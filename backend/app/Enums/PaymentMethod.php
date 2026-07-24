<?php

namespace App\Enums;

/**
 * 💳 Payment Method Enumeration
 */
enum PaymentMethod: string
{
    case ONLINE            = 'online';              // Online gateway
    case CASH_ON_DELIVERY  = 'cash_on_delivery';    // Pay at delivery
    case WALLET            = 'wallet';              // In-app wallet

    public function label(): string
    {
        return match($this) {
            self::ONLINE           => 'Online Payment',
            self::CASH_ON_DELIVERY => 'Cash on Delivery',
            self::WALLET           => 'Wallet',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ONLINE           => '💳',
            self::CASH_ON_DELIVERY => '💵',
            self::WALLET           => '👛',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}