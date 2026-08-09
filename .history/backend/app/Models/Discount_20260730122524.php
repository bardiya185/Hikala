<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'stackable',
        'starts_at',
        'ends_at',
        'quantity_limit',
        'used_quantity',
        'priority',
        'campaign_id',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_flash_sale' => 'boolean',
        'is_active' => 'boolean',
        'stackable' => 'boolean',
        'used_quantity' => 'integer',
    ];

    public function products()
    {
        return $this->morphedByMany(
            Product::class,
            'discountable'
        );
    }
    
    public function variants()
    {
        return $this->morphedByMany(
            ProductVariant::class,
            'discountable'
        );
    }
    
    public function categories()
    {
        return $this->morphedByMany(
            Category::class,
            'discountable'
        );
    }
    
    public function brands()
    {
        return $this->morphedByMany(
            Brand::class,
            'discountable'
        );
    }
    public function usages()
{
    return $this->hasMany(DiscountUsage::class);
}
public function userLimits()
{
    return $this->hasMany(
        DiscountUserLimit::class
    );
}
public function coupons()
{
    return $this->hasMany(
        Coupon::class
    );
}



// Discount.php
public function scopeActive($query)
{
    return $query
        ->where('is_active', true)
        ->where(fn($q) => $q
            ->whereNull('starts_at')
            ->orWhere('starts_at', '<=', now())
        )
        ->where(fn($q) => $q
            ->whereNull('ends_at')
            ->orWhere('ends_at', '>=', now())
        );
}

}