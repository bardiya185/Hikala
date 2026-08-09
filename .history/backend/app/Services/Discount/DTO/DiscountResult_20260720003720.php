<?php

namespace App\Services\Discount\DTO;

use App\Models\Discount;
use App\Models\Coupon;

class DiscountResult
{
    public function __construct(

        // قیمت اصلی قبل از تخفیف
        public int|float $basePrice,

        // مقدار تخفیف (مثلا 200000)
        public int|float $discountAmount,

        // قیمت نهایی بعد از تخفیف
        public int|float $price,

        // برای سازگاری و خوانایی
        public int|float $finalPrice,

        public ?Discount $discount = null,

        public ?Coupon $coupon = null,

    ) {}
}