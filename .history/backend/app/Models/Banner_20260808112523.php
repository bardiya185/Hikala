<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Banner extends Model
{
    protected $fillable = [
        'banner_position_id',
        'title',
        'subtitle',
        'image',
        'mobile_image',
        'alt_text',
        'linkable_type',
        'linkable_id',
        'custom_url',
        'background_color',
        'text_color',
        'starts_at',
        'ends_at',
        'sort_order',
        'is_active',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'click_count' => 'integer',
        'view_count' => 'integer',
    ];

    // ✅ رابطه با Position
    public function position(): BelongsTo
    {
        return $this->belongsTo(BannerPosition::class, 'banner_position_id');
    }

    // ✅ Polymorphic - به هر چیزی میتونه وصل بشه
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    // ✅ Scope: بنرهای فعال
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

    // ✅ ساخت لینک نهایی
    public function getUrlAttribute(): ?string
    {
        // اولویت ۱: custom_url
        if ($this->custom_url) {
            return $this->custom_url;
        }
    
        // اولویت ۲: linkable با eager load
        if ($this->linkable) {
            return match ($this->linkable_type) {
                \App\Models\Product::class => "/products/{$this->linkable->slug}",
                \App\Models\Category::class => "/category/{$this->linkable->slug}",
                \App\Models\Brand::class => "/brand/{$this->linkable->slug}",
                default => null,
            };
        }
    
        // اولویت ۳: فقط ID (اگه linkable load نشده)
        if ($this->linkable_type && $this->linkable_id) {
            return match ($this->linkable_type) {
                \App\Models\Product::class => "/products/{$this->linkable_id}",
                \App\Models\Category::class => "/category/{$this->linkable_id}",
                \App\Models\Brand::class => "/brand/{$this->linkable_id}",
                default => null,
            };
        }
    
        return null;
    }

    // ✅ آدرس کامل تصویر
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->mobile_image
            ? asset('storage/' . $this->mobile_image)
            : $this->image_url;
    }
}