<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 🟢 رابطه برند با محصولات (یک به چند)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
{
    return $this->belongsToMany(Category::class, 'category_brand');
}

public function discounts()
{
    return $this->morphToMany(
        Discount::class,
        'discountable'
    );
}

}