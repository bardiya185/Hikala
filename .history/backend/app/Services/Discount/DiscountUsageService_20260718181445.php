<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Models\User;
use App\Models\DiscountUsage;


class DiscountUsageService
{


    public function create(
        Discount $discount,
        ?User $user,
        int $quantity = 1
    )
    {

        return DiscountUsage::create([

            'discount_id'=>$discount->id,

            'user_id'=>$user?->id,

            'quantity'=>$quantity,

        ]);

    }


}