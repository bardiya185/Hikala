<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BannerPosition extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'max_banners',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_banners' => 'integer',
    ];

    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    public function activeBanners(): HasMany
    {
        return $this->hasMany(Banner::class)
            ->active()
            ->orderBy('sort_order');
    }
}