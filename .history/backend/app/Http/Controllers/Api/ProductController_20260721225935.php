<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Services\ProductService;
use App\Services\Discount\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Product management"
)]
#[OA\Schema(
    schema: "Product",
    title: "Product",
    description: "Product model",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "iPhone 15 Pro"),
        new OA\Property(property: "slug", type: "string", example: "iphone-15-pro"),
        new OA\Property(property: "short_description", type: "string", nullable: true),
        new OA\Property(property: "description", type: "string", nullable: true),
        new OA\Property(property: "status", type: "string", enum: ["draft", "active", "inactive"], example: "active"),
        new OA\Property(property: "view_count", type: "integer", example: 100),
        new OA\Property(property: "is_active", type: "boolean", example: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "brand",
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer"),
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "slug", type: "string"),
            ]
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
            property: "images",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "image_path", type: "string"),
                    new OA\Property(property: "url", type: "string"),
                    new OA\Property(property: "is_main", type: "boolean"),
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
                    new OA\Property(property: "base_price", type: "integer"),
                    new OA\Property(
                        property: "final_price",
                        type: "integer",
                        example: 800,
                        description: "Final price after discount calculation"
                    ),
                    new OA\Property(property: "discount_amount", type: "integer", example: 200),
                    new OA\Property(property: "discount_percent", type: "integer", example: 20),
                    new OA\Property(property: "stock", type: "integer"),
                    new OA\Property(property: "is_default", type: "boolean"),
                    new OA\Property(
                        property: "attributes",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "attribute_name", type: "string"),
                                new OA\Property(property: "value", type: "string"),
                                new OA\Property(property: "color_code", type: "string", nullable: true),
                            ]
                        )
                    ),
                ]
            )
        ),
    ]
)]
class ProductController extends Controller
{
    private array $defaultRelations = [
        'brand.discounts',
        'categories.discounts',
        'images',
        'variants.discounts',
        'variants.attributeValues',
        'discounts'
    ];

    public function __construct(
        private ProductService $productService
    ) {}

