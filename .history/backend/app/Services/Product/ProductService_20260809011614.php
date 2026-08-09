<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttributeValue;
use App\Models\DiscountCampaign;
use App\Services\Discount\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * ✅ Default eager loading relations
     */
    private array $defaultRelations = [
        'brand.discounts.campaign',
        'categories.discounts.campaign',
        'images',
        'variants.discounts.campaign',
        'variants.attributeValues',
        'variants.shippingFeatures',
        'discounts.campaign',
        'approvedReviews.user',
    ];

    public function __construct(
        private DiscountService $discountService
    ) {}

    // ================================================================
    // 📋 LIST PRODUCTS (Main entry point)
    // ================================================================
    
    /**
     * لیست محصولات با تمام فیلترها
     */
    public function list(Request $request): array
    {
        $query = $this->buildBaseQuery($request);

        $sortBy = $this->getSortField($request);
        $sortOrder = $request->get('sort_order', 'desc');
        $sortByPrice = $sortBy === 'base_price';

        if (!$sortByPrice) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // 🎯 Route to appropriate handler
        if ($request->filled('campaign')) {
            return $this->filterByCampaign($query, $request, $sortByPrice, $sortOrder);
        }

        if ($this->hasDiscountFilter($request)) {
            return $this->filterByDiscount($query, $request, $sortByPrice, $sortOrder);
        }

        if ($request->has('cursor')) {
            return $this->cursorPaginate($query, $request, $sortByPrice, $sortOrder);
        }

        return $this->standardPaginate($query, $request, $sortByPrice, $sortOrder);
    }

    /**
     * لیست محصولات یک کمپین خاص (برای صفحه اختصاصی کمپین)
     */
    public function listByCampaign(DiscountCampaign $campaign, Request $request): array
    {
        $query = $this->buildBaseQuery($request);
        
        // فورس کن campaign slug رو
        $request->merge(['campaign' => $campaign->slug]);

        $sortBy = $this->getSortField($request);
        $sortOrder = $request->get('sort_order', 'desc');
        $sortByPrice = $sortBy === 'base_price';

        if (!$sortByPrice) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $this->filterByCampaign($query, $request, $sortByPrice, $sortOrder);
    }

    // ================================================================
    // 🔧 QUERY BUILDER
    // ================================================================
    
    private function buildBaseQuery(Request $request): Builder
    {
        $query = Product::query()
            ->with($this->defaultRelations)
            ->where('is_active', 1);

        $this->applyCategoryFilter($query, $request);
        $this->applyBrandFilter($query, $request);
        $this->applyPriceFilter($query, $request);
        $this->applyAttributesFilter($query, $request);
        $this->applySearchFilter($query, $request);

        return $query;
    }

   /**
 * فیلتر دسته‌بندی (شامل زیردسته‌ها)
 * پشتیبانی از یک یا چند دسته
 */
