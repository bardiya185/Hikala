<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'quantity_limit',
        'priority',
        'is_flash_sale',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_flash_sale' => 'boolean',
        'is_active' => 'boolean',
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
}