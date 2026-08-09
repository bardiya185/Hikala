<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductVariantService 
{
    public function create(array $data): Product
    {
        // اگر slug داده نشده، از title بساز
        if (!isset($data['slug']) && isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $product = Product::create($data);

        // اگر categories داده شده، ارتباط برقرار کن
        if (isset($data['categories']) && is_array($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return $product->fresh();
    }

    public function update(Product $product, array $data): Product
    {
        // اگر title تغییر کرده و slug داده نشده، slug رو آپدیت کن
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $product->update($data);

        // اگر categories داده شده، آپدیت کن
        if (isset($data['categories']) && is_array($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function toggleActive(Product $product): Product
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return $product;
    }
}