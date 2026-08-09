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

    if (!$discount) {

        return new DiscountResult(
            $basePrice,
            0,
            $basePrice,
            $basePrice,
            null,
            $coupon
        );
    }


    $discountAmount = 0;


    if ($discount->type === 'percent') {

        $discountAmount = ($basePrice * $discount->value) / 100;

    }


    if ($discount->type === 'fixed') {

        $discountAmount = $discount->value;

    }


    $discountAmount = min(
        $discountAmount,
        $basePrice
    );


    $finalPrice = $basePrice - $discountAmount;


    new DiscountResult(
        $basePrice,
        $discountAmount,
        $finalPrice,
        $discount,
        $coupon
    );
}
}