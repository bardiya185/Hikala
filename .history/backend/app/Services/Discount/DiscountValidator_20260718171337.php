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


        if ($discount->starts_at &&
            Carbon::now()->lt($discount->starts_at)
        ) {
            return false;
        }


        if ($discount->ends_at &&
            Carbon::now()->gt($discount->ends_at)
        ) {
            return false;
        }


        return true;
    }

}