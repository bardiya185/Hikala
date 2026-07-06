<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
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
                'variants.inventory',
                'variants.attributeValues.attribute', // این رو اضافه کن
            ])
            ->latest()
            ->paginate(15);

        return ProductResource::collection($products);
    }

    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Create Product',
        description: 'Create a new product.',
        security: [
            ['bearerAuth' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'slug'],
                properties: [
                    new OA\Property(property: 'brand_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15 Pro'),
                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro'),
                    new OA\Property(property: 'short_description', type: 'string', example: 'Short description'),
                    new OA\Property(property: 'description', type: 'string', example: '<p>Full description</p>'),
                    new OA\Property(property: 'status', type: 'string', example: 'active'),
                    new OA\Property(property: 'meta_title', type: 'string', example: 'iPhone 15 Pro'),
                    new OA\Property(property: 'meta_keywords', type: 'string', example: 'iphone, apple'),
                    new OA\Property(property: 'meta_description', type: 'string', example: 'Buy iPhone 15 Pro'),
                    new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2]),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
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
        description: 'Get product details.',
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
            'variants.inventory',
           'variants.attributeValues.attribute', // این رو اضافه کن
        ]);

        return new ProductResource($product);
    }

    #[OA\Put(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Update Product',
        description: 'Update product information.',
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
                    new OA\Property(property: 'brand_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'iPhone 15 Pro'),
                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro'),
                    new OA\Property(property: 'short_description', type: 'string', example: 'Short description'),
                    new OA\Property(property: 'description', type: 'string', example: '<p>Full description</p>'),
                    new OA\Property(property: 'status', type: 'string', example: 'active'),
                    new OA\Property(property: 'meta_title', type: 'string', example: 'iPhone 15 Pro'),
                    new OA\Property(property: 'meta_keywords', type: 'string', example: 'iphone, apple'),
                    new OA\Property(property: 'meta_description', type: 'string', example: 'Buy iPhone 15 Pro'),
                    new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2]),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
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
}
