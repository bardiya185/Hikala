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

            $variant = $product->variants()->create([

                'price' => $variantData['price'],

                'sku' => strtoupper(uniqid('SKU-')),

            ]);

            $variant->inventory()->create([

                'quantity' => $variantData['stock'],

            ]);

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