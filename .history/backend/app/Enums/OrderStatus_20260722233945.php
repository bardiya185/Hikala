<?php

namespace App\Enums;

/**
 * 📊 وضعیت‌های سفارش
 * 
 * چرخه حیات:
 * pending → paid → processing → shipped → delivered
 *                                              ↓
 *                                          refunded
 * 
 * در هر مرحله (قبل از delivered): می‌تونه canceled بشه
 */
enum OrderStatus: string
{
    // ⏳ منتظر پرداخت
    case PENDING = 'pending';
    
    // ✅ پرداخت شده
    case PAID = 'paid';
    
    // 📦 در حال آماده‌سازی
    case PROCESSING = 'processing';
    
    // 🚚 ارسال شده
    case SHIPPED = 'shipped';
    
    // ✅ تحویل داده شده
    case DELIVERED = 'delivered';
    
    // ❌ لغو شده (توسط کاربر یا ادمین قبل از تحویل)
    case CANCELED = 'canceled';
    
    // 🔄 مرجوع شده (بعد از تحویل)
    case REFUNDED = 'refunded';


    // ================================================================
    // 🎯 متدهای کمکی
    // ================================================================
   

    /**
     * 🎨 رنگ برای نمایش در UI
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
     * ❌ آیا این سفارش قابل لغو هست؟
     * فقط قبل از تحویل می‌تونه لغو بشه
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
     * 🔄 آیا این سفارش قابل مرجوع هست؟
     * فقط بعد از تحویل می‌تونه مرجوع بشه
     */
    public function canBeRefunded(): bool
    {
        return $this === self::DELIVERED;
    }

    /**
     * ✅ آیا این سفارش نهایی شده؟
     * (دیگه نمی‌شه تغییرش داد)
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
     * 💰 آیا این سفارش پرداخت شده؟
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
     * 📋 لیست همه وضعیت‌ها (برای Swagger)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
