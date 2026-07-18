<?php

namespace App\Services\Discount;


class DiscountPriority
{

    public function calculate($discount): int
    {

        return $this->sourceWeight(
            $discount->pivot->discountable_type
        )
        +
        $discount->priority;

    }



    private function sourceWeight(string $type): int
    {

        return match($type){

            'App\Models\ProductVariant' => 400,

            'App\Models\Product' => 300,

            'App\Models\Category' => 200,

            'App\Models\Brand' => 100,

            default => 0,

        };

    }

}