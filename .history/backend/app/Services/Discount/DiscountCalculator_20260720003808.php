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
        // اگر تخفیفی وجود نداشت
        if (!$discount) {
            return new DiscountResult(
                $basePrice,
                $discountAmount,
                $finalPrice,
                $finalPrice,
                $discount,
                $coupon
            );
        }

        // مقدار تخفیف
        $discountAmount = match ($discount->type) {
            'percent' => ($basePrice * $discount->value) / 100,
            'fixed'   => $discount->value,
            default   => 0,
        };

        // جلوگیری از منفی شدن قیمت
        $discountAmount = min($discountAmount, $basePrice);

        // قیمت نهایی
        $price = $basePrice - $discountAmount;

        return new DiscountResult(
            basePrice: $basePrice,
            discountAmount: $discountAmount,
            price: $price,
            discount: $discount,
            coupon: $coupon,
        );
    }
}