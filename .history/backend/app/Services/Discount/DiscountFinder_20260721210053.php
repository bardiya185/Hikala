<?php

namespace App\Services\Discount;


use App\Models\ProductVariant;
use Illuminate\Support\Collection;


class DiscountFinder
{


    public function find(ProductVariant $variant): Collection
    {
        $discounts = collect();
    
        // ✅ از relation cache استفاده می‌کنیم
        $discounts = $discounts->merge(
            $variant->relationLoaded('discounts')
                ? $variant->discounts
                : $variant->discounts()->active()->get()
        );
    
        $product = $variant->relationLoaded('product')
            ? $variant->product
            : $variant->product()->first();
    
        if ($product) {
            // ✅ Product discounts
            $discounts = $discounts->merge(
                $product->relationLoaded('discounts')
                    ? $product->discounts
                    : $product->discounts()->active()->get()
            );
    
            // ✅ Category discounts
            $categories = $product->relationLoaded('categories')
                ? $product->categories
                : $product->categories()->get();
    
            foreach ($categories as $category) {
                $discounts = $discounts->merge(
                    $category->relationLoaded('discounts')
                        ? $category->discounts
                        : $category->discounts()->active()->get()
                );
            }
    
            // ✅ Brand discounts
            $brand = $product->relationLoaded('brand')
                ? $product->brand
                : $product->brand()->first();
    
            if ($brand) {
                $discounts = $discounts->merge(
                    $brand->relationLoaded('discounts')
                        ? $brand->discounts
                        : $brand->discounts()->active()->get()
                );
            }
        }
    
        $priority = new DiscountPriority();
    
        return $discounts
            ->unique('id')
            ->sortByDesc(fn($d) => $priority->calculate($d))
            ->values();
    }


}