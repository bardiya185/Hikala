<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{
    protected $fillable = [

        'attribute_id',
    
        'value',
    
        'code',
    
        'color_code',
    
        'image',
    
        'sort_order',
    
        'is_active',
    
    ];
    
    protected $casts = [
    
        'is_active' => 'boolean',
    
    ];
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants()
{
    return $this->belongsToMany(
        ProductVariant::class,
        'product_variant_attribute_values'
    );
}
}