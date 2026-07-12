<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class ProductFilterController extends Controller
{
    // ================================================================
    // 1. GET FILTER OPTIONS (گزینه‌های فیلتر)
    // ================================================================
    #[OA\Get(
        path: "/api/products/filter-options",
        tags: ["Products"],
        summary: "Get filter options",
        description: "Get all available filter options (brands, categories, price range, attributes).",
        responses: [
            new OA\Response(
                response: 200,
                description: "Filter options retrieved successfully."
            )
        ]
    )]
    public function options()
    {
        // دریافت همه برندها
        $brands = Brand::where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        // دریافت همه دسته‌بندی‌ها
        $categories = Category::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'parent_id']);

        // دریافت محدوده قیمت
        $minPrice = DB::table('product_variants')->min('sale_price') ?? 0;
        $maxPrice = DB::table('product_variants')->max('sale_price') ?? 0;

        // دریافت ویژگی‌های قابل فیلتر
        $attributes = Attribute::where('is_filterable', 1)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'type']);

        // دریافت مقادیر ویژگی‌ها
        $attributeValues = DB::table('attribute_values')
            ->where('is_active', 1)
            ->get(['id', 'attribute_id', 'value', 'slug', 'color_code']);

        return response()->json([
            'success' => true,
            'data' => [
                'brands' => $brands,
                'categories' => $categories,
                'price_range' => [
                    'min' => (int) $minPrice,
                    'max' => (int) $maxPrice,
                ],
                'attributes' => $attributes,
                'attribute_values' => $attributeValues,
            ]
        ]);
    }

    // ================================================================
    // 2. FILTER PRODUCTS (فیلتر پیشرفته)
    // ================================================================
    #[OA\Get(
        path: "/api/products/filter",
        tags: ["Products"],
        summary: "Filter products",
        description: "Filter products by category, brand, price range, attributes, and sort.",
        parameters: [
            new OA\Parameter(
                name: "category_id",
                in: "query",
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "brand_id",
                in: "query",
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "min_price",
                in: "query",
                description: "Minimum price",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "max_price",
                in: "query",
                description: "Maximum price",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "Search query",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "attributes",
                in: "query",
                description: "Filter by attribute values (comma separated IDs)",
                schema: new OA\Schema(type: "string", example: "1,2,3")
            ),
            new OA\Parameter(
                name: "sort_by",
                in: "query",
                description: "Sort by field (price, view_count, created_at, title)",
                schema: new OA\Schema(type: "string", default: "created_at")
            ),
            new OA\Parameter(
                name: "sort_order",
                in: "query",
                description: "Sort order (asc, desc)",
                schema: new OA\Schema(type: "string", default: "desc")
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "Items per page",
                schema: new OA\Schema(type: "integer", default: 15)
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Page number",
                schema: new OA\Schema(type: "integer", default: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Filtered products retrieved successfully."
            )
        ]
    )]
    public function filter(Request $request)
    {
        $query = Product::query()
            ->with([
                'brand',
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->where('is_active', 1);

        // ===== 1. فیلتر بر اساس دسته‌بندی =====
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // ===== 2. فیلتر بر اساس برند =====
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // ===== 3. فیلتر بر اساس محدوده قیمت =====
        if ($request->has('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere('price', '<=', $request->max_price);
            });
        }

        // ===== 4. فیلتر بر اساس ویژگی‌ها (attribute values) =====
        if ($request->has('attributes') && $request->attributes) {
            $attributeIds = explode(',', $request->attributes);
            $query->whereHas('variants.attributeValues', function($q) use ($attributeIds) {
                $q->whereIn('attribute_value_id', $attributeIds);
            });
        }

        // ===== 5. جستجو =====
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // ===== 6. مرتب‌سازی =====
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['price', 'view_count', 'created_at', 'title', 'sort_order'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        if ($sortBy === 'price') {
            $query->orderBy(
                \App\Models\ProductVariant::select('sale_price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('sale_price', $sortOrder)
                    ->limit(1)
            );
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // ===== 7. صفحه‌بندی =====
        $perPage = $request->get('per_page', 15);
        if ($perPage > 100) {
            $perPage = 100;
        }

        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    // ================================================================
    // 3. SEARCH PRODUCTS (جستجوی ساده)
    // ================================================================
    #[OA\Get(
        path: "/api/products/search",
        tags: ["Products"],
        summary: "Search products",
        description: "Search products by title or description.",
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                required: true,
                description: "Search query",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Search results retrieved successfully."
            )
        ]
    )]
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::query()
            ->with([
                'brand',
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->where('is_active', 1)
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return ProductResource::collection($products);
    }

    // ================================================================
    // 4. ADVANCED SEARCH (جستجوی پیشرفته با فیلتر)
    // ================================================================
    #[OA\Get(
        path: "/api/products/advanced-search",
        tags: ["Products"],
        summary: "Advanced search",
        description: "Search products with filters.",
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                description: "Search query",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "category_id",
                in: "query",
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "brand_id",
                in: "query",
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "min_price",
                in: "query",
                description: "Minimum price",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "max_price",
                in: "query",
                description: "Maximum price",
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Search results retrieved successfully."
            )
        ]
    )]
    public function advancedSearch(Request $request)
    {
        $query = Product::query()
            ->with([
                'brand',
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->where('is_active', 1);

        // جستجو
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // فیلتر دسته‌بندی
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // فیلتر برند
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // فیلتر قیمت
        if ($request->has('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere('price', '<=', $request->max_price);
            });
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->latest()->paginate($perPage);

        return ProductResource::collection($products);
    }

   // ================================================================
    // INFINITE SCROLL (اسکرول نامحدود) با قابلیت مرتب‌سازی
    // ================================================================
    #[OA\Get(
        path: "/api/products/infinite",
        tags: ["Products"],
        summary: "Infinite scroll products",
        description: "Load products with cursor-based pagination for infinite scroll. You can sort by id, price, view_count, created_at, title.",
        parameters: [
            new OA\Parameter(
                name: "cursor",
                in: "query",
                description: "Cursor for pagination (get from response meta.next_cursor)",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "Number of products per request (max: 50)",
                schema: new OA\Schema(type: "integer", default: 20)
            ),
            new OA\Parameter(
                name: "category_id",
                in: "query",
                description: "Category ID (e.g., 2 for all mobile, 3 for Apple phones, 12 for Samsung, 20 for Xiaomi, 27 for Other Brands)",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "brand_id",
                in: "query",
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "min_price",
                in: "query",
                description: "Minimum price",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "max_price",
                in: "query",
                description: "Maximum price",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "attributes",
                in: "query",
                description: "Filter by attribute values (comma separated IDs, e.g., '1,2,3')",
                schema: new OA\Schema(type: "string", example: "")
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "Search query for product title or description",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "sort_by",
                in: "query",
                description: "Sort by field (id, price, view_count, created_at, title)",
                schema: new OA\Schema(type: "string", default: "id", enum: ["id", "price", "view_count", "created_at", "title"])
            ),
            new OA\Parameter(
                name: "sort_order",
                in: "query",
                description: "Sort order (asc: ascending, desc: descending)",
                schema: new OA\Schema(type: "string", default: "asc", enum: ["asc", "desc"])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Products loaded successfully.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "success",
                            type: "boolean",
                            example: true
                        ),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer"),
                                    new OA\Property(property: "title", type: "string"),
                                    new OA\Property(property: "slug", type: "string"),
                                    new OA\Property(property: "short_description", type: "string"),
                                    new OA\Property(property: "description", type: "string"),
                                    new OA\Property(property: "status", type: "string"),
                                    new OA\Property(property: "view_count", type: "integer"),
                                    new OA\Property(property: "is_active", type: "boolean"),
                                    new OA\Property(property: "created_at", type: "string"),
                                    new OA\Property(property: "updated_at", type: "string"),
                                    new OA\Property(
                                        property: "brand",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer"),
                                            new OA\Property(property: "name", type: "string"),
                                            new OA\Property(property: "slug", type: "string"),
                                        ],
                                        type: "object"
                                    ),
                                    new OA\Property(
                                        property: "categories",
                                        type: "array",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "id", type: "integer"),
                                                new OA\Property(property: "name", type: "string"),
                                                new OA\Property(property: "slug", type: "string"),
                                            ]
                                        )
                                    ),
                                    new OA\Property(
                                        property: "variants",
                                        type: "array",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "id", type: "integer"),
                                                new OA\Property(property: "sku", type: "string"),
                                                new OA\Property(property: "price", type: "integer"),
                                                new OA\Property(property: "sale_price", type: "integer"),
                                                new OA\Property(property: "stock", type: "integer"),
                                                new OA\Property(property: "weight", type: "integer"),
                                                new OA\Property(
                                                    property: "attributes",
                                                    type: "array",
                                                    items: new OA\Items(
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer"),
                                                            new OA\Property(property: "attribute_name", type: "string"),
                                                            new OA\Property(property: "value", type: "string"),
                                                            new OA\Property(property: "color_code", type: "string"),
                                                        ]
                                                    )
                                                ),
                                            ]
                                        )
                                    ),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "meta",
                            properties: [
                                new OA\Property(property: "has_more", type: "boolean"),
                                new OA\Property(property: "next_cursor", type: "string", nullable: true),
                                new OA\Property(property: "limit", type: "integer"),
                            ],
                            type: "object"
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            )
        ]
    )]
    public function infinite(Request $request)
    {
        $limit = $request->get('limit', 20);
        if ($limit > 50) {
            $limit = 50;
        }

        $query = Product::query()
            ->with([
                'brand',
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->where('is_active', 1);

        // ===== فیلتر دسته‌بندی با زیردسته‌ها =====
        if ($request->has('category_id') && $request->category_id) {
            $categoryId = $request->category_id;
            $subCategoryIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
            $allCategoryIds = array_merge([$categoryId], $subCategoryIds);
            
            $query->whereHas('categories', function($q) use ($allCategoryIds) {
                $q->whereIn('category_id', $allCategoryIds);
            });
        }

        // ===== فیلتر برند =====
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // ===== فیلتر قیمت =====
        if ($request->has('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere('price', '<=', $request->max_price);
            });
        }

        // ===== فیلتر ویژگی‌ها =====
        if ($request->has('attributes') && $request->attributes) {
            $attributeIds = explode(',', $request->attributes);
            $query->whereHas('variants.attributeValues', function($q) use ($attributeIds) {
                $q->whereIn('attribute_value_id', $attributeIds);
            });
        }

        // ===== جستجو =====
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // ===== مرتب‌سازی (Sorting) =====
        $allowedSortFields = ['id', 'price', 'view_count', 'created_at', 'title'];
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'id';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        if ($sortBy === 'price') {
            $query->orderBy(
                \App\Models\ProductVariant::select('sale_price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('sale_price', $sortOrder)
                    ->limit(1)
            );
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // ===== Cursor-based pagination =====
        $cursor = $request->get('cursor');
        
        if ($cursor) {
            $decoded = json_decode(base64_decode($cursor), true);
            if ($decoded && isset($decoded['id'])) {
                if ($sortOrder === 'asc') {
                    $query->where('id', '>', $decoded['id']);
                } else {
                    $query->where('id', '<', $decoded['id']);
                }
            }
        }

        $products = $query->limit($limit + 1)->get();

        $hasMore = $products->count() > $limit;
        $products = $products->take($limit);

        $nextCursor = null;
        if ($hasMore && $products->isNotEmpty()) {
            $lastProduct = $products->last();
            $nextCursor = base64_encode(json_encode([
                'id' => $lastProduct->id,
            ]));
        }

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
                'limit' => $limit,
            ]
        ]);
    }
}