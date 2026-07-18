<?php

namespace App\Services\Discount;

use App\Models\Discount;
use Carbon\Carbon;

class DiscountValidator
{

    public function validate(Discount $discount): bool
{

    if (!$discount->is_active) {
        return false;
    }


    if (
        $discount->starts_at &&
        now()->lt($discount->starts_at)
    ) {
        return false;
    }



    if (
        $discount->ends_at &&
        now()->gt($discount->ends_at)
    ) {
        return false;
    }



    if(
        $discount->quantity_limit &&
        $discount->used_quantity >= $discount->quantity_limit
    ){

        return false;

    }


    return true;

}

}