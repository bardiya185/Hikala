<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_title',
        'product_sku',
        'product_image',
        'variant_attributes',
        'quantity',
        'base_price',
        'final_price',
        'discount_amount',
        'total',
        'discount_id',
    ];

    protected $casts = [
        'variant_attributes' => 'array',
        'quantity' => 'integer',
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * 📊 Get discount percent
     */
    public function getDiscountPercentAttribute(): int
    {
        if ($this->base_price <= 0) return 0;
        
        return (int) round(($this->discount_amount / $this->base_price) * 100);
    }

    /**
     * 🖼️ Get full image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->product_image) return null;
        
        if (str_starts_with($this->product_image, 'http')) {
            return $this->product_image;
        }
        
        return asset('storage/' . $this->product_image);
    }
}