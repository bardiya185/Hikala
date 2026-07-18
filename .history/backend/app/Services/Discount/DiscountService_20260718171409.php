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
    
        $price = $variant->price;
    
        if (!$discount) {
    
            return [
                'price' => $price,
                'discount_amount' => 0,
                'final_price' => $price,
                'discount' => null,
            ];
        }
    
        $discountAmount = 0;
    
        switch ($discount->type) {
    
            case 'percent':
    
                $discountAmount = ($price * $discount->value) / 100;
    
                break;
    
            case 'fixed':
    
                $discountAmount = $discount->value;
    
                break;
        }
    
      پپ
        $discountAmount = min($discountAmount, $price);
    
        return [
    
            'price' => $price,
    
            'discount_amount' => $discountAmount,
    
            'final_price' => $price - $discountAmount,
    
            'discount' => $discount,
        ];
    }    private function getActiveDiscount(ProductVariant $variant): ?Discount
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