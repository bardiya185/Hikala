<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Services\Product\RelatedProductsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "Product management"
)]
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private RelatedProductsService $relatedProductsService
    ) {}
    #[OA\Get(
        path: '/api/products',
        operationId: 'products.index',
        tags: ['Products'],
        summary: 'List products with filters',
        description: 'Get products with filtering, search, sorting and pagination. Supports campaign filtering.',
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
                name: 'attribute_value_ids',
                in: 'query',
                description: 'Filter by attribute value IDs (comma-separated). Groups values by attribute type (OR logic within same attribute, AND logic across different attributes). Example: 10,11,25 (10,11=Red,Blue | 25=XL)',
                schema: new OA\Schema(type: 'string', example: '10,11,25')
            ),
            new OA\Parameter(
                name: 'attributes_id',
                in: 'query',
                description: 'Alias for attribute_value_ids (comma-separated attribute value IDs).',
                schema: new OA\Schema(type: 'string', example: '10,11,25')
            ),
            new OA\Parameter(
                name: 'campaign',
                in: 'query',
                description: 'Filter by campaign slug (flash-sale, special, weekly, clearance, ...)',
                schema: new OA\Schema(type: 'string', example: 'flash-sale')
            ),
            new OA\Parameter(
                name: 'min_discount',
                in: 'query',
                description: 'Minimum discount percentage',
                schema: new OA\Schema(type: 'integer', minimum: 0, maximum: 100, example: 20)
            ),
            new OA\Parameter(
                name: 'max_discount',
                in: 'query',
                description: 'Maximum discount percentage',
                schema: new OA\Schema(type: 'integer', minimum: 0, maximum: 100, example: 50)
            ),
            new OA\Parameter(
                name: 'has_discount',
                in: 'query',
                description: 'Only products with any discount',
                schema: new OA\Schema(type: 'boolean', example: true)
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                description: 'Sort field',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'created_at',
                    enum: ['base_price', 'view_count', 'created_at', 'title', 'sort_order']
                )
            ),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                description: 'Sort direction',
                schema: new OA\Schema(type: 'string', default: 'desc', enum: ['asc', 'desc'])
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Items per page (max 100)',
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
                description: 'Cursor for infinite scroll',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Products retrieved successfully')
        ]
    )]
    public function index(Request $request)
    {
        $result = $this->productService->list($request);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($result['data']),
            'meta' => $result['meta'],
        ]);
    }
    #[OA\Get(
        path: '/api/products/{product}',
        operationId: 'products.show',
        tags: ['Products'],
        summary: 'Show product details',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product retrieved successfully'),
            new OA\Response(response: 404, description: 'Product not found')
        ]
    )]
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404, 'Product not found');
        }

        $product->load(array_merge(
            $this->productService->getDefaultRelations(),
            ['variants.attributeValues.attribute']
        ));

        $product->loadCount('approvedReviews');
        $product->increment('view_count');

        return new ProductResource($product);
    }
    #[OA\Get(
        path: '/api/products/{product}/related',
        operationId: 'products.related',
        tags: ['Products'],
        summary: 'Get related products',
        description: 'Returns similar products based on category, brand and popularity',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                description: 'Maximum number of related products',
                schema: new OA\Schema(type: 'integer', default: 8, minimum: 1, maximum: 20)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Related products'),
            new OA\Response(response: 404, description: 'Product not found')
        ]
    )]
    public function related(Request $request, Product $product)
    {
        $product->load(['brand', 'categories']);
        $limit = min((int) $request->get('limit', 8), 20);

        $relatedProducts = $this->relatedProductsService->find($product, $limit);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($relatedProducts),
            'meta' => [
                'total' => $relatedProducts->count(),
                'source_product_id' => $product->id,
            ],
        ]);
    }
    #[OA\Post(
        path: '/api/products',
        operationId: 'products.store',
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
                    new OA\Property(property: 'status', type: 'string', enum: ['draft', 'active', 'inactive']),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'integer')),
                    new OA\Property(
                        property: 'variants',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'sku', type: 'string'),
                                new OA\Property(property: 'base_price', type: 'integer'),
                                new OA\Property(property: 'stock', type: 'integer'),
                                new OA\Property(property: 'attributes', type: 'array', items: new OA\Items(type: 'object')),
                            ]
                        )
                    ),
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
    #[OA\Put(
        path: '/api/products/{product}',
        operationId: 'products.update',
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
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'slug', type: 'string'),
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
    #[OA\Delete(
        path: '/api/products/{product}',
        operationId: 'products.destroy',
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