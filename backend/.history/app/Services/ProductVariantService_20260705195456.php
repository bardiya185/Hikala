<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductVariantService
{
    public function create(Product $product, array $attributes): void
    {
        $combinations = $this->generateCombinations($attributes);

        foreach ($combinations as $combination) {

            $sku = $this->generateSku($product, $combination);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'price' => $product->price ?? 0,
                'stock' => 10,
            ]);

            // اینجا بعداً attribute values رو وصل می‌کنیم
        }
    }

    private function generateCombinations(array $attributes): array
    {
        $result = [[]];

        foreach ($attributes as $values) {

            $temp = [];

            foreach ($result as $combination) {
                foreach ($values as $value) {
                    $temp[] = array_merge($combination, [$value]);
                }
            }

            $result = $temp;
        }

        return $result;
    }

    private function generateSku(Product $product, array $combination): string
    {
        return Str::slug($product->slug) . '-' . implode('-', $combination);
    }
}