<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttributeValue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // اگر slug داده نشده، از title بساز
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // فیلدهای قابل قبول برای محصول
            $productData = [
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
            ];

            // ایجاد محصول
            $product = Product::create($productData);

            // ارتباط با دسته‌بندی‌ها
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            // ایجاد تنوع‌ها (variants)
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    // ایجاد تنوع
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'] ?? 'SKU-' . Str::random(8),
                        'barcode' => $variantData['barcode'] ?? null,
                        'price' => $variantData['price'] ?? 0,
                        'base_price' => $variantData['base_price'] ?? null,
                        'stock' => $variantData['stock'] ?? 0,
                        'weight' => $variantData['weight'] ?? 0,
                        'is_active' => $variantData['is_active'] ?? 1,
                    ]);

                    // اتصال ویژگی‌ها به تنوع
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attributeData) {
                            if (isset($attributeData['attribute_value_id'])) {
                                ProductVariantAttributeValue::create([
                                    'product_variant_id' => $variant->id,
                                    'attribute_value_id' => $attributeData['attribute_value_id'],
                                ]);
                            }
                        }
                    }
                }
            }

            return $product->load(['brand', 'categories', 'variants.attributeValues.attribute']);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // اگر title تغییر کرده و slug داده نشده
            if (isset($data['title']) && !isset($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // فیلدهای قابل قبول برای آپدیت
            $productData = [
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
            ];

            // آپدیت محصول
            $product->update($productData);

            // آپدیت دسته‌بندی‌ها
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            // آپدیت تنوع‌ها
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'sku' => $variantData['sku'] ?? $variant->sku,
                                'barcode' => $variantData['barcode'] ?? $variant->barcode,
                                'price' => $variantData['price'] ?? $variant->price,
                                'base_price' => $variantData['base_price'] ?? $variant->sale_price,
                                'stock' => $variantData['stock'] ?? $variant->stock,
                                'weight' => $variantData['weight'] ?? $variant->weight,
                                'is_active' => $variantData['is_active'] ?? $variant->is_active,
                            ]);

                            if (isset($variantData['attributes'])) {
                                ProductVariantAttributeValue::where('product_variant_id', $variant->id)->delete();
                                foreach ($variantData['attributes'] as $attributeData) {
                                    if (isset($attributeData['attribute_value_id'])) {
                                        ProductVariantAttributeValue::create([
                                            'product_variant_id' => $variant->id,
                                            'attribute_value_id' => $attributeData['attribute_value_id'],
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $variantData['sku'] ?? 'SKU-' . Str::random(8),
                            'barcode' => $variantData['barcode'] ?? null,
                            'price' => $variantData['price'] ?? 0,
                            'sale_price' => $variantData['sale_price'] ?? null,
                            'stock' => $variantData['stock'] ?? 0,
                            'weight' => $variantData['weight'] ?? 0,
                            'is_active' => $variantData['is_active'] ?? 1,
                        ]);

                        if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                            foreach ($variantData['attributes'] as $attributeData) {
                                if (isset($attributeData['attribute_value_id'])) {
                                    ProductVariantAttributeValue::create([
                                        'product_variant_id' => $variant->id,
                                        'attribute_value_id' => $attributeData['attribute_value_id'],
                                    ]);
                                }
                            }
                        }
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
}