<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'type',
        'value',
        'stackable',
        'quantity_limit',
        'used_quantity',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'stackable' => 'boolean',
        'used_quantity' => 'integer',
        'value' => 'float',
    ];

    // ================================================================
    // Relations
    // ================================================================

    public function campaign()
    {
        return $this->belongsTo(DiscountCampaign::class, 'campaign_id');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'discountable');
    }

    public function variants()
    {
        return $this->morphedByMany(ProductVariant::class, 'discountable');
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'discountable');
    }

    public function brands()
    {
        return $this->morphedByMany(Brand::class, 'discountable');
    }

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    public function userLimits()
    {
        return $this->hasMany(DiscountUserLimit::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    // ================================================================
    // Scopes
    // ================================================================

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                // campaign نداره یا campaign فعاله
                $q->whereNull('campaign_id')
                  ->orWhereHas('campaign', function ($q) {
                      $q->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('starts_at')
                              ->orWhere('starts_at', '<=', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('ends_at')
                              ->orWhere('ends_at', '>=', now());
                        });
                  });
            });
    }
}