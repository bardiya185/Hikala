<?php

namespace App\Enums;

enum ShippingFeatureType: string
{
    case FAST     = 'fast';        // 1 روز آماده‌سازی
    case SAME_DAY = 'same_day';    // 0 روز (همان روز)
    case FREE     = 'free';        // 3 روز
    case STANDARD = 'standard';    // 5 روز

    public function label(): string
    {
        return match($this) {
            self::FAST     => 'Fast Delivery (1 day)',
            self::SAME_DAY => 'Same Day Delivery',
            self::FREE     => 'Free Shipping (3-5 days)',
            self::STANDARD => 'Standard (5-7 days)',
        };
    }

    /**
     * 📅 حداقل روز آماده‌سازی
     */
    public function minPreparationDays(): int
    {
        return match($this) {
            self::SAME_DAY => 0,   // امروز
            self::FAST     => 1,   // فردا
            self::FREE     => 3,   // 3 روز
            self::STANDARD => 5,   // 5 روز
        };
    }

    /**
     * 📅 حداکثر روز آماده‌سازی
     */
    public function maxPreparationDays(): int
    {
        return match($this) {
            self::SAME_DAY => 0,   
            self::FAST     => 1,   
            self::FREE     => 5,   
            self::STANDARD => 7,   
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SAME_DAY => '⚡',
            self::FAST     => '🚀',
            self::FREE     => '🎁',
            self::STANDARD => '📦',
        };
    }
}