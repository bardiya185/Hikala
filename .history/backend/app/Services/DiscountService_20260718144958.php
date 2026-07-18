<?php

namespace App\Services;

use App\Models\ProductVariant;

class DiscountService
{
    public function calculate(ProductVariant $variant): array
    {
        return [

            'price' => $variant->price,

            'discount_amount' => 0,

            'final_price' => $variant->price,

        ];
    }
}