    // ================================================================
    // INDEX
    // ================================================================
    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'List products with filters',
        description: 'Get products with filtering, search, sorting and pagination. Supports discount filtering for deal pages.',
        parameters: [
            new OA\Parameter(
                name: 'category_id',
                in: 'query',
                description: 'Category ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'brand_id',
                in: 'query',
                description: 'Brand ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'min_price',
                in: 'query',
                description: 'Minimum price (based on base_price)',
                schema: new OA\Schema(type: 'integer', example: 100000)
            ),
            new OA\Parameter(
                name: 'max_price',
                in: 'query',
                description: 'Maximum price (based on base_price)',
                schema: new OA\Schema(type: 'integer', example: 500000)
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                description: 'Search in title and description',
                schema: new OA\Schema(type: 'string', example: 'iphone')
            ),
            new OA\Parameter(
                name: 'attributes_id',
                in: 'query',
                description: 'Filter by attribute values (comma separated)',
                schema: new OA\Schema(type: 'string', example: '1,2,3')
            ),
            new OA\Parameter(
                name: 'min_discount',
                in: 'query',
                description: 'Minimum discount percentage. Only returns products with discount >= this value',
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 0,
                    maximum: 100,
                    example: 20
                )
            ),
            new OA\Parameter(
                name: 'max_discount',
                in: 'query',
                description: 'Maximum discount percentage',
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 0,
                    maximum: 100,
                    example: 50
                )
            ),
            new OA\Parameter(
                name: 'has_discount',
                in: 'query',
                description: 'Show only products with any discount',
                schema: new OA\Schema(type: 'boolean', example: true)
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                description: 'Sort field',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'created_at',
                    enum: ['base_price', 'view_count', 'created_at', 'title']
                )
            ),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                description: 'Sort order',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'desc',
                    enum: ['asc', 'desc']
                )
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Items per page',
                schema: new OA\Schema(type: 'integer', default: 20, minimum: 1, maximum: 100)
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number',
                schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)
            ),
            new OA\Parameter(
                name: 'cursor',
                in: 'query',
                description: 'Cursor for infinite scroll (get from meta.next_cursor)',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Products retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Product')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                                new OA\Property(property: 'has_more', type: 'boolean'),
                                new OA\Property(property: 'next_cursor', type: 'string', nullable: true),
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $query = Product::query()
            ->with($this->defaultRelations)
            ->where('is_active', 1);

        // ===== 1. Category filter =====
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            $subCategoryIds = Category::where('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();
            $allCategoryIds = array_merge([$categoryId], $subCategoryIds);

            $query->whereHas('categories', fn($q) =>
                $q->whereIn('category_id', $allCategoryIds)
            );
        }

        // ===== 2. Brand filter =====
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // ===== 3. Price filter =====
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->has('min_price')) {
                    $q->where('base_price', '>=', $request->min_price);
                }
                if ($request->has('max_price')) {
                    $q->where('base_price', '<=', $request->max_price);
                }
            });
        }

        // ===== 4. Attribute filter =====
        if ($request->has('attributes_id')) {
            $attributeIds = explode(',', $request->attributes_id);
            $query->whereHas('variants.attributeValues', fn($q) =>
                $q->whereIn('attribute_value_id', $attributeIds)
            );
        }

        // ===== 5. Search =====
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
            );
        }

        // ===== 6. Sorting =====
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $sortByPrice = $sortBy === 'base_price';

        if (!$sortByPrice) {
            $allowedSortFields = ['view_count', 'created_at', 'title', 'sort_order'];

            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'created_at';
            }

            $query->orderBy($sortBy, $sortOrder);
        }

        // ===== 7. ✅ Discount filter (PHP-side) =====
        $hasDiscountFilter = $request->has('min_discount') 
            || $request->has('max_discount') 
            || $request->boolean('has_discount');

        if ($hasDiscountFilter) {
            return $this->filterByDiscount($query, $request, $sortByPrice, $sortOrder);
        }

        // ===== 8. Pagination =====
        if ($request->has('cursor')) {
            return $this->cursorPaginate($query, $request, $sortByPrice, $sortOrder);
        }

        return $this->standardPaginate($query, $request, $sortByPrice, $sortOrder);
    }

    // ================================================================
    // ✅ Discount Filter (PHP-side because discount is dynamic)
    // ================================================================
    private function filterByDiscount($query, $request, bool $sortByPrice, string $sortOrder)
    {
        $discountService = app(DiscountService::class);

        // گرفتن همه محصولات و محاسبه قیمت نهایی
        $products = $query->get()
            ->map(function ($product) use ($discountService) {
                $variant = $product->variants->firstWhere('is_default', true)
                        ?? $product->variants->firstWhere('is_active', true)
                        ?? $product->variants->first();

                if (!$variant) {
                    $product->_discount_percent = 0;
                    $product->_final_price = 0;
                    return $product;
                }

                $pricing = $discountService->calculate($variant);

                $product->_discount_percent = $pricing->basePrice > 0
                    ? round(($pricing->discountAmount / $pricing->basePrice) * 100)
                    : 0;
                $product->_final_price = $pricing->price;

                return $product;
            })
            ->filter(function ($product) use ($request) {
                // فقط تخفیف‌دارها
                if ($product->_discount_percent <= 0) return false;

                // فیلتر حداقل درصد
                if ($request->has('min_discount')) {
                    if ($product->_discount_percent < (int) $request->min_discount) return false;
                }

                // فیلتر حداکثر درصد
                if ($request->has('max_discount')) {
                    if ($product->_discount_percent > (int) $request->max_discount) return false;
                }

                return true;
            });

        // سورت
        if ($sortByPrice) {
            $products = $products->sortBy(
                '_final_price',
                SORT_REGULAR,
                $sortOrder === 'desc'
            );
        }

        $products = $products->values();

        // Pagination
        $perPage = min((int) $request->get('per_page', 20), 100);
        $page = max((int) $request->get('page', 1), 1);
        $total = $products->count();
        $items = $products->slice(($page - 1) * $perPage, $perPage)->values();
        $lastPage = (int) ceil($total / $perPage);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($items),
            'meta' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => $page < $lastPage,
                'next_cursor' => null,
                'filters_applied' => [
                    'min_discount' => (int) $request->get('min_discount', 0),
                    'max_discount' => $request->has('max_discount') 
                        ? (int) $request->max_discount 
                        : null,
                    'has_discount' => $request->boolean('has_discount'),
                ]
            ]
        ]);
    }

    // ================================================================
    // Standard Pagination
    // ================================================================
    private function standardPaginate($query, $request, bool $sortByPrice = false, string $sortOrder = 'desc')
    {
        $perPage = min($request->get('per_page', 20), 100);
        $page = max($request->get('page', 1), 1);

        if ($sortByPrice) {
            $discountService = app(DiscountService::class);

            $sorted = $query->get()
                ->map(function ($product) use ($discountService) {
                    $variant = $product->variants->firstWhere('is_default', true)
                            ?? $product->variants->firstWhere('is_active', true)
                            ?? $product->variants->first();

                    $product->_effective_price = $variant
                        ? $discountService->calculate($variant)->price
                        : 0;

                    return $product;
                })
                ->sortBy('_effective_price', SORT_REGULAR, $sortOrder === 'desc')
                ->values();

            $total = $sorted->count();
            $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();
            $lastPage = (int) ceil($total / $perPage);

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($items),
                'meta' => [
                    'current_page' => $page,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total,
                    'has_more' => $page < $lastPage,
                    'next_cursor' => null,
                    'sort_note' => 'sorted_by_final_price',
                ]
            ]);
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $products->hasMorePages(),
                'next_cursor' => null,
            ]
        ]);
    }

    // ================================================================
    // Cursor Pagination
    // ================================================================
    private function cursorPaginate($query, $request, bool $sortByPrice = false, string $sortOrder = 'desc')
    {
        $limit = min($request->get('limit', 20), 50);
        $cursor = $request->get('cursor');

        if ($sortByPrice) {
            $discountService = app(DiscountService::class);

            $allProducts = $query->get()
                ->map(function ($product) use ($discountService) {
                    $variant = $product->variants->firstWhere('is_default', true)
                            ?? $product->variants->firstWhere('is_active', true)
                            ?? $product->variants->first();

                    $product->_effective_price = $variant
                        ? $discountService->calculate($variant)->price
                        : 0;

                    return $product;
                })
                ->sortBy('_effective_price', SORT_REGULAR, $sortOrder === 'desc')
                ->values();

            $startIndex = 0;
            if ($cursor) {
                $decoded = json_decode(base64_decode($cursor), true);
                if ($decoded && isset($decoded['index'])) {
                    $startIndex = $decoded['index'];
                }
            }

            $items = $allProducts->slice($startIndex, $limit + 1)->values();
            $hasMore = $items->count() > $limit;
            $items = $items->take($limit);

            $nextCursor = null;
            if ($hasMore) {
                $nextCursor = base64_encode(json_encode([
                    'index' => $startIndex + $limit
                ]));
            }

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($items),
                'meta' => [
                    'has_more' => $hasMore,
                    'next_cursor' => $nextCursor,
                    'limit' => $limit,
                    'current_page' => null,
                    'last_page' => null,
                    'per_page' => $limit,
                    'total' => $allProducts->count(),
                ]
            ]);
        }

        if ($cursor) {
            $decoded = json_decode(base64_decode($cursor), true);
            if ($decoded && isset($decoded['id'])) {
                $operator = $sortOrder === 'asc' ? '>' : '<';
                $query->where('id', $operator, $decoded['id']);
            }
        }

        $products = $query->limit($limit + 1)->get();
        $hasMore = $products->count() > $limit;
        $products = $products->take($limit);

        $nextCursor = null;
        if ($hasMore && $products->isNotEmpty()) {
            $nextCursor = base64_encode(json_encode([
                'id' => $products->last()->id
            ]));
        }

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
                'limit' => $limit,
                'current_page' => null,
                'last_page' => null,
                'per_page' => $limit,
                'total' => null,
            ]
        ]);
    }

    // ================================================================
    // Show Product Details
    // ================================================================
    #[OA\Get(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Show product details',
        description: 'Get complete product information with all relationships',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Product')
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Product not found')
        ]
    )]
    public function show(Product $product)
    {
        $product->load(array_merge($this->defaultRelations, [
            'variants.attributeValues.attribute'
        ]));

        return new ProductResource($product);
    }

    // ================================================================
    // Create Product
    // ================================================================
    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Create new product',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['brand_id', 'title', 'slug'],
                properties: [
                    new OA\Property(property: 'brand_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15 Pro'),
                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro'),
                    new OA\Property(property: 'short_description', type: 'string', nullable: true),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string', enum: ['draft', 'active', 'inactive'], example: 'active'),
                    new OA\Property(property: 'meta_title', type: 'string', nullable: true),
                    new OA\Property(property: 'meta_keywords', type: 'string', nullable: true),
                    new OA\Property(property: 'meta_description', type: 'string', nullable: true),
                    new OA\Property(property: 'sort_order', type: 'integer', example: 0),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Product created'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // Update Product
    // ================================================================
    #[OA\Put(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Update product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'brand_id', type: 'integer'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'slug', type: 'string'),
                    new OA\Property(property: 'short_description', type: 'string', nullable: true),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string', enum: ['draft', 'active', 'inactive']),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Product updated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update($product, $request->validated());
        return new ProductResource($product);
    }

    // ================================================================
    // Delete Product
    // ================================================================
    #[OA\Delete(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Delete product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted successfully'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }
}