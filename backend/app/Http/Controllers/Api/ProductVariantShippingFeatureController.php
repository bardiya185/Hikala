<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingFeatureRequest;
use App\Http\Requests\UpdateShippingFeatureRequest;
use App\Http\Resources\ShippingFeatureResource;
use App\Models\ProductVariant;
use App\Models\ProductVariantShippingFeature;
use App\Services\Shipping\ShippingFeatureService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductVariantShippingFeatureController extends Controller
{
    public function __construct(
        private readonly ShippingFeatureService $service
    ) {
    }


    #[OA\Get(
        path: "/api/variants/{variant}/shipping-features",
        summary: "Get variant shipping features",
        tags: ["Shipping Features"],
        parameters: [
            new OA\Parameter(
                name: "variant",
                description: "Product variant id",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Shipping features retrieved successfully"
            )
        ]
    )]
    public function index(ProductVariant $variant): JsonResponse
    {
        $features = $this->service->getByVariant($variant);

        return response()->json([
            'data' => ShippingFeatureResource::collection($features),
        ]);
    }


    #[OA\Post(
        path: "/api/variants/{variant}/shipping-features",
        summary: "Create shipping feature for variant",
        tags: ["Shipping Features"],
        parameters: [
            new OA\Parameter(
                name: "variant",
                description: "Product variant id",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Shipping feature created"
            )
        ]
    )]
    public function store(
        StoreShippingFeatureRequest $request,
        ProductVariant $variant
    ): JsonResponse {

        $feature = $this->service->create(
            $variant,
            $request->validated()
        );

        return response()->json([
            'message' => 'Created successfully.',
            'data' => new ShippingFeatureResource($feature),
        ], 201);
    }


    #[OA\Get(
        path: "/api/shipping-features/{feature}",
        summary: "Get shipping feature",
        tags: ["Shipping Features"],
        parameters: [
            new OA\Parameter(
                name: "feature",
                description: "Shipping feature id",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Shipping feature retrieved successfully"
            )
        ]
    )]
    public function show(
        ProductVariantShippingFeature $feature
    ): JsonResponse {

        return response()->json([
            'data' => new ShippingFeatureResource($feature),
        ]);
    }


    #[OA\Put(
        path: "/api/shipping-features/{feature}",
        summary: "Update shipping feature",
        tags: ["Shipping Features"],
        parameters: [
            new OA\Parameter(
                name: "feature",
                description: "Shipping feature id",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Shipping feature updated successfully"
            )
        ]
    )]
    public function update(
        UpdateShippingFeatureRequest $request,
        ProductVariantShippingFeature $feature
    ): JsonResponse {

        $feature = $this->service->update(
            $feature,
            $request->validated()
        );

        return response()->json([
            'message' => 'Updated successfully.',
            'data' => new ShippingFeatureResource($feature),
        ]);
    }


    #[OA\Delete(
        path: "/api/shipping-features/{feature}",
        summary: "Delete shipping feature",
        tags: ["Shipping Features"],
        parameters: [
            new OA\Parameter(
                name: "feature",
                description: "Shipping feature id",
                in: "path",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Shipping feature deleted successfully"
            )
        ]
    )]
    public function destroy(
        ProductVariantShippingFeature $feature
    ): JsonResponse {

        $this->service->delete($feature);

        return response()->json([
            'message' => 'Deleted successfully.',
        ]);
    }
}
