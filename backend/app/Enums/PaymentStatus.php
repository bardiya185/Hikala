<?php

namespace App\Enums;

/**
 * 💳 Payment Status Enumeration
 */
enum PaymentStatus: string
{
    case PENDING  = 'pending';   // Awaiting payment
    case PAID     = 'paid';      // Successfully paid
    case FAILED   = 'failed';    // Payment failed
    case REFUNDED = 'refunded';  // Money returned to customer

    public function label(): string
    {
        return match($this) {
            self::PENDING  => 'Pending',
            self::PAID     => 'Paid',
            self::FAILED   => 'Failed',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING  => 'yellow',
            self::PAID     => 'green',
            self::FAILED   => 'red',
            self::REFUNDED => 'gray',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}