<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'base_price',
        'stock',
        'weight',
        'is_active',
        'is_default',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }



    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        );
    }



    public function attributeValuesPivot(): HasMany
    {
        return $this->hasMany(
            ProductVariantAttributeValue::class,
            'product_variant_id'
        );
    }



    public function inventory(): HasOne
    {
        return $this->hasOne(
            Inventory::class
        );
    }



    public function discounts()
    {
        return $this->morphToMany(
            Discount::class,
            'discountable'
        );
    }



    /**
     * قیمت قابل استفاده قبل از تخفیف
     */
    public function getBasePriceAttribute()
    {
        return $this->attributes['base_price']
            ?? $this->attributes['price'];
    }

    public function shippingFeatures()
    {
        return $this->hasMany(
            ProductVariantShippingFeature::class
        );
    }

}
