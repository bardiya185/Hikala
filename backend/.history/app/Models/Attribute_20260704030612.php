<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
َ

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'unit',
        'is_filterable',
        'is_variant',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_variant' => 'boolean',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'category_attributes'
        )->withPivot('sort_order');
    }
}