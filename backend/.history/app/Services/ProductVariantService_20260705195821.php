<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use App\Models\AttributeValue;

class ProductVariantService
{
    public function create(Product $product, array $attributes): void
    {
        $combinations = $this->generateCombinations($attributes);

        foreach ($combinations as $combination) {

            $valueIds = [];

            foreach ($combination as $value) {
                $valueIds[] = AttributeValue::where('value', $value)->firstOrFail()->id;
            }

            $sku = $this->generateSku($product, $combination);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'price' => $product->price ?? 0,
                'stock' => 10,
            ]);

            // اتصال واقعی
            $variant->attributeValues()->attach($valueIds);
        }
    }
}