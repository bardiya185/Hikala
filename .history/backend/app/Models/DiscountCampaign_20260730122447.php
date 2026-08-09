<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountCampaign extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'banner_image',
        'priority',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];


    // ================================================================
    // 🔗 Relationships
    // ================================================================

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'campaign_id');
    }


    // ================================================================
    // 🔍 Scopes
    // ================================================================

 
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

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }


    // ================================================================
    // 🎯 Helpers
    // ================================================================


    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->ends_at && now()->gt($this->ends_at)) return false;
        return true;
    }

    /**
     * URL عکس بنر
     */
    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_image) return null;
        
        if (str_starts_with($this->banner_image, 'http')) {
            return $this->banner_image;
        }
        
        return asset('storage/' . $this->banner_image);
    }
}