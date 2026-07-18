<?php

namespace App\Services\Coupon;


use App\Models\Coupon;


class CouponService
{


    public function find(string $code)
    {

        return Coupon::where(
            'code',
            $code
        )
        ->first();

    }


}