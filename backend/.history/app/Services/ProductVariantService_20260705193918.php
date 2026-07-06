<?php

namespace App\Services;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Services\SkuGeneratorService;

class ProductVariantService
{
    public function create(Product $product, array $variants): void
    {
        foreach ($variants as $variantData) {

            // 🔥 فقط اطلاعات ضروری رو به SKU بده (نه toArray)
            $sku = (new SkuGeneratorService())->generate(
                [
                    'brand' => $product->brand->slug ?? null,
                    'slug' => $product->slug,
                ],
                $variantData
            );

            // ساخت Variant
            $variant = $product->variants()->create([
                'price' => $variantData['price'],
                'sku'   => $sku,
            ]);

            // Inventory
            $variant->inventory()->create([
                'quantity' => $variantData['stock'],
            ]);

            // اتصال attribute values
            $attributeValueIds = [];

            foreach ($variantData['attributes'] as $attributeSlug => $valueSlug) {

                $attributeValue = AttributeValue::query()
                    ->whereHas('attribute', function ($query) use ($attributeSlug) {
                        $query->where('slug', $attributeSlug);
                    })
                    ->where('slug', $valueSlug)
                    ->first();

                if ($attributeValue) {
                    $attributeValueIds[] = $attributeValue->id;
                }
            }

            $variant->attributeValues()->sync($attributeValueIds);
        }
    }
}