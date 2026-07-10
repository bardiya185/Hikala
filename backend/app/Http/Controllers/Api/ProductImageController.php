<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImage\ProductImageService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductImageController extends Controller
{
    public function __construct(
        private ProductImageService $service
    ) {}

    // ================================================================
    // 1. دریافت همه تصاویر یک محصول
    // ================================================================
    #[OA\Get(
        path: "/api/products/{product}/images",
        tags: ["Product Images"],
        summary: "Get product images",
        description: "Get all images of a specific product.",
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Images retrieved successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Product not found."
            )
        ]
    )]
    public function index(Product $product)
    {
        $images = $product->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return ProductImageResource::collection($images);
    }

    // ================================================================
    // 2. آپلود عکس جدید
    // ================================================================
    #[OA\Post(
        path: "/api/products/{product}/images",
        tags: ["Product Images"],
        summary: "Upload product image",
        description: "Upload a new image for a product.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["image"],
                    properties: [
                        new OA\Property(
                            property: "image",
                            type: "string",
                            format: "binary",
                            description: "Image file (jpeg, png, jpg, gif, webp - max 5MB)"
                        ),
                        new OA\Property(
                            property: "is_main",
                            type: "boolean",
                            example: true,
                            description: "Set as main image"
                        ),
                        new OA\Property(
                            property: "alt",
                            type: "string",
                            example: "iPhone 15 Pro",
                            description: "Alt text for image"
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Image uploaded successfully."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            ),
            new OA\Response(
                response: 404,
                description: "Product not found."
            )
        ]
    )]
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $image = $this->service->upload(
            $product,
            $request->file('image'),
            $request->input('alt')
        );

        // اگر این عکس اصلی است
        if ($request->input('is_main', false)) {
            $this->service->setMain($image);
        }

        return (new ProductImageResource($image))
            ->response()
            ->setStatusCode(201);
    }

    // ================================================================
    // 3. حذف عکس
    // ================================================================
    #[OA\Delete(
        path: "/api/product-images/{image}",
        tags: ["Product Images"],
        summary: "Delete product image",
        description: "Delete a product image.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "image",
                in: "path",
                required: true,
                description: "Image ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Image deleted successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Image not found."
            )
        ]
    )]
    public function destroy(ProductImage $image)
    {
        $this->service->delete($image);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }

    // ================================================================
    // 4. تنظیم عکس به عنوان اصلی
    // ================================================================
    #[OA\Put(
        path: "/api/product-images/{image}/main",
        tags: ["Product Images"],
        summary: "Set main image",
        description: "Set an image as the main image for its product.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "image",
                in: "path",
                required: true,
                description: "Image ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Main image set successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Image not found."
            )
        ]
    )]
    public function setMain(ProductImage $image)
    {
        $this->service->setMain($image);

        return response()->json([
            'success' => true,
            'message' => 'Main image set successfully.',
            'data' => new ProductImageResource($image)
        ]);
    }

    // ================================================================
    // 5. مرتب‌سازی عکس‌ها
    // ================================================================
    #[OA\Put(
        path: "/api/product-images/reorder",
        tags: ["Product Images"],
        summary: "Reorder images",
        description: "Reorder product images by sending an array of image IDs.",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["order"],
                properties: [
                    new OA\Property(
                        property: "order",
                        type: "array",
                        description: "Array of image IDs in desired order",
                        items: new OA\Items(type: "integer"),
                        example: [3, 1, 2]
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Images reordered successfully."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            )
        ]
    )]
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:product_images,id',
        ]);

        $this->service->reorder($request->order);

        return response()->json([
            'success' => true,
            'message' => 'Images reordered successfully.'
        ]);
    }
}