<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Models\User;
use App\Models\DiscountUsage;
use Illuminate\Support\Facades\DB;


class DiscountUsageService
{

    public function consume(
        Discount $discount,
        ?User $user,
        int $quantity = 1
    )
    {

        return DB::transaction(function () use (
            $discount,
            $user,
            $quantity
        ) {
            $discount = Discount::lockForUpdate()
                ->find($discount->id);
            if (
                $discount->quantity_limit &&
                ($discount->used_quantity + $quantity)
                > $discount->quantity_limit
            ) {

                throw new \Exception(
                    'Discount limit reached'
                );

            }

            $usage = DiscountUsage::create([

                'discount_id' => $discount->id,

                'user_id' => $user?->id,

                'quantity' => $quantity,

            ]);

            $discount->increment(
                'used_quantity',
                $quantity
            );



            return $usage;


        });

    }

}