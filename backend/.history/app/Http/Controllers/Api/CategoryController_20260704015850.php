<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;


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

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $category->load('children');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}