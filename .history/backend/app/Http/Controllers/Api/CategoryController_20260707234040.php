<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use OpenApi\Attributes as OA;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    #[OA\Get(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "List Categories",
        description: "Get all root categories with their children.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Categories retrieved successfully."
            )
        ]
    )]
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return CategoryResource::collection($categories);
    }

    #[OA\Post(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "Create Category",
        description: "Create a new category.",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "slug"],
                properties: [
                    new OA\Property(property: "parent_id", type: "integer", example: 1, nullable: true),
                    new OA\Property(property: "name", type: "string", example: "موبایل"),
                    new OA\Property(property: "slug", type: "string", example: "mobile"),
                    new OA\Property(property: "icon_key", type: "string", example: "mobile", nullable: true),
                    new OA\Property(property: "image", type: "string", example: "categories/mobile.png", nullable: true),
                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                    new OA\Property(property: "is_active", type: "boolean", example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            ),
        ]
    )]
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: "/api/categories/{category}",
        tags: ["Categories"],
        summary: "Show Category",
        description: "Get category details.",
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category retrieved successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Category not found."
            ),
        ]
    )]
    public function show(Category $category)
    {
        $category->load('children');

        return new CategoryResource($category);
    }

    #[OA\Put(
        path: "/api/categories/{category}",
        tags: ["Categories"],
        summary: "Update Category",
        description: "Update category information.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "parent_id", type: "integer", example: 1, nullable: true),
                    new OA\Property(property: "name", type: "string", example: "موبایل"),
                    new OA\Property(property: "slug", type: "string", example: "mobile"),
                    new OA\Property(property: "icon_key", type: "string", example: "mobile"),
                    new OA\Property(property: "image", type: "string", example: "categories/mobile.png"),
                    new OA\Property(property: "sort_order", type: "integer", example: 2),
                    new OA\Property(property: "is_active", type: "boolean", example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Category not found."
            ),
            new OA\Response(
                response: 422,
                description: "Validation Error"
            ),
        ]
    )]
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    #[OA\Delete(
        path: "/api/categories/{category}",
        tags: ["Categories"],
        summary: "Delete Category",
        description: "Delete a category.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category deleted successfully."
            ),
            new OA\Response(
                response: 404,
                description: "Category not found."
            ),
        ]
    )]
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    #[OA\Get(
        path: "/api/categories/menu",
        tags: ["Categories"],
        summary: "Get Category Menu Tree",
        description: "Get categories tree for header menu (like Digikala).",
        responses: [
            new OA\Response(
                response: 200,
                description: "Categories menu retrieved successfully."
            )
        ]
    )]
    public function menu()
    {
        $categories = Category::with(['children' => function($query) {
            dd()
            $query->with(['children' => function($q) {
                $q->with('children')->orderBy('sort_order');
            }])->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->where('is_active', 1)
        ->orderBy('sort_order')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }


  
}