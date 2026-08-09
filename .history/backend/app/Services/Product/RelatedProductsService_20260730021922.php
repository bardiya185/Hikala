<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class RelatedProductsService
{
    
    public function find(Product $product, int $limit = 8): Collection
    {
        // یه Collection خالی شروع کن
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
            ->where('id', '!=', $product->id)           // خود محصول رو exclude کن
            ->where('brand_id', $product->brand_id)     // فقط هم‌برند
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);  // هم‌دسته
            })
            ->orderBy('view_count', 'desc')             // محبوب‌ترین اول
            ->limit($limit)
            ->get();
    }


    /**
     * 2️⃣ محصولات هم‌دسته (بدون توجه به برند)
     */
    private function getSameCategory(Product $product, int $limit, array $excludeIds = []): Collection
    {
        $categoryIds = $product->categories->pluck('id')->toArray();
        
        if (empty($categoryIds)) {
            return collect();
        }
        
        // ID محصول فعلی رو هم به exclude اضافه کن
        $excludeIds[] = $product->id;
        
        return Product::query()
            ->with(['brand', 'images', 'variants'])
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)             // exclude ها
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->orderBy('view_count', 'desc')
            ->limit($limit)
            ->get();
    }


    /**
     * 3️⃣ محصولات محبوب همون برند
     */
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