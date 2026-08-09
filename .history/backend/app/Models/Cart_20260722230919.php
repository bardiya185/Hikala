<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_id',
    ];

    // ================================================================
    // Relationships
    // ================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    // ================================================================
    // Accessors (محاسبات خودکار)
    // ================================================================

    /**
     * جمع قیمت‌های اصلی
     */
    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->base_price * $item->quantity);
    }

    /**
     * مجموع تخفیف محصولات
     */
    public function getProductsDiscountAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->discount_amount * $item->quantity);
    }

    /**
     * جمع قبل از کوپن
     */
    public function getTotalBeforeCouponAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->final_price * $item->quantity);
    }

    /**
     * مقدار تخفیف کوپن
     */
    public function getCouponDiscountAttribute(): float
    {
        if (!$this->coupon) return 0;
        
        $discount = $this->coupon->discount;
        if (!$discount) return 0;
        
        $subtotal = $this->total_before_coupon;
        
        if ($discount->type === 'percent') {
            return ($subtotal * $discount->value) / 100;
        }
        
        return min($discount->value, $subtotal);
    }

    /**
     * جمع نهایی
     */
// app/Models/Cart.php

public function getCouponDiscountAttribute(): float
{
    if (!$this->coupon || !$this->coupon->discount) return 0;
    
    $discount = $this->coupon->discount;
    
    // چک فعال بودن
    if (!$discount->is_active) return 0;
    if ($discount->ends_at && now()->gt($discount->ends_at)) return 0;
    
    $subtotal = $this->total_before_coupon;
    
    if ($discount->type === 'percent') {
        return round(($subtotal * $discount->value) / 100, 2);
    }
    
    if ($discount->type === 'fixed') {
        return min((float) $discount->value, $subtotal);
    }
    
    return 0;
}
    /**
     * تعداد کل آیتم‌ها
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    // ================================================================
    // Helpers
    // ================================================================

    /**
     * چک کن سبد خالیه یا نه
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}