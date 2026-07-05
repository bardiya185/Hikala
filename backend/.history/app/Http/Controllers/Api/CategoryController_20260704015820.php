<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use 

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