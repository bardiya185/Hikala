<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Category;
use OpenApi\Attributes as OA; 
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    #[OA\Get(
        path: "/api/brands",
        tags: ["Brands"],
        summary: "List brands with category filter",
        description: "Get all brands, optionally filtered by category slug",
        parameters: [
            new OA\Parameter(
                name: "q",
                in: "query",
                description: "Search by brand name",
                schema: new OA\Schema(type: "string", example: "apple")
            ),
            new OA\Parameter(
                name: "category",
                in: "query",
                description: "Filter brands by category slug (e.g., mobile, apple-phones, laptops, fashion)",
                schema: new OA\Schema(type: "string", example: "mobile")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Brands retrieved successfully"
            )
        ]
    )]
    public function index(Request $request)
    {
        $search = $request->input('q');
        $categorySlug = $request->input('category');
      
        $query = Brand::where('is_active', true);

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                $query->whereHas('categories', function($q) use ($category) {
                    $q->where('category_id', $category->id);
                });
            } else {
                return response()->json([
                    'data' => [],
                    'message' => 'Category not found: ' . $categorySlug
                ], 200);
            }
        }

        $brands = $query->orderBy('sort_order')
                        ->orderBy('name')
                        ->get();

        return BrandResource::collection($brands);
    }
    
    #[OA\Post(
        path: "/api/brands",
        tags: ["Brands"],
        summary: "Create a new brand",
        description: "Create a new brand in the system",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "slug"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Apple", description: "Brand name"),
                    new OA\Property(property: "slug", type: "string", example: "apple", description: "URL friendly name"),
                    new OA\Property(property: "logo", type: "string", example: "brands/apple.png", nullable: true, description: "Logo path"),
                    new OA\Property(property: "description", type: "string", example: "Apple official brand", nullable: true, description: "Brand description"),
                    new OA\Property(property: "sort_order", type: "integer", example: 1, description: "Display order"),
                    new OA\Property(property: "is_active", type: "boolean", example: true, description: "Active status"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201, 
                description: "Brand created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Apple"),
                                new OA\Property(property: "slug", type: "string", example: "apple"),
                                new OA\Property(property: "logo", type: "string", example: "brands/apple.png"),
                                new OA\Property(property: "description", type: "string", example: "Apple official brand"),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                                new OA\Property(property: "sort_order", type: "integer", example: 1),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422, 
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The name field is required."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401, 
                description: "Unauthenticated"
            )
        ]
    )]
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Get brand details",
        description: "Get detailed information about a specific brand",
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
                description: "Brand retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Apple"),
                                new OA\Property(property: "slug", type: "string", example: "apple"),
                                new OA\Property(property: "logo", type: "string", example: "brands/apple.png"),
                                new OA\Property(property: "description", type: "string", example: "American technology company"),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                                new OA\Property(property: "sort_order", type: "integer", example: 1),
                                new OA\Property(property: "products_count", type: "integer", example: 25),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404, 
                description: "Brand not found"
            )
        ]
    )]
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    #[OA\Put(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Update brand",
        description: "Update brand information",
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
                    new OA\Property(property: "name", type: "string", example: "Apple Inc."),
                    new OA\Property(property: "slug", type: "string", example: "apple-inc"),
                    new OA\Property(property: "logo", type: "string", example: "brands/apple-new.png"),
                    new OA\Property(property: "description", type: "string", example: "Apple official brand updated"),
                    new OA\Property(property: "sort_order", type: "integer", example: 2),
                    new OA\Property(property: "is_active", type: "boolean", example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "Brand updated successfully"
            ),
            new OA\Response(
                response: 404, 
                description: "Brand not found"
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
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return new BrandResource($brand);
    }

    #[OA\Delete(
        path: "/api/brands/{brand}",
        tags: ["Brands"],
        summary: "Delete brand",
        description: "Delete a brand from the system (only if it has no products)",
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
                description: "Brand deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Brand deleted successfully.")
                    ]
                )
            ),
            new OA\Response(
                response: 400, 
                description: "Cannot delete brand with products",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Cannot delete this brand because it has associated products.")
                    ]
                )
            ),
            new OA\Response(
                response: 404, 
                description: "Brand not found"
            ),
            new OA\Response(
                response: 401, 
                description: "Unauthenticated"
            )
        ]
    )]
    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this brand because it has associated products.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.',
        ]);
    }
}