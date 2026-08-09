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
        // فعاله؟
        if (!$coupon->is_active) {
            throw new \Exception('Coupon is not active');
        }

        // discount مرتبط داره؟
        if (!$coupon->discount) {
            throw new \Exception('Coupon has no discount attached');
        }

        // discount فعاله؟
        if (!$coupon->discount->is_active) {
            throw new \Exception('Discount is not active');
        }

        // تاریخ شروع discount گذشته؟
        if ($coupon->discount->starts_at && now()->lt($coupon->discount->starts_at)) {
            throw new \Exception('Coupon is not yet available');
        }

        // تاریخ پایان discount نگذشته؟
        if ($coupon->discount->ends_at && now()->gt($coupon->discount->ends_at)) {
            throw new \Exception('Coupon has expired');
        }

        // ظرفیت کوپن پر نشده؟
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
        // اگه مهمانه، ok
        if (!$user) return true;

        // چند بار این کاربر استفاده کرده؟
        $userUsageCount = $coupon->usages()
            ->where('user_id', $user->id)
            ->sum('quantity');

        // برای این نسخه: هر کاربر فقط یه بار
        // اگه بخوای چند بار مجاز باشه، این چک رو حذف کن
        if ($userUsageCount >= 1) {
            throw new \Exception('You have already used this coupon');
        }

        return true;
    }
}