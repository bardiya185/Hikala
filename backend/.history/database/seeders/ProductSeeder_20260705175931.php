<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = require database_path('data/products_1.php');

        foreach ($products as $item) {

            $brand = Brand::where('slug', $item['brand'])->first();

            if (!$brand) {
                continue;
            }

            $category = Category::where('name', $item['category'])->first();

            if (!$category) {
                continue;
            }

            $product = Product::updateOrCreate(
                [
                    'slug' => $item['slug'],
                ],
                [
                    'brand_id' => $brand->id,
                    'title' => $item['title'],
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'status' => 'active',
                    'meta_title' => $item['title'],
                    'meta_keywords' => $item['title'],
                    'meta_description' => $item['short_description'],
                    'view_count' => 0,
                    'sort_order' => 0,
                    'is_active' => true,
                ]
            );

            $product->categories()->syncWithoutDetaching([
                $category->id,
            ]);
        }
    }
}