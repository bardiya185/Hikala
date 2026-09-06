<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Collection; 

class RelatedProductsService
{
    
    public function find(Product $product, int $limit = 8): Collection
    {
        $relatedProducts = collect();
        
        
        $sameCategoryAndBrand = $this->getSameCategoryAndBrand($product, $limit);
        $relatedProducts = $relatedProducts->merge($sameCategoryAndBrand);
        
 
        if ($relatedProducts->count() < $limit) {
            
            $remaining = $limit - $relatedProducts->count();
            $excludeIds = $relatedProducts->pluck('id')->toArray();
            
            $sameCategory = $this->getSameCategory(
                $product, 
                $remaining, 
                $excludeIds
            );
            
            $relatedProducts = $relatedProducts->merge($sameCategory);
        }

        if ($relatedProducts->count() < $limit) {
            
            $remaining = $limit - $relatedProducts->count();
            $excludeIds = $relatedProducts->pluck('id')->toArray();
            
            $popularFromBrand = $this->getPopularFromBrand(
                $product, 
                $remaining, 
                $excludeIds
            );
            
            $relatedProducts = $relatedProducts->merge($popularFromBrand);
        }
        
  
        return $relatedProducts->take($limit)->values();
    }


    private function getSameCategoryAndBrand(Product $product, int $limit): Collection
    {
 
        if (!$product->brand_id) {
            return collect();
        }

        $categoryIds = $product->categories->pluck('id')->toArray();

        if (empty($categoryIds)) {
            return collect();
        }
        
        return Product::query()
            ->with(['brand', 'images', 'variants'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)        
            ->where('brand_id', $product->brand_id)  
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);  
            })
            ->orderBy('view_count', 'desc')            
            ->limit($limit)
            ->get();
    }


    private function getSameCategory(Product $product, int $limit, array $excludeIds = []): Collection
    {
        $categoryIds = $product->categories->pluck('id')->toArray();
        
        if (empty($categoryIds)) {
            return collect();
        }

        $excludeIds[] = $product->id;
        
        return Product::query()
            ->with(['brand', 'images', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)            
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }



    private function getPopularFromBrand(Product $product, int $limit, array $excludeIds = []): Collection
    {
        if (!$product->brand_id) {
            return collect();
        }
        
        $excludeIds[] = $product->id;
        
        return Product::query()
            ->with(['brand', 'images', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)
            ->where('brand_id', $product->brand_id)
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }
}