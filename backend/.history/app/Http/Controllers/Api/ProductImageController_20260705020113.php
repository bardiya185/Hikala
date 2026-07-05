<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product
use App\Http\Requests\StoreProductImageRequest;

class ProductImageController extends Controller
{
    public function store(
        StoreProductImageRequest $request,
        Product $product
    )
    {
        $path = $request
            ->file('image')
            ->store('products', 'public');
    
        $image = $product->images()->create([
    
            'image_path' => $path,
    
            'alt' => $request->alt,
    
            'sort_order' =>
    
                $product->images()->count() + 1,
    
            'is_main' =>
    
                $product->images()->doesntExist(),
    
        ]);
    
        return new ProductImageResource($image);
    }
}
