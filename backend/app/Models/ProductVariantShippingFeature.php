<?php

namespace App\Models;

use App\Enums\ShippingFeatureType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantShippingFeature extends Model
{

    protected $fillable = [

        'product_variant_id',

        'type',

        'title',

        'description',

        'is_active',

    ];



    protected $casts = [

        'type' => ShippingFeatureType::class,

        'is_active' => 'boolean',

    ];



    public function variant(): BelongsTo
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }

}
