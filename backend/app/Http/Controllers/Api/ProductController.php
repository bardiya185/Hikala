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
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    // آرایه پیش‌فرض روابط برای جلوگیری از تکرار کد در متدهای نمایش تکی یا لیست‌های خاص
    private array $defaultRelations = [
        'brand',
        'categories',
        'images',
        'variants'
    ];

    public function __construct(
        private ProductService $productService
    ) {}

    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'List Products',
        description: 'Get all products with pagination.',
        responses: [
            new OA\Response(response: 200, description: 'Products retrieved successfully.')
        ]
    )]
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $products = Product::query()
            ->with($this->defaultRelations) // لود بهینه روابط سطحی برای لیست
            ->latest()
            ->paginate($perPage > 50 ? 50 : $perPage);

        return ProductResource::collection($products);
    }

    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Create Product',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['brand_id', 'title', 'slug'])
        ),
        responses: [
            new OA\Response(response: 201, description: 'Product created successfully.'),
            new OA\Response(response: 422, description: 'Validation Error')
        ]
    )]
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Show Product',
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product retrieved successfully.'),
            new OA\Response(response: 404, description: 'Product not found.')
        ]
    )]
    public function show(Product $product)
    {
        // لود کامل و عمیق روابط ویژگی‌ها فقط و فقط در صفحه جزئیات محصول
        $product->load(array_merge($this->defaultRelations, [
            'variants.attributeValues.attribute'
        ]));

        return new ProductResource($product);
    }

    #[OA\Put(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Update Product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product updated successfully.'),
            new OA\Response(response: 422, description: 'Validation Error')
        ]
    )]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update($product, $request->validated());

        return new ProductResource($product);
    }

    #[OA\Delete(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Delete Product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Product deleted successfully.')
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

    #[OA\Get(
        path: "/api/products/latest",
        tags: ["Products"],
        summary: "Get latest products",
        parameters: [
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Latest products retrieved successfully.")
        ]
    )]
    public function latest(Request $request)
    {
        $limit = min($request->get('limit', 10), 50);

        $products = Product::query()
            ->with($this->defaultRelations)
            ->where('is_active', true)
            ->latest()
            ->limit($limit)
            ->get();

        return ProductResource::collection($products);
    }

    #[OA\Get(
        path: "/api/products/featured",
        tags: ["Products"],
        summary: "Get featured products",
        parameters: [
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 10))
        ],
        responses: [
            new OA\Response(response: 200, description: "Featured products retrieved successfully.")
        ]
    )]
    public function featured(Request $request)
    {
        $limit = min($request->get('limit', 10), 50);

        $products = Product::query()
            ->with($this->defaultRelations)
            ->where('is_active', true)
            ->orderByDesc('view_count')
            // ترجیحاً هاردکد نشود و تبدیل به یک Scope در مدل Product شود: scopeFeatured()
            ->limit($limit)
            ->get();

        return ProductResource::collection($products);
    }

    #[OA\Get(
        path: "/api/products/category/{categoryId}",
        tags: ["Products"],
        summary: "Get Products by Category",
        parameters: [
            new OA\Parameter(name: "categoryId", in: "path", required: true, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "per_page", in: "query", schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Category products retrieved successfully."),
            new OA\Response(response: 404, description: "Category not found.")
        ]
    )]
    public function getByCategory(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'دسته‌بندی مورد نظر یافت نشد'
            ], Response::HTTP_NOT_FOUND);
        }

        $perPage = min($request->get('per_page', 15), 50);

        // حل باگ شماره ۲: دریافت شناسه خود دسته + تمام شناسه‌های زیرمجموعه آن به صورت درختی
        // فرض بر این است متد getTypeIds یا شبیه به آن در مدل دسته‌بندی شما پیاده شده است
        $categoryIds = method_exists($category, 'allSubCategoryIds') 
            ? array_merge([$category->id], $category->allSubCategoryIds()) 
            : [$category->id];

        $products = Product::query()
            ->whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds); // استفاده از whereIn به جای where
            })
            ->with($this->defaultRelations)
            ->where('is_active', true)
            ->latest()
            ->paginate($perPage);

        return ProductResource::collection($products);
    }
}