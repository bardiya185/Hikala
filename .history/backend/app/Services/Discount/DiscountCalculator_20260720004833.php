<?php

namespace App\Services\Discount;

use App\Models\Coupon;
use App\Models\Discount;
use App\Services\Discount\DTO\DiscountResult;

class DiscountCalculator
{
    public function calculate(
        float $basePrice,
        ?Discount $discount,
        ?Coupon $coupon = null
    ): DiscountResult
    {
        // بدون تخفیف
        if (!$discount) {

            return new DiscountResult(
                $basePrice, // قیمت اصلی
                0,          // مقدار تخفیف
                $basePrice, // price نهایی
                $basePrice, // finalPrice
                null,
                $coupon
            );
        }


        $discountAmount = 0;


        // تخفیف درصدی
        if ($discount->type === 'percent') {

            $discountAmount = ($basePrice * $discount->value) / 100;

        }


        // تخفیف مبلغ ثابت
        if ($discount->type === 'fixed') {

            $discountAmount = $discount->value;

        }


        // جلوگیری از منفی شدن قیمت
        $discountAmount = min(
            $discountAmount,
            $basePrice
        );


        $finalPrice = $basePrice - $discountAmount;


        return new DiscountResult(

            $basePrice,       // قیمت اصلی
            $discountAmount,   // مقدار تخفیف
            $finalPrice,       // price
            $finalPrice,       // finalPrice
            $discount,
            $coupon

        );
    }
}