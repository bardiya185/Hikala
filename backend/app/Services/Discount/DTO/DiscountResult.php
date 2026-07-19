<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        public int|float $basePrice,

        public int|float $discountAmount,

        public int|float $price,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}