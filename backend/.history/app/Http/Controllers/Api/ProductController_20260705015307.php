<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()

            ->with([

                'brand',

                'categories',

                'images',

                'variants.inventory',

            ])

            ->latest()

            ->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = DB::transaction(function () use ($request) {
    
            $product = Product::create(
    
                $request->safe()->except('categories')
    
            );
    
            $product->categories()->sync(
    
                $request->categories ?? []
    
            );
    
            return $product;
    
        });
    
        $product->load([
    
            'brand',
    
            'categories',
    
        ]);
    
        return (new ProductResource($product))
    
            ->response()
    
            ->setStatusCode(201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
