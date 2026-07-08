<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json([
            'success' => true,
            'data' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        $category->load('children');
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    // ================================================================
    // MENU TREE (منوی درختی) - ✅ این متد رو اصلاح کن
    // ================================================================
    public function menu()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $this->buildTree($categories)
        ]);
    }
    
    private function buildTree($categories)
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
                'is_active' => $category->is_active,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'children' => [],
            ];
    
            if ($children->isNotEmpty()) {
                $item['children'] = $this->buildTree($children);
            }
    
            $result[] = $item;
        }
        return $result;
    }
    {}
    // ================================================================
    // ساخت درخت بازگشتی
    // ================================================================
    private function buildTree($categories)
    {
        $result = [];
        foreach ($categories as $category) {
            // گرفتن فرزندان این دسته
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
                'is_active' => $category->is_active,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'children' => [],
            ];

            // اگر فرزند داشت، بازگشتی برو
            if ($children->isNotEmpty()) {
                $item['children'] = $this->buildTree($children);
            }

            $result[] = $item;
        }
        return $result;
    }

    public function all()
    {
        $categories = Category::query()
            ->withCount('products')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function withProducts()
    {
        $categories = Category::query()
            ->with(['products' => function($query) {
                $query->where('is_active', 1)->latest()->limit(10);
            }])
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function products(Category $category)
    {
        $products = $category->products()
            ->with(['brand', 'variants', 'images'])
            ->where('is_active', 1)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}