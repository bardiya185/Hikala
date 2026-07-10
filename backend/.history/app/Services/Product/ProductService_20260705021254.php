<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $categories = $data['categories'] ?? [];

            unset($data['categories']);

            $product = Product::create($data);

            $product->categories()->sync($categories);

            return $product->load([
                'brand',
                'categories',
            ]);
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

            $categories = $data['categories'] ?? [];

            unset($data['categories']);

            $product->update($data);

            $product->categories()->sync($categories);

            return $product->load([
                'brand',
                'categories',
            ]);
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $product->delete();
        });
    }
}