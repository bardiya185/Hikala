<?php

namespace App\Services\Order;

use App\Models\Order;

/**
 * 🎫 Generates unique order numbers
 * 
 * Format: ORD-YYYYMMDD-NNNN
 * Example: ORD-20241027-0001
 */
class OrderNumberGenerator
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "ORD-{$date}";
        
        // آخرین سفارش این روز رو پیدا کن
        $lastOrder = Order::where('order_number', 'LIKE', "{$prefix}-%")
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$lastOrder) {
            $sequence = 1;
        } else {
            // 4 رقم آخر رو بگیر
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $sequence = $lastNumber + 1;
        }
        
        // 4 رقمی با صفر پیش‌رو
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$sequence}";
    }
}
