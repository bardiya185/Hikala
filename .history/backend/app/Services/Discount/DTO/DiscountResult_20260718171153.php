<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;

class DiscountResult
{
    public function __construct(

        public int|float $price,

        public int|float $discountAmount,

        public int|float $finalPrice,

        public ?Discount $discount = null,

    ) {
    }
}