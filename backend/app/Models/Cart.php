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

    /**
     * Sum of the base prices
     */
    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->base_price * $item->quantity);
    }

    /**
     * Total of the product discounts
     */
    public function getProductsDiscountAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->discount_amount * $item->quantity);
    }

    /**
     * Sum before the coupon
     */
    public function getTotalBeforeCouponAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->final_price * $item->quantity);
    }

    /**
     * Coupon discount amount
     */

public function getCouponDiscountAttribute(): float
{
    if (!$this->coupon || !$this->coupon->discount) return 0;
    
    $discount = $this->coupon->discount;
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
     * Final total
     */
    public function getFinalTotalAttribute(): float
    {
        return max(0, $this->total_before_coupon - $this->coupon_discount);
    }

    /**
     * Total number of items
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items->count();
    }

    /**
     * Check whether the cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}