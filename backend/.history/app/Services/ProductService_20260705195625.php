<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductService
{
    public function create(array $item): Product
    {
        $brand = Brand::where('slug', $item['brand'])->firstOrFail();

        $category = Category::where('name', $item['category'])->firstOrFail();

        
        $product = Product::updateOrCreate(
            
            [
                'slug' => $item['slug'],
            ],
            
            [
                'brand_id' => $brand->id,
                
                'title' => $item['title'],
                
                'short_description' => $item['short_description'],
                
                'description' => $item['description'],
                
                'status' => 'active',
                
                'meta_title' => $item['title'],
                
                'meta_keywords' => $item['title'],
                
                'meta_description' => $item['short_description'],
                
                'view_count' => 0,
                
                'sort_order' => 0,
                
                'is_active' => true,
                ]
                
            );
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $product->slug . '-default',
                'price' => 0,
                'stock' => 10,
            ]);
            
            $product->categories()->syncWithoutDetaching([
                $category->id,
            ]);
            
            (new ProductVariantService())->create($product, $item['attributes']);
            
        return $product;
    }
}