<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'List Products',
        description: 'Get all products with pagination.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Products retrieved successfully.'
            ),
        ]
    )]
    public function index()
    {
        $products = Product::query()
            ->with([
                'brand',
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->latest()
            ->paginate(15);

        return ProductResource::collection($products);
    }

    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Create Product',
        description: 'Create a new product with variants and attributes.',
        security: [
            ['bearerAuth' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['brand_id', 'title', 'slug'],
                properties: [
                    // ===== اطلاعات اصلی محصول =====
                    new OA\Property(property: 'brand_id', type: 'integer', example: 1, description: 'شناسه برند'),
                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15 Pro', description: 'عنوان محصول'),
                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro', description: 'اسلاگ محصول (آدرس سئویی)'),
                    new OA\Property(property: 'short_description', type: 'string', example: 'گوشی آیفون ۱۵ پرو با پردازنده A16', description: 'توضیح مختصر'),
                    new OA\Property(property: 'description', type: 'string', example: '<p>توضیحات کامل محصول</p>', description: 'توضیح کامل (HTML)'),
                    new OA\Property(property: 'status', type: 'string', example: 'active', description: 'وضعیت (active/inactive/draft)'),
                    new OA\Property(property: 'meta_title', type: 'string', example: 'iPhone 15 Pro', description: 'عنوان سئو'),
                    new OA\Property(property: 'meta_keywords', type: 'string', example: 'iphone, apple', description: 'کلمات کلیدی سئو'),
                    new OA\Property(property: 'meta_description', type: 'string', example: 'خرید آیفون ۱۵ پرو', description: 'توضیحات سئو'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true, description: 'فعال/غیرفعال'),
                    
                    // ===== دسته‌بندی‌ها =====
                    new OA\Property(
                        property: 'categories',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2],
                        description: 'آرایه شناسه دسته‌بندی‌ها'
                    ),
                    
                    // ===== تنوع‌ها (Variants) =====
                    new OA\Property(
                        property: 'variants',
                        type: 'array',
                        description: 'لیست تنوع‌های محصول',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'sku', type: 'string', example: 'SKU-001', description: 'کد انبار'),
                                new OA\Property(property: 'barcode', type: 'string', example: '1234567890123', description: 'بارکد'),
                                new OA\Property(property: 'price', type: 'integer', example: 30000000, description: 'قیمت اصلی (تومان)'),
                                new OA\Property(property: 'sale_price', type: 'integer', example: 25000000, description: 'قیمت تخفیف‌خورده (تومان)'),
                                new OA\Property(property: 'stock', type: 'integer', example: 10, description: 'موجودی انبار'),
                                new OA\Property(property: 'weight', type: 'integer', example: 200, description: 'وزن (گرم)'),
                                new OA\Property(property: 'is_active', type: 'boolean', example: true, description: 'فعال/غیرفعال'),
                                new OA\Property(
                                    property: 'attributes',
                                    type: 'array',
                                    description: 'ویژگی‌های این تنوع',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'attribute_value_id', type: 'integer', example: 1, description: 'شناسه مقدار ویژگی')
                                        ]
                                    )
                                )
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product created successfully.'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error'
            ),
        ]
    )]
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create(
            $request->validated()
        );

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Show Product',
        description: 'Get product details with variants and attributes.',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product retrieved successfully.'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found.'
            ),
        ]
    )]
    public function show(Product $product)
    {
        $product->load([
            'brand',
            'categories',
            'images',
            'variants.attributeValues.attribute',
        ]);

        return new ProductResource($product);
    }

    #[OA\Put(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Update Product',
        description: 'Update product information with variants.',
        security: [
            ['bearerAuth' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    // ===== اطلاعات اصلی محصول =====
                    new OA\Property(property: 'brand_id', type: 'integer', example: 1, description: 'شناسه برند'),
                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15 Pro', description: 'عنوان محصول'),
                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro', description: 'اسلاگ محصول'),
                    new OA\Property(property: 'short_description', type: 'string', example: 'گوشی آیفون ۱۵ پرو', description: 'توضیح مختصر'),
                    new OA\Property(property: 'description', type: 'string', example: '<p>توضیحات کامل</p>', description: 'توضیح کامل'),
                    new OA\Property(property: 'status', type: 'string', example: 'active', description: 'وضعیت'),
                    new OA\Property(property: 'meta_title', type: 'string', example: 'iPhone 15 Pro', description: 'عنوان سئو'),
                    new OA\Property(property: 'meta_keywords', type: 'string', example: 'iphone, apple', description: 'کلمات کلیدی'),
                    new OA\Property(property: 'meta_description', type: 'string', example: 'خرید آیفون ۱۵ پرو', description: 'توضیحات سئو'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true, description: 'فعال/غیرفعال'),
                    
                    // ===== دسته‌بندی‌ها =====
                    new OA\Property(
                        property: 'categories',
                        type: 'array',
                        items: new OA\Items(type: 'integer'),
                        example: [1, 2],
                        description: 'آرایه شناسه دسته‌بندی‌ها'
                    ),
                    
                    // ===== تنوع‌ها (Variants) =====
                    new OA\Property(
                        property: 'variants',
                        type: 'array',
                        description: 'لیست تنوع‌های محصول',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1, description: 'شناسه تنوع (برای آپدیت)'),
                                new OA\Property(property: 'sku', type: 'string', example: 'SKU-001', description: 'کد انبار'),
                                new OA\Property(property: 'barcode', type: 'string', example: '1234567890123', description: 'بارکد'),
                                new OA\Property(property: 'price', type: 'integer', example: 30000000, description: 'قیمت اصلی'),
                                new OA\Property(property: 'sale_price', type: 'integer', example: 25000000, description: 'قیمت تخفیف‌خورده'),
                                new OA\Property(property: 'stock', type: 'integer', example: 10, description: 'موجودی'),
                                new OA\Property(property: 'weight', type: 'integer', example: 200, description: 'وزن'),
                                new OA\Property(property: 'is_active', type: 'boolean', example: true, description: 'فعال/غیرفعال'),
                                new OA\Property(
                                    property: 'attributes',
                                    type: 'array',
                                    description: 'ویژگی‌های تنوع',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'attribute_value_id', type: 'integer', example: 1, description: 'شناسه مقدار ویژگی')
                                        ]
                                    )
                                )
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product updated successfully.'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found.'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error'
            ),
        ]
    )]
    public function update(
        UpdateProductRequest $request,
        Product $product
    ) {
        $product = $this->productService->update(
            $product,
            $request->validated()
        );

        return new ProductResource($product);
    }

    #[OA\Delete(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Delete Product',
        description: 'Delete a product.',
        security: [
            ['bearerAuth' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product deleted successfully.'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found.'
            ),
        ]
    )]
    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
