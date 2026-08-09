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
        summary: "Get all categories",
        description: "Retrieve all root categories with their nested children structure. Use this for displaying full category tree.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Categories retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "parent_id", type: "integer", nullable: true, example: null),
                                    new OA\Property(property: "name", type: "string", example: "Mobile"),
                                    new OA\Property(property: "slug", type: "string", example: "mobile"),
                                    new OA\Property(property: "icon_key", type: "string", nullable: true, example: "mobile"),
                                    new OA\Property(property: "banner", type: "string", nullable: true, example: null),
                                    new OA\Property(property: "description", type: "string", nullable: true, example: null),
                                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T10:00:00.000000Z"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T10:00:00.000000Z"),
                                    new OA\Property(
                                        property: "children",
                                        type: "array",
                                        description: "Nested subcategories",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "id", type: "integer", example: 2),
                                                new OA\Property(property: "name", type: "string", example: "Select Mobile"),
                                                new OA\Property(property: "slug", type: "string", example: "select-mobile"),
                                                new OA\Property(
                                                    property: "children",
                                                    type: "array",
                                                    items: new OA\Items(
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 3),
                                                            new OA\Property(property: "name", type: "string", example: "Apple Phones"),
                                                            new OA\Property(property: "slug", type: "string", example: "apple-phones"),
                                                            new OA\Property(
                                                                property: "children",
                                                                type: "array",
                                                                items: new OA\Items(
                                                                    properties: [
                                                                        new OA\Property(property: "id", type: "integer", example: 4),
                                                                        new OA\Property(property: "name", type: "string", example: "iPhone 16"),
                                                                        new OA\Property(property: "slug", type: "string", example: "iphone-16"),
                                                                    ]
                                                                )
                                                            )
                                                        ]
                                                    )
                                                )
                                            ]
                                        )
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->where('is_active' , '!=', false ) ->get();

        return CategoryResource::collection($categories);
    }

    #[OA\Post(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "Create a new category",
        description: "Create a new category. You can set parent_id to create subcategories.",
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "slug"],
                properties: [
                    new OA\Property(
                        property: "parent_id",
                        type: "integer",
                        nullable: true,
                        example: null,
                        description: "Parent category ID. Leave null for root category."
                    ),
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Mobile",
                        description: "Category display name"
                    ),
                    new OA\Property(
                        property: "slug",
                        type: "string",
                        example: "mobile",
                        description: "URL friendly unique slug"
                    ),
                    new OA\Property(
                        property: "icon_key",
                        type: "string",
                        nullable: true,
                        example: "mobile",
                        description: "Icon identifier (e.g., fontawesome class)"
                    ),
                    new OA\Property(
                        property: "banner",
                        type: "string",
                        nullable: true,
                        example: "categories/mobile-banner.jpg",
                        description: "Banner image path"
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true,
                        example: "Mobile phones and accessories",
                        description: "Category description"
                    ),
                    new OA\Property(
                        property: "sort_order",
                        type: "integer",
                        example: 1,
                        description: "Display order (lower numbers appear first)"
                    ),
                    new OA\Property(
                        property: "is_active",
                        type: "boolean",
                        example: true,
                        description: "Whether the category is visible"
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "parent_id", type: "integer", nullable: true, example: null),
                                new OA\Property(property: "name", type: "string", example: "Mobile"),
                                new OA\Property(property: "slug", type: "string", example: "mobile"),
                                new OA\Property(property: "icon_key", type: "string", nullable: true, example: "mobile"),
                                new OA\Property(property: "banner", type: "string", nullable: true),
                                new OA\Property(property: "description", type: "string", nullable: true),
                                new OA\Property(property: "sort_order", type: "integer", example: 1),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
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
                        new OA\Property(property: "errors", type: "object", example: [
                            "name" => ["The name field is required."],
                            "slug" => ["The slug has already been taken."]
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - Admin access required"
            )
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
        summary: "Get a specific category",
        description: "Get detailed information about a single category with its children.",
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
                description: "Category retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "parent_id", type: "integer", nullable: true),
                                new OA\Property(property: "name", type: "string", example: "Mobile"),
                                new OA\Property(property: "slug", type: "string", example: "mobile"),
                                new OA\Property(property: "icon_key", type: "string", nullable: true, example: "mobile"),
                                new OA\Property(property: "banner", type: "string", nullable: true),
                                new OA\Property(property: "description", type: "string", nullable: true),
                                new OA\Property(property: "sort_order", type: "integer", example: 1),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                new OA\Property(
                                    property: "children",
                                    type: "array",
                                    description: "Direct subcategories",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 2),
                                            new OA\Property(property: "name", type: "string", example: "Select Mobile"),
                                            new OA\Property(property: "slug", type: "string", example: "select-mobile"),
                                            new OA\Property(
                                                property: "children",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 3),
                                                        new OA\Property(property: "name", type: "string", example: "Apple Phones"),
                                                        new OA\Property(property: "slug", type: "string", example: "apple-phones"),
                                                    ]
                                                )
                                            )
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            )
        ]
    )]
    public function show(Category $category)
    {

        if(Category::where('is_active' , '=' , true)){

            $category->load('children');   
            return new CategoryResource($category);
        }
    }

    #[OA\Put(
        path: "/api/categories/{category}",
        tags: ["Categories"],
        summary: "Update a category",
        description: "Update category information. All fields are optional.",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID to update",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "parent_id",
                        type: "integer",
                        nullable: true,
                        example: null,
                        description: "Change parent category. Null for root."
                    ),
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "Mobile & Tablets",
                        description: "New category name"
                    ),
                    new OA\Property(
                        property: "slug",
                        type: "string",
                        example: "mobile-tablets",
                        description: "New URL friendly slug"
                    ),
                    new OA\Property(
                        property: "icon_key",
                        type: "string",
                        nullable: true,
                        example: "mobile",
                        description: "Icon identifier"
                    ),
                    new OA\Property(
                        property: "banner",
                        type: "string",
                        nullable: true,
                        example: "categories/mobile-banner.jpg",
                        description: "Banner image path"
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true,
                        example: "Mobile phones and tablets",
                        description: "Category description"
                    ),
                    new OA\Property(
                        property: "sort_order",
                        type: "integer",
                        example: 1,
                        description: "Display order"
                    ),
                    new OA\Property(
                        property: "is_active",
                        type: "boolean",
                        example: true,
                        description: "Active status"
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Mobile & Tablets"),
                                new OA\Property(property: "slug", type: "string", example: "mobile-tablets"),
                                new OA\Property(property: "sort_order", type: "integer", example: 1),
                                new OA\Property(property: "is_active", type: "boolean", example: true),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - Admin access required"
            )
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
        summary: "Delete a category",
        description: "Delete a category. Also deletes all subcategories (cascade).",
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID to delete",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Category deleted successfully")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - Admin access required"
            )
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
        summary: "Get category menu for header",
        description: "Get categories tree for use in header/footer navigation menus. Returns only active categories sorted by order.\n\nExample structure:\n- Mobile\n  - Select Mobile\n    - Apple Phones\n      - iPhone 16\n      - iPhone 16 Pro\n    - Samsung Phones\n      - Galaxy S24 Ultra\n      - Galaxy S24 Plus\n    - Xiaomi Phones\n      - Xiaomi 14 Ultra\n      - Xiaomi 14 Pro",
        responses: [
            new OA\Response(
                response: 200,
                description: "Menu retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Category tree for menu",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "parent_id", type: "integer", nullable: true, example: null),
                                    new OA\Property(property: "name", type: "string", example: "Mobile"),
                                    new OA\Property(property: "slug", type: "string", example: "mobile"),
                                    new OA\Property(property: "icon_key", type: "string", nullable: true, example: "mobile"),
                                    new OA\Property(property: "banner", type: "string", nullable: true),
                                    new OA\Property(property: "description", type: "string", nullable: true),
                                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                    new OA\Property(
                                        property: "children",
                                        type: "array",
                                        description: "Nested subcategories",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "id", type: "integer", example: 2),
                                                new OA\Property(property: "name", type: "string", example: "Select Mobile"),
                                                new OA\Property(property: "slug", type: "string", example: "select-mobile"),
                                                new OA\Property(
                                                    property: "children",
                                                    type: "array",
                                                    items: new OA\Items(
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 3),
                                                            new OA\Property(property: "name", type: "string", example: "Apple Phones"),
                                                            new OA\Property(property: "slug", type: "string", example: "apple-phones"),
                                                            new OA\Property(
                                                                property: "children",
                                                                type: "array",
                                                                items: new OA\Items(
                                                                    properties: [
                                                                        new OA\Property(property: "id", type: "integer", example: 4),
                                                                        new OA\Property(property: "name", type: "string", example: "iPhone 16"),
                                                                        new OA\Property(property: "slug", type: "string", example: "iphone-16"),
                                                                    ]
                                                                )
                                                            )
                                                        ]
                                                    )
                                                )
                                            ]
                                        )
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function menu()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $this->formatMenu($categories)
        ]);
    }
    
    /**
     * Recursively format categories for menu structure
     * 
     * @param \Illuminate\Support\Collection $categories
     * @return array
     */
    private function formatMenu($categories)
    {
        $result = [];
        foreach ($categories as $category) {
            $children = Category::where('parent_id', $category->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get();
    
            $item = [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon_key' => $category->icon_key,
                'banner' => $category->banner,
                'description' => $category->description,
                'sort_order' => $category->sort_order,
                'is_active' => (bool) $category->is_active,
                'created_at' => $category->created_at?->toISOString(),
                'updated_at' => $category->updated_at?->toISOString(),
                'children' => [],
            ];
    
            if ($children->isNotEmpty()) {
                $item['children'] = $this->formatMenu($children);
            }
    
            $result[] = $item;
        }
    
        return $result;
    }
}