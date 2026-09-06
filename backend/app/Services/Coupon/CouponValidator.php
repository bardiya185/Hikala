<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use App\Models\User;

class CouponValidator
{
    /**
     * Validate coupon (basic checks)
     */
    public function validate(Coupon $coupon, int $quantity = 1): bool
    {
        if (!$coupon->is_active) {
            throw new \Exception('Coupon is not active');
        }
        if (!$coupon->discount) {
            throw new \Exception('Coupon has no discount attached');
        }
        if (!$coupon->discount->is_active) {
            throw new \Exception('Discount is not active');
        }
        if ($coupon->discount->starts_at && now()->lt($coupon->discount->starts_at)) {
            throw new \Exception('Coupon is not yet available');
        }
        if ($coupon->discount->ends_at && now()->gt($coupon->discount->ends_at)) {
            throw new \Exception('Coupon has expired');
        }
        if ($coupon->usage_limit && ($coupon->used_count + $quantity) > $coupon->usage_limit) {
            throw new \Exception('Coupon usage limit reached');
        }

        return true;
    }

    /**
     * Validate for specific user
     */
    public function validateForUser(Coupon $coupon, ?User $user, int $quantity = 1): bool
    {
        if (!$user) return true;
        $userUsageCount = $coupon->usages()
            ->where('user_id', $user->id)
            ->sum('quantity');
        if ($userUsageCount >= 1) {
            throw new \Exception('You have already used this coupon');
        }

        return true;
    }
}