private function applyCategoryFilter(Builder $query, Request $request): void
{
    // 🎯 حالت ۱: چند دسته (category_ids=49,62)
    if ($request->has('category_ids')) {
        $ids = array_filter(explode(',', $request->category_ids));
        
        if (empty($ids)) return;

        // شامل زیردسته‌های هر کدوم هم بشه
        $allCategoryIds = [];
        foreach ($ids as $id) {
            $subCategoryIds = Category::where('parent_id', $id  ,  'is_active' , '!=' , false)
                ->pluck('id')
                ->toArray();
            $allCategoryIds = array_merge($allCategoryIds, [$id], $subCategoryIds);
        }

        $query->whereHas('categories', fn($q) =>
            $q->whereIn('category_id', array_unique($allCategoryIds))
        );
        return;
    }

    // 🎯 حالت ۲: یک دسته (category_id=49)
    if ($request->has('category_id')) {
        $categoryId = $request->category_id;
        $subCategoryIds = Category::where('parent_id', $categoryId , 'and' , 'is_active' , '!=' , false)
            ->pluck('id')
            ->toArray();
        $allCategoryIds = array_merge([$categoryId], $subCategoryIds);

        $query->whereHas('categories', fn($q) =>
            $q->whereIn('category_id', $allCategoryIds)
        );
    }
}
    private function applyBrandFilter(Builder $query, Request $request): void
    {
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
    }

    private function applyPriceFilter(Builder $query, Request $request): void
    {
        if (!$request->has('min_price') && !$request->has('max_price')) return;

        $query->whereHas('variants', function ($q) use ($request) {
            if ($request->has('min_price')) {
                $q->where('base_price', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $q->where('base_price', '<=', $request->max_price);
            }
        });
    }

    private function applyAttributesFilter(Builder $query, Request $request): void
    {
        if (!$request->has('attributes_id')) return;

        $attributeIds = explode(',', $request->attributes_id);
        $query->whereHas('variants.attributeValues', fn($q) =>
            $q->whereIn('attribute_value_id', $attributeIds)
        );
    }

    private function applySearchFilter(Builder $query, Request $request): void
    {
        if (!$request->has('search')) return;

        $search = $request->search;
        $query->where(fn($q) =>
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('short_description', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
        );
    }

    // ================================================================
    // 🎯 CAMPAIGN FILTER (داینامیک - جایگزین Flash Sale)
    // ================================================================
    
    /**
     * فیلتر محصولات بر اساس کمپین
     * پشتیبانی از: flash-sale, special, weekly, clearance, ...
     */
    private function filterByCampaign(Builder $query, Request $request, bool $sortByPrice, string $sortOrder): array
    {
        $campaignSlug = $request->get('campaign');

        $products = $query->get()
            ->map(fn($product) => $this->attachPricingData($product))
            ->filter(function ($product) use ($campaignSlug) {
                // فقط محصولاتی که تخفیف اعمال شده در این کمپین دارن
                return $product->_campaign_slug === $campaignSlug;
            });

        // Sorting
        if ($sortByPrice) {
            $products = $products->sortBy('_final_price', SORT_REGULAR, $sortOrder === 'desc');
        } else {
            $products = $products->sortByDesc('_discount_percent');
        }

        return $this->paginateCollection($products->values(), $request, [
            'campaign' => $campaignSlug,
        ]);
    }

    // ================================================================
    // 💸 DISCOUNT FILTER
    // ================================================================
    
    private function filterByDiscount(Builder $query, Request $request, bool $sortByPrice, string $sortOrder): array
    {
        $products = $query->get()
            ->map(fn($product) => $this->attachPricingData($product))
            ->filter(function ($product) use ($request) {
                if ($product->_discount_percent <= 0) return false;

                if ($request->has('min_discount') 
                    && $product->_discount_percent < (int) $request->min_discount) {
                    return false;
                }

                if ($request->has('max_discount') 
                    && $product->_discount_percent > (int) $request->max_discount) {
                    return false;
                }

                return true;
            });

        if ($sortByPrice) {
            $products = $products->sortBy('_final_price', SORT_REGULAR, $sortOrder === 'desc');
        }

        return $this->paginateCollection($products->values(), $request, [
            'filters_applied' => [
                'min_discount' => (int) $request->get('min_discount', 0),
                'max_discount' => $request->has('max_discount') ? (int) $request->max_discount : null,
                'has_discount' => $request->boolean('has_discount'),
            ]
        ]);
    }

    // ================================================================
    // 📄 PAGINATION
    // ================================================================
    
    private function standardPaginate(Builder $query, Request $request, bool $sortByPrice, string $sortOrder): array
    {
        if ($sortByPrice) {
            $sorted = $query->get()
                ->map(fn($product) => $this->attachEffectivePrice($product))
                ->sortBy('_effective_price', SORT_REGULAR, $sortOrder === 'desc')
                ->values();

            return $this->paginateCollection($sorted, $request, [
                'sort_note' => 'sorted_by_final_price',
            ]);
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $products = $query->paginate($perPage);

        return [
            'data' => $products,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $products->hasMorePages(),
                'next_cursor' => null,
            ],
        ];
    }

    private function cursorPaginate(Builder $query, Request $request, bool $sortByPrice, string $sortOrder): array
    {
        $limit = min((int) $request->get('limit', 20), 50);
        $cursor = $request->get('cursor');

        if ($sortByPrice) {
            return $this->cursorPaginateByPrice($query, $sortOrder, $limit, $cursor);
        }

        return $this->cursorPaginateById($query, $sortOrder, $limit, $cursor);
    }

    private function cursorPaginateById(Builder $query, string $sortOrder, int $limit, ?string $cursor): array
    {
        if ($cursor) {
            $decoded = json_decode(base64_decode($cursor), true);
            if ($decoded && isset($decoded['id'])) {
                $operator = $sortOrder === 'asc' ? '>' : '<';
                $query->where('id', $operator, $decoded['id']);
            }
        }

        $products = $query->limit($limit + 1)->get();
        $hasMore = $products->count() > $limit;
        $products = $products->take($limit);

        $nextCursor = null;
        if ($hasMore && $products->isNotEmpty()) {
            $nextCursor = base64_encode(json_encode(['id' => $products->last()->id]));
        }

        return [
            'data' => $products,
            'meta' => [
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
                'limit' => $limit,
                'current_page' => null,
                'last_page' => null,
                'per_page' => $limit,
                'total' => null,
            ],
        ];
    }

    private function cursorPaginateByPrice(Builder $query, string $sortOrder, int $limit, ?string $cursor): array
    {
        $allProducts = $query->get()
            ->map(fn($product) => $this->attachEffectivePrice($product))
            ->sortBy('_effective_price', SORT_REGULAR, $sortOrder === 'desc')
            ->values();

        $startIndex = 0;
        if ($cursor) {
            $decoded = json_decode(base64_decode($cursor), true);
            if ($decoded && isset($decoded['index'])) {
                $startIndex = $decoded['index'];
            }
        }

        $items = $allProducts->slice($startIndex, $limit + 1)->values();
        $hasMore = $items->count() > $limit;
        $items = $items->take($limit);

        $nextCursor = null;
        if ($hasMore) {
            $nextCursor = base64_encode(json_encode(['index' => $startIndex + $limit]));
        }

        return [
            'data' => $items,
            'meta' => [
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
                'limit' => $limit,
                'current_page' => null,
                'last_page' => null,
                'per_page' => $limit,
                'total' => $allProducts->count(),
            ],
        ];
    }

    private function paginateCollection(Collection $products, Request $request, array $extraMeta = []): array
    {
        $perPage = min((int) $request->get('per_page', 20), 100);
        $page = max((int) $request->get('page', 1), 1);
        $total = $products->count();
        $items = $products->slice(($page - 1) * $perPage, $perPage)->values();
        $lastPage = (int) ceil($total / $perPage);

        return [
            'data' => $items,
            'meta' => array_merge([
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => $page < $lastPage,
                'next_cursor' => null,
            ], $extraMeta),
        ];
    }

    // ================================================================
    // 🛠️ HELPERS
    // ================================================================
    
    /**
     * محاسبه قیمت نهایی، تخفیف و کمپین
     */
    private function attachPricingData(Product $product): Product
    {
        $variant = $this->getDefaultVariant($product);

        if (!$variant) {
            $product->_campaign_slug = null;
            $product->_campaign_name = null;
            $product->_discount_percent = 0;
            $product->_final_price = 0;
            return $product;
        }

        $pricing = $this->discountService->calculate($variant);
        $campaign = $pricing->discount?->campaign;

        // 🎯 اطلاعات کمپین (داینامیک - هر کمپینی می‌تونه باشه)
        $product->_campaign_slug = $campaign?->slug;
        $product->_campaign_name = $campaign?->name;
        $product->_campaign_icon = $campaign?->icon;
        $product->_campaign_color = $campaign?->color;
        $product->_campaign_ends_at = $campaign?->ends_at;

        $product->_discount_percent = $pricing->basePrice > 0
            ? round(($pricing->discountAmount / $pricing->basePrice) * 100)
            : 0;

        $product->_final_price = $pricing->price;
        $product->_base_price = $pricing->basePrice;
        $product->_discount_amount = $pricing->discountAmount;

        return $product;
    }

    private function attachEffectivePrice(Product $product): Product
    {
        $variant = $this->getDefaultVariant($product);
        $product->_effective_price = $variant
            ? $this->discountService->calculate($variant)->price
            : 0;
        return $product;
    }

    private function getDefaultVariant(Product $product): ?ProductVariant
    {
        return $product->variants->firstWhere('is_default', true)
            ?? $product->variants->firstWhere('is_active', true)
            ?? $product->variants->first();
    }

    private function hasDiscountFilter(Request $request): bool
    {
        return $request->has('min_discount') 
            || $request->has('max_discount') 
            || $request->boolean('has_discount');
    }

    private function getSortField(Request $request): string
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $allowedSortFields = ['base_price', 'view_count', 'created_at', 'title', 'sort_order'];

        return in_array($sortBy, $allowedSortFields) ? $sortBy : 'created_at';
    }

    public function getDefaultRelations(): array
    {
        return $this->defaultRelations;
    }

    // ================================================================
    // ✏️ CREATE, UPDATE, DELETE (بدون تغییر)
    // ================================================================
    
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $product = Product::create([
                'brand_id' => $data['brand_id'] ?? null,
                'title' => $data['title'] ?? null,
                'slug' => $data['slug'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'active',
                'meta_title' => $data['meta_title'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'is_active' => $data['is_active'] ?? 1,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $this->createVariant($product, $variantData);
                }
            }

            return $product->load(['brand', 'categories', 'variants.attributeValues.attribute']);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            if (isset($data['title']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $product->update([
                'brand_id' => $data['brand_id'] ?? $product->brand_id,
                'title' => $data['title'] ?? $product->title,
                'slug' => $data['slug'] ?? $product->slug,
                'short_description' => $data['short_description'] ?? $product->short_description,
                'description' => $data['description'] ?? $product->description,
                'status' => $data['status'] ?? $product->status,
                'meta_title' => $data['meta_title'] ?? $product->meta_title,
                'meta_keywords' => $data['meta_keywords'] ?? $product->meta_keywords,
                'meta_description' => $data['meta_description'] ?? $product->meta_description,
                'is_active' => $data['is_active'] ?? $product->is_active,
                'sort_order' => $data['sort_order'] ?? $product->sort_order,
            ]);

            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        $this->updateVariant($product, $variantData);
                    } else {
                        $this->createVariant($product, $variantData);
                    }
                }
            }

            return $product->fresh()->load(['brand', 'categories', 'variants.attributeValues.attribute']);
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            foreach ($product->variants as $variant) {
                ProductVariantAttributeValue::where('product_variant_id', $variant->id)->delete();
                $variant->delete();
            }

            $product->categories()->detach();
            $product->delete();
        });
    }

    public function toggleActive(Product $product): Product
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return $product;
    }

    // ================================================================
    // 🔧 VARIANT HELPERS
    // ================================================================
    
    private function createVariant(Product $product, array $variantData): ProductVariant
    {
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $variantData['sku'] ?? 'SKU-' . Str::random(8),
            'barcode' => $variantData['barcode'] ?? null,
            'base_price' => $variantData['base_price'] ?? null,
            'stock' => $variantData['stock'] ?? 0,
            'max_order_quantity' => $variantData['max_order_quantity'] ?? 5, 
            'weight' => $variantData['weight'] ?? 0,
            'is_active' => $variantData['is_active'] ?? 1,
        ]);

        $this->syncVariantAttributes($variant, $variantData['attributes'] ?? []);
        return $variant;
    }

    private function updateVariant(Product $product, array $variantData): void
    {
        $variant = ProductVariant::find($variantData['id']);

        if (!$variant || $variant->product_id != $product->id) return;

        $variant->update([
            'sku' => $variantData['sku'] ?? $variant->sku,
            'barcode' => $variantData['barcode'] ?? $variant->barcode,
            'base_price' => $variantData['base_price'] ?? $variant->base_price,
            'stock' => $variantData['stock'] ?? $variant->stock,
            'max_order_quantity' => $variantData['max_order_quantity'] ?? $variant->max_order_quantity,
            'weight' => $variantData['weight'] ?? $variant->weight,
            'is_active' => $variantData['is_active'] ?? $variant->is_active,
        ]);

        if (isset($variantData['attributes'])) {
            ProductVariantAttributeValue::where('product_variant_id', $variant->id)->delete();
            $this->syncVariantAttributes($variant, $variantData['attributes']);
        }
    }

    private function syncVariantAttributes(ProductVariant $variant, array $attributes): void
    {
        if (empty($attributes)) return;

        foreach ($attributes as $attributeData) {
            if (isset($attributeData['attribute_value_id'])) {
                ProductVariantAttributeValue::create([
                    'product_variant_id' => $variant->id,
                    'attribute_value_id' => $attributeData['attribute_value_id'],
                ]);
            }
        }
    }
}