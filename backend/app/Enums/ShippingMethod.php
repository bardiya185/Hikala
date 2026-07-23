<?php

namespace App\Enums;

/**
 * 🚚 Shipping Method
 * 
 * Method selected by customer at checkout (with cost)
 */
enum ShippingMethod: string
{
    case STANDARD = 'standard';
    case EXPRESS  = 'express';
    case FREE     = 'free';


    public function label(): string
    {
        return match($this) {
            self::STANDARD => 'Standard Shipping',
            self::EXPRESS  => 'Express Shipping',
            self::FREE     => 'Free Shipping',
        };
    }

    public function estimatedDays(): string
    {
        return match($this) {
            self::STANDARD => '5-7 days',
            self::EXPRESS  => '2-3 days',
            self::FREE     => '7-10 days',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::STANDARD => 'blue',
            self::EXPRESS  => 'purple',
            self::FREE     => 'green',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}