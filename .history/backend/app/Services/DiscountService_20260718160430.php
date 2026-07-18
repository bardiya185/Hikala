<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\Discount;
use Carbon\Carbon;


class DiscountService
{
    public function calculate(ProductVariant $variant): array
    {
        $discount = $this->getActiveDiscount($variant);
    
        if (!$discount) {
    
            return [
    
                'price' => $variant->price,
    
                'discount_amount' => 0,
    
                'final_price' => $variant->price,
    
            ];
        }
    
        return [
    
            'price' => $variant->price,
    
            'discount' => $discount,
    
        ];
    }
    private function getActiveDiscount(ProductVariant $variant): ?Discount
{
    return $variant->discounts()

        ->where('is_active', true)

        ->where(function ($query) {
            $query->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', Carbon::now());
        })

        ->where(function ($query) {
            $query->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', Carbon::now());
        })

        ->orderByDesc('priority')

        ->first();
}
}