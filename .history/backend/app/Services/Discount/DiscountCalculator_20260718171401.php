<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Services\Discount\DTO\DiscountResult;

class DiscountCalculator
{


    public function calculate(
        float $price,
        ?Discount $discount
    ): DiscountResult
    {


        if (!$discount) {

            return new DiscountResult(
                $price,
                0,
                $price,
                null
            );

        }


        $amount = 0;


        if ($discount->type === 'percent') {

            $amount = ($price * $discount->value) / 100;

        }



        if ($discount->type === 'fixed') {

            $amount = $discount->value;

        }


 

        $amount = min(
            $amount,
            $price
        );



        return new DiscountResult(

            $price,

            $amount,

            $price - $amount,

            $discount

        );

    }


}