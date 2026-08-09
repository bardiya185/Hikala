<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Product Images",
    description: "Manage product images"
)]
class ProductImageController extends Controller
{
    public function __construct(
        private ProductImageService $service
    ) {}

    // ================================================================
    // 1. Get all images of a product
    // ================================================================
    #[OA\Get(
        path: "/api/products/{product}/images",
        tags: ["Product Images"],
        summary: "Get product images",
        description: "Get all images of a specific product",
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Images retrieved successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            )
        ]
    )]
    public function index(Product $product): JsonResponse
    {
        $images = $this->service->getImages($product);

        return response()->json([
            'success' => true,
            'data' => ProductImageResource::collection($images)
        ]);
    }

    // ================================================================
    // 2. Upload a new image
    // ================================================================
    #[OA\Post(
        path: "/api/products/{product}/images",
        tags: ["Product Images"],
        summary: "Upload product image",
        description: "Upload a new image for a product",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer", example: 1)
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
                description: "Image uploaded successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 404,
                description: "Product not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function store(StoreProductImageRequest $request, Product $product): JsonResponse
    {
        $image = $this->service->upload(
            $product,
            $request->file('image'),
            $request->input('alt')
        );

        if ($request->boolean('is_main')) {
            $this->service->setMain($image);
            $image->refresh();
        }

        return (new ProductImageResource($image))
            ->response()
            ->setStatusCode(201);
    }

    // ================================================================
    // 3. Delete an image
    // ================================================================
    #[OA\Delete(
        path: "/api/products/{product}/images/{image}",
        tags: ["Product Images"],
        summary: "Delete product image",
        description: "Delete a product image",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "image",
                in: "path",
                required: true,
                description: "Image ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Image deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Image not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function destroy(Product $product, ProductImage $image): JsonResponse
    {
        // Ensure image belongs to product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Image does not belong to this product'
            ], 404);
        }

        $this->service->delete($image);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }

    // ================================================================
    // 4. Set an image as main
    // ================================================================
    #[OA\Put(
        path: "/api/products/{product}/images/{image}/main",
        tags: ["Product Images"],
        summary: "Set main image",
        description: "Set an image as the main image for its product",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "image",
                in: "path",
                required: true,
                description: "Image ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Main image set successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Image not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function setMain(Product $product, ProductImage $image): JsonResponse
    {
        // Ensure image belongs to product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Image does not belong to this product'
            ], 404);
        }

        $this->service->setMain($image);
        $image->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Main image set successfully.',
            'data' => new ProductImageResource($image)
        ]);
    }

    // ================================================================
    // 5. Reorder images
    // ================================================================
    #[OA\Put(
        path: "/api/products/{product}/images/reorder",
        tags: ["Product Images"],
        summary: "Reorder images",
        description: "Reorder product images by sending an array of image IDs",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "product",
                in: "path",
                required: true,
                description: "Product ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
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
                description: "Images reordered successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function reorder(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:product_images,id',
        ]);

        // Ensure all images belong to the product
        $imageIds = ProductImage::where('product_id', $product->id)
            ->pluck('id')
            ->toArray();

        foreach ($request->order as $id) {
            if (!in_array($id, $imageIds)) {
                return response()->json([
                    'success' => false,
                    'message' => "Image ID {$id} does not belong to this product"
                ], 422);
            }
        }

        $this->service->reorder($request->order);

        return response()->json([
            'success' => true,
            'message' => 'Images reordered successfully.'
        ]);
    }
}