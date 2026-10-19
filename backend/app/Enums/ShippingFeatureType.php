<?php

namespace App\Enums;

enum ShippingFeatureType: string
{
    case FAST     = 'fast';        // 1 preparation day
    case SAME_DAY = 'same_day';    // 0 days (same day)
    case FREE     = 'free';        // 3 days
    case STANDARD = 'standard';    // 5 days

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
     * 📅 Minimum number of preparation days
     */
    public function minPreparationDays(): int
    {
        return match($this) {
            self::SAME_DAY => 0,   // today
            self::FAST     => 1,   // tomorrow
            self::FREE     => 3,   // 3 days
            self::STANDARD => 5,   // 5 days
        };
    }

    /**
     * 📅 Maximum number of preparation days
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