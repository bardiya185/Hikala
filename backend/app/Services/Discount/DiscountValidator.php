<?php

namespace App\Services\Discount;

use App\Models\Discount;
use App\Models\User;


class DiscountValidator
{

    /**
     * Validate discount status, date and global quantity limit
     */
    public function validateDiscount(
        Discount $discount,
        int $quantity = 1
    ): bool
    {

        // Check active status
        if (!$discount->is_active) {

            return false;

        }


        // Check start date
        if (
            $discount->starts_at &&
            now()->lt($discount->starts_at)
        ) {

            return false;

        }


        // Check end date
        if (
            $discount->ends_at &&
            now()->gt($discount->ends_at)
        ) {

            return false;

        }


        // Check total quantity limit
        if (
            $discount->quantity_limit &&
            (
                $discount->used_quantity + $quantity
            ) > $discount->quantity_limit
        ) {

            return false;

        }


        return true;

    }



    /**
     * Validate user specific discount limit
     */
    public function validateForUser(
        Discount $discount,
        ?User $user,
        int $quantity = 1
    ): bool
    {

        // Guest users have no personal limit
        if (!$user) {

            return true;

        }



        // Find user limit
        $limit = $discount
            ->userLimits()
            ->where('user_id', $user->id)
            ->first();



        // No custom limit
        if (!$limit) {

            return true;

        }



        // Calculate previous usage
        $used = $discount
            ->usages()
            ->where('user_id', $user->id)
            ->sum('quantity');



        // Check user limit
        if (
            ($used + $quantity)
            > $limit->max_quantity
        ) {

            return false;

        }



        return true;

    }

}