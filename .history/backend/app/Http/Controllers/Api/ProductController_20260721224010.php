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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Product management"
)]
#[OA\Get(
    path: '/api/products',
    tags: ['Products'],
    summary: 'List products with filters',
    description: 'Get products with filtering, search, sorting and pagination. Supports discount filtering for special deal pages.',
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
            description: 'Minimum price',
            schema: new OA\Schema(type: 'integer', example: 100000)
        ),
        new OA\Parameter(
            name: 'max_price',
            in: 'query',
            description: 'Maximum price',
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

        // ================================================
        // ✅ NEW: Discount Filters
        // ================================================
        new OA\Parameter(
            name: 'min_discount',
            in: 'query',
            description: 'Minimum discount percentage (0-100). Returns only products with discount >= this value',
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
            description: 'Maximum discount percentage (0-100). Returns only products with discount <= this value',
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
            description: 'Show only products that have any discount',
            schema: new OA\Schema(
                type: 'boolean',
                example: true
            )
        ),

        // ================================================
        // Sorting & Pagination
        // ================================================
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
            schema: new OA\Schema(
                type: 'integer',
                default: 20,
                minimum: 1,
                maximum: 100
            )
        ),
        new OA\Parameter(
            name: 'page',
            in: 'query',
            description: 'Page number',
            schema: new OA\Schema(
                type: 'integer',
                default: 1,
                minimum: 1
            )
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
                            new OA\Property(property: 'current_page', type: 'integer', example: 1),
                            new OA\Property(property: 'last_page', type: 'integer', example: 10),
                            new OA\Property(property: 'per_page', type: 'integer', example: 20),
                            new OA\Property(property: 'total', type: 'integer', example: 200),
                            new OA\Property(property: 'has_more', type: 'boolean', example: true),
                            new OA\Property(property: 'next_cursor', type: 'string', nullable: true),
                        ]
                    )
                ]
            )
        ),
        new OA\Response(
            response: 422,
            description: 'Validation error'
        )
    ]

    public function index(Request $request)
    {
        $query = Product::query()
            ->with([
                'brand.discounts',        // ✅ eager load
                'categories.discounts',   // ✅ eager load
                'images',
                'variants.discounts',     // ✅ eager load
                'variants.attributeValues',
                'discounts'
            ])
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
        // ⚠️ فعلاً روی base_price میمونه چون final_price در DB نداریم
        // بعد از اینکه sort در PHP انجام شد، این هم باید PHP-side بشه
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
    
        // ✅ سورت قیمت رو جدا هندل می‌کنیم (در PHP)
        $sortByPrice = $sortBy === 'base_price';
    
        if (!$sortByPrice) {
            $allowedSortFields = ['view_count', 'created_at', 'title', 'sort_order'];
    
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'created_at';
            }
    
            $query->orderBy($sortBy, $sortOrder);
        }
    
        // ===== 7. Cursor یا Standard =====
        if ($request->has('cursor')) {
            return $this->cursorPaginate($query, $request, $sortByPrice, $sortOrder);
        }
    
        return $this->standardPaginate($query, $request, $sortByPrice, $sortOrder);

        // ===== 8. Discount filter =====
    if ($request->has('min_discount') || $request->has('has_discount')) {
        
        $discountService = app(\App\Services\Discount\DiscountService::class);
        
        // ✅ چون تخفیف داینامیکه، باید همه محصولات رو بگیریم و فیلتر کنیم
        $allProducts = $query->get();
        
        $filtered = $allProducts->filter(function ($product) use ($request, $discountService) {
            
            $variant = $product->variants
                ->firstWhere('is_default', true)
                ?? $product->variants->firstWhere('is_active', true)
                ?? $product->variants->first();
            
            if (!$variant) return false;
            
            $pricing = $discountService->calculate($variant);
            
            // ✅ اگه تخفیف نداره، رد کن
            if ($pricing->discountAmount <= 0) return false;
            
            // ✅ درصد تخفیف
            $discountPercent = $pricing->basePrice > 0
                ? round(($pricing->discountAmount / $pricing->basePrice) * 100)
                : 0;
            
            // ✅ فیلتر بر اساس حداقل درصد تخفیف
            if ($request->has('min_discount')) {
                if ($discountPercent < (int) $request->min_discount) return false;
            }
            
            // ✅ فیلتر بر اساس حداکثر درصد تخفیف
            if ($request->has('max_discount')) {
                if ($discountPercent > (int) $request->max_discount) return false;
            }
            
            return true;
        });
        
        // pagination دستی
        $perPage = min($request->get('per_page', 20), 100);
        $page = max($request->get('page', 1), 1);
        $total = $filtered->count();
        $items = $filtered->slice(($page - 1) * $perPage, $perPage)->values();
        
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($items),
            'meta' => [
                'current_page' => $page,
                'last_page' => (int) ceil($total / $perPage),
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => ($page * $perPage) < $total,
            ]
        ]);
    }
    }


    // ================================================================
    // Standard Pagination
    // ================================================================
    private function standardPaginate($query, $request, bool $sortByPrice = false, string $sortOrder = 'desc')
    {
        $perPage = min($request->get('per_page', 20), 100);
        $page = max($request->get('page', 1), 1);
    
        if ($sortByPrice) {
            // ✅ همه محصولات رو می‌گیریم و در PHP سورت می‌کنیم
            $allProducts = $query->get();
    
            $discountService = app(\App\Services\Discount\DiscountService::class);
    
            // ✅ قیمت نهایی هر محصول رو حساب می‌کنیم
            $sorted = $allProducts
                ->map(function ($product) use ($discountService) {
    
                    // default variant یا اولین variant فعال
                    $variant = $product->variants->firstWhere('is_default', true)
                            ?? $product->variants->firstWhere('is_active', true)
                            ?? $product->variants->first();
    
                    // اگه variant نداشت، قیمت صفر
                    if (!$variant) {
                        $product->_effective_price = 0;
                        return $product;
                    }
    
                    // ✅ محاسبه قیمت نهایی با تخفیف
                    $pricing = $discountService->calculate($variant);
                    $product->_effective_price = $pricing->price;
    
                    return $product;
                })
                ->sortBy(
                    '_effective_price',
                    SORT_REGULAR,
                    $sortOrder === 'desc' // ✅ desc = بزرگ به کوچیک
                )
                ->values();
    
            // ✅ pagination دستی
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
                    'sort_note' => 'sorted_by_final_price', // برای debug
                ]
            ]);
        }
    
        // ✅ سورت معمولی با pagination دیتابیس
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
    // Infinite Scroll (Cursor-based)
    // ================================================================
    private function cursorPaginate($query, $request, bool $sortByPrice = false, string $sortOrder = 'desc')
    {
        $limit = min($request->get('limit', 20), 50);
        $cursor = $request->get('cursor');
    
        if ($sortByPrice) {
            // ✅ برای cursor + sort قیمت هم همه رو می‌گیریم
            $discountService = app(\App\Services\Discount\DiscountService::class);
    
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
    
            // ✅ cursor بر اساس index در لیست مرتب‌شده
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
    
        // ✅ cursor معمولی (بدون sort قیمت)
        if ($cursor) {
            $decoded = json_decode(base64_decode($cursor), true);
            if ($decoded && isset($decoded['id'])) {
                $sortOrder = $request->get('sort_order', 'desc');
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
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
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
        description: 'Create a new product',
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
            new OA\Response(
                response: 201,
                description: 'Product created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Product')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            )
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
        description: 'Update product information',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
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
            new OA\Response(
                response: 200,
                description: 'Product updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Product')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            )
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
        description: 'Delete a product',
        security: [['bearerAuth' => []]],
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
                description: 'Product deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product deleted successfully.')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            )
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