<?php

namespace App\Enums;

/**
 * 🚀 Shipping Feature Types
 * 
 * Features that a product variant supports
 * (Display only - no cost calculation)
 */
enum ShippingFeatureType: string
{
    case FAST     = 'fast';      // Fast delivery available
    case SAME_DAY = 'same_day';  // Same day delivery
    case FREE     = 'free';      // Free shipping option
    case STANDARD = 'standard';  // Standard shipping
    

    /**
     * 🏷️ Display label
     */
    public function label(): string
    {
        return match($this) {
            self::FAST     => 'Fast Delivery',
            self::SAME_DAY => 'Same Day Delivery',
            self::FREE     => 'Free Shipping',
            self::STANDARD => 'Standard Shipping',
        };
    }

    /**
     * 🎨 UI icon
     */
    public function icon(): string
    {
        return match($this) {
            self::FAST     => '⚡',
            self::SAME_DAY => '🚀',
            self::FREE     => '🎁',
            self::STANDARD => '📦',
        };
    }

    /**
     * 🎨 UI color
     */
    public function color(): string
    {
        return match($this) {
            self::FAST     => 'orange',
            self::SAME_DAY => 'red',
            self::FREE     => 'green',
            self::STANDARD => 'blue',
        };
    }

    /**
     * 📋 All values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}