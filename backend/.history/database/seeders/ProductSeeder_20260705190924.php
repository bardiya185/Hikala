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
        $this->seedDirectory(database_path('data/mobile'));
    
        $this->seedDirectory(database_path('data/laptop'));
    
        // بعداً
        // $this->seedDirectory(database_path('data/digital'));
        // $this->seedDirectory(database_path('data/home'));
        // $this->seedDirectory(database_path('data/fashion'));
    }
    private function seedDirectory(string $path): void
    {
        foreach (glob($path . '/*.php') as $file) {

            $products = require $file;

            foreach ($products as $item) {

                (new \app\Services\Image\)->create($item);

            }

        }
    }

    private function createProduct(array $item): void
    {
        $brand = Brand::where('slug', $item['brand'])->first();

        if (!$brand) {
            return;
        }

        $category = Category::where('name', $item['category'])->first();

        if (!$category) {
            return;
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

        foreach ($item['variants'] as $variantData) {

            $variant = $product->variants()->create([
        
                'price' => $variantData['price'],
        
                'sku' => strtoupper(uniqid('SKU-')),
        
            ]);
        
            $variant->inventory()->create([
        
                'quantity' => $variantData['stock'],
        
            ]);
        
            $attributeValueIds = [];
        
            foreach ($variantData['attributes'] as $attributeSlug => $valueSlug) {
        
                $attributeValue = \App\Models\AttributeValue::query()
        
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