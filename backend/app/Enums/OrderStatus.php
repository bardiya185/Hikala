<?php

namespace App\Enums;

/**
 * 📊 Order Status Enumeration
 * 
 * Lifecycle:
 * pending → paid → processing → shipped → delivered
 *                                             ↓
 *                                         refunded
 * 
 * At any stage before delivered: can be canceled
 */
enum OrderStatus: string
{
    case PENDING    = 'pending';
    case PAID       = 'paid';
    case PROCESSING = 'processing';
    case SHIPPED    = 'shipped';
    case DELIVERED  = 'delivered';
    case CANCELED   = 'canceled';
    case REFUNDED   = 'refunded';


    // ================================================================
    // 🎯 Helper Methods
    // ================================================================

    /**
     * 🏷️ Display label (English)
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING    => 'Pending Payment',
            self::PAID       => 'Paid',
            self::PROCESSING => 'Processing',
            self::SHIPPED    => 'Shipped',
            self::DELIVERED  => 'Delivered',
            self::CANCELED   => 'Canceled',
            self::REFUNDED   => 'Refunded',
        };
    }

    /**
     * 📝 Description of each status
     */
    public function description(): string
    {
        return match($this) {
            self::PENDING    => 'Waiting for payment confirmation',
            self::PAID       => 'Payment received, awaiting processing',
            self::PROCESSING => 'Order is being prepared',
            self::SHIPPED    => 'Order is on the way',
            self::DELIVERED  => 'Order delivered successfully',
            self::CANCELED   => 'Order was canceled',
            self::REFUNDED   => 'Order was refunded',
        };
    }

    /**
     * 🎨 UI color
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING    => 'yellow',
            self::PAID       => 'blue',
            self::PROCESSING => 'purple',
            self::SHIPPED    => 'indigo',
            self::DELIVERED  => 'green',
            self::CANCELED   => 'red',
            self::REFUNDED   => 'gray',
        };
    }

    /**
     * 🎨 UI icon (Emoji)
     */
    public function icon(): string
    {
        return match($this) {
            self::PENDING    => '⏳',
            self::PAID       => '💰',
            self::PROCESSING => '📦',
            self::SHIPPED    => '🚚',
            self::DELIVERED  => '✅',
            self::CANCELED   => '❌',
            self::REFUNDED   => '🔄',
        };
    }

    /**
     * ❌ Can this order be canceled?
     * Only before delivery
     */
    public function canBeCanceled(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::PAID,
            self::PROCESSING,
            self::SHIPPED,
        ]);
    }

    /**
     * 🔄 Can this order be refunded?
     * Only after delivery
     */
    public function canBeRefunded(): bool
    {
        return $this === self::DELIVERED;
    }

    /**
     * ✅ Is this a final status?
     * (No more changes possible)
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::DELIVERED,
            self::CANCELED,
            self::REFUNDED,
        ]);
    }

    /**
     * 💰 Is the order paid?
     */
    public function isPaid(): bool
    {
        return in_array($this, [
            self::PAID,
            self::PROCESSING,
            self::SHIPPED,
            self::DELIVERED,
            self::REFUNDED,
        ]);
    }

    /**
     * 📋 Get all status values
     * (Useful for validation and Swagger)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * 📋 Get all statuses as [value => label] array
     * (Useful for select boxes)
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}