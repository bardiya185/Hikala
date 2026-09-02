<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
        'base_price',
        'final_price',
        'discount_amount',
        'discount_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

<<<<<<< Updated upstream
<<<<<<< Updated upstream
=======
=======
>>>>>>> Stashed changes
    /**
     * جمع این آیتم (قیمت اصلی × تعداد)
     */
>>>>>>> Stashed changes
    public function getSubtotalAttribute(): float
    {
        return $this->base_price * $this->quantity;
    }

 
    public function getTotalAttribute(): float
    {
        return $this->final_price * $this->quantity;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->base_price <= 0) return 0;
        
        return (int) round(($this->discount_amount / $this->base_price) * 100);
    }
}