<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;

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

    protected $casts = [
        'rating' => 'float',
        'is_active' => 'boolean',
    ];

    // ============================================================
    // 🔗 RELATIONSHIPS
    // ============================================================

    /**
     * برند محصول
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * تصاویر محصول
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order');
    }

    /**
     * واریانت‌های محصول
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * ✅ دسته‌بندی‌های محصول
     * 🔥 نام جدول pivot را مشخص کنید
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            '<?php

            namespace App\Models;
            
            use Illuminate\Database\Eloquent\Factories\HasFactory;
            use Illuminate\Database\Eloquent\Model;
            use Illuminate\Database\Eloquent\Relations\BelongsTo;
            use Illuminate\Database\Eloquent\Relations\BelongsToMany;
            use Illuminate\Database\Eloquent\Relations\HasMany;
            use Illuminate\Database\Eloquent\Relations\MorphToMany;
            
            class Product extends Model
            {
                use HasFactory;
            
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
            
                protected $casts = [
                    'rating' => 'float',
                    'is_active' => 'boolean',
                ];
            
                // ============================================================
                // 🔗 RELATIONSHIPS
                // ============================================================
            
                /**
                 * برند محصول
                 */
                public function brand(): BelongsTo
                {
                    return $this->belongsTo(Brand::class);
                }
            
                /**
                 * تصاویر محصول
                 */
                public function images(): HasMany
                {
                    return $this->hasMany(ProductImage::class)
                        ->orderBy('sort_order');
                }
            
                /**
                 * واریانت‌های محصول
                 */
                public function variants(): HasMany
                {
                    return $this->hasMany(ProductVariant::class);
                }
            
                /**
                 * ✅ دسته‌بندی‌های محصول
                 * 🔥 نام جدول pivot را مشخص کنید
                 */
                public function categories(): BelongsToMany
                {
                    return $this->belongsToMany(
                        Category::class,
                        'product_category',  // ← نام جدول pivot
                        'product_id',        // ← کلید خارجی برای Product
                        'category_id'        // ← کلید خارجی برای Category
                    );
                }
            
                /**
                 * تخفیف‌های محصول (Polymorphic)
                 */
                public function discounts(): MorphToMany
                {
                    return $this->morphToMany(
                        Discount::class,
                        'discountable'
                    );
                }
            
                /**
                 * همه نظرات
                 */
                public function reviews(): HasMany
                {
                    return $this->hasMany(Review::class);
                }
            
                /**
                 * نظرات تایید شده
                 */
                public function approvedReviews(): HasMany
                {
                    return $this->hasMany(Review::class)
                        ->where('status', 'approved');
                }
            
                // ============================================================
                // 🛠️ SCOPES
                // ============================================================
            
                /**
                 * فقط محصولات فعال
                 */
                public function scopeActive($query)
                {
                    return $query->where('is_active', true);
                }
            
                /**
                 * محصولات با تخفیف فعال
                 */
                public function scopeHasActiveDiscount($query)
                {
                    return $query->whereHas('discounts', function ($q) {
                        $q->where('is_active', true)
                          ->where('starts_at', '<=', now())
                          ->where('ends_at', '>=', now());
                    });
                }
            
                // ============================================================
                // 📊 ACCESSORS
                // ============================================================
            
                /**
                 * دریافت URL محصول
                 */
                public function getUrlAttribute(): string
                {
                    return route('product.show', $this->slug);
                }
            
                /**
                 * دریافت قیمت پایه (کمترین قیمت واریانت)
                 */
                public function getBasePriceAttribute(): float
                {
                    return $this->variants->min('base_price') ?? 0;
                }
            
                /**
                 * دریافت موجودی کل
                 */
                public function getStockAttribute(): int
                {
                    return $this->variants->sum('stock');
                }
            }',  // ← نام جدول pivot
            'product_id',        // ← کلید خارجی برای Product
            'category_id'        // ← کلید خارجی برای Category
        );
    }

    /**
     * تخفیف‌های محصول (Polymorphic)
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(
            Discount::class,
            'discountable'
        );
    }

    /**
     * همه نظرات
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * نظرات تایید شده
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('status', 'approved');
    }

    // ============================================================
    // 🛠️ SCOPES
    // ============================================================

    /**
     * فقط محصولات فعال
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * محصولات با تخفیف فعال
     */
    public function scopeHasActiveDiscount($query)
    {
        return $query->whereHas('discounts', function ($q) {
            $q->where('is_active', true)
              ->where('starts_at', '<=', now())
              ->where('ends_at', '>=', now());
        });
    }

    // ============================================================
    // 📊 ACCESSORS
    // ============================================================

    /**
     * دریافت URL محصول
     */
    public function getUrlAttribute(): string
    {
        return route('product.show', $this->slug);
    }

    /**
     * دریافت قیمت پایه (کمترین قیمت واریانت)
     */
    public function getBasePriceAttribute(): float
    {
        return $this->variants->min('base_price') ?? 0;
    }

    /**
     * دریافت موجودی کل
     */
    public function getStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }
}