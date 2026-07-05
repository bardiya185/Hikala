<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use OpenApi\Attributes as OA 

class BrandController extends Controller
{
    #[OA\Get(
        path: "/api/brands",
        tags: ["Brands"],
        summary: "List Brands",
        description: "Get all brands.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Brands retrieved successfully."
            )
        ]
    )]    public function index()
    {
        return BrandResource::collection(
            Brand::orderBy('sort_order')->get()
        );
    }

    #[OA\Post(
        path: "/api/brands",
        tags: ["Brands"],
        summary: "Create Brand",
        description: "Create a new brand.",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "slug"],
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Apple"
                    ),
                    new OA\Property(
                        property: "slug",
                        type: "string",
                        example: "apple"
                    ),
                    new OA\Property(
                        property: "logo",
                        type: "string",
                        example: "brands/apple.png",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        example: "Apple official brand",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "sort_order",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "is_active",
                        type: "boolean",
                        example: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Brand created successfully."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            ),
        ]
    )]
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Show Brand",
        description: "Get brand details.",
        parameters: [
            new OA\Parameter(
                name: "brand",
                in: "path",
                required: true,
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Brand retrieved successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Brand not found."
            ),
        ]
    )]

    
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    #[OA\Put(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Update Brand",
        description: "Update brand information.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "brand",
                in: "path",
                required: true,
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Apple"
                    ),
                    new OA\Property(
                        property: "slug",
                        type: "string",
                        example: "apple"
                    ),
                    new OA\Property(
                        property: "logo",
                        type: "string",
                        example: "brands/apple.png"
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        example: "Apple official brand"
                    ),
                    new OA\Property(
                        property: "sort_order",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "is_active",
                        type: "boolean",
                        example: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Brand updated successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Brand not found."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            ),
        ]
    )]

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return new BrandResource($brand);
    }

    #[OA\Delete(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Delete Brand",
        description: "Delete a brand.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "brand",
                in: "path",
                required: true,
                description: "Brand ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Brand deleted successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Brand not found."
            ),
        ]
    )]
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully.',
        ]);
    }
}