<?php

namespace App\Services\Discount;


class DiscountPriority
{

    public function weight(string $type): int
    {

        return match($type){

            'App\Models\ProductVariant' => 40,

            'App\Models\Product' => 30,

            'App\Models\Category' => 20,

            'App\Models\Brand' => 10,

            default => 0,

        };

    }

}