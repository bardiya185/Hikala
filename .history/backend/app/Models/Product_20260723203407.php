<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'short_description',
        'description',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'view_count',
        'sort_order',
        'is_active',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function discounts()
{
    return $this->morphToMany(
        Discount::class,
        'discountable'
    );
}

public function ShippingFeatur()
{
    return $this->morphToMany(
        Discount::class,
        'discountable'
    );
}


    use HasFactory;
}
