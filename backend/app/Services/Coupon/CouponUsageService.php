<?php

namespace App\Services\Coupon;


use App\Models\Coupon;
use App\Models\User;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;


class CouponUsageService
{


    public function consume(
        Coupon $coupon,
        ?User $user,
        int $quantity = 1
    )
    {


        return DB::transaction(function() use(
            $coupon,
            $user,
            $quantity
        ){


            $coupon = Coupon::lockForUpdate()
                ->find($coupon->id);



            if(
                $coupon->usage_limit &&
                (
                    $coupon->used_count + $quantity
                ) > $coupon->usage_limit
            ){

                throw new \Exception(
                    'Coupon limit reached'
                );

            }



            $usage = CouponUsage::create([

                'coupon_id'=>$coupon->id,

                'user_id'=>$user?->id,

                'quantity'=>$quantity,

            ]);



            $coupon->increment(
                'used_count',
                $quantity
            );



            return $usage;


        });

    }

}