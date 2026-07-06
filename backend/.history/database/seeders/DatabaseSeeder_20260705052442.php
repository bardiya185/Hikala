<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =====================
        // USER
        // =====================
        \App\Models\User::create([
            'name' => 'Admin',
            'mobile' => '09120000000',
        ]);
    
        // =====================
        // CATEGORIES
        // =====================
        $mobile = \App\Models\Category::create([
            'name' => 'موبایل',
            'slug' => 'mobile',
        ]);
    
        $laptop = \App\Models\Category::create([
            'name' => 'لپ‌تاپ',
            'slug' => 'laptop',
        ]);
    
        // =====================
        // ATTRIBUTES
        // =====================
        $color = \App\Models\Attribute::create([
            'name' => 'Color',
            'slug' => 'color',
        ]);
        
        $storage = \App\Models\Attribute::create([
            'name' => 'Storage',
            'slug' => 'storage',
        ]);
    
        $black = \App\Models\AttributeValue::create([
            'attribute_id' => $color->id,
            'value' => 'Black'
        ]);
    
        $blue = \App\Models\AttributeValue::create([
            'attribute_id' => $color->id,
            'value' => 'Blue'
        ]);
    
        $gb128 = \App\Models\AttributeValue::create([
            'attribute_id' => $storage->id,
            'value' => '128GB'
        ]);
    
        $gb256 = \App\Models\AttributeValue::create([
            'attribute_id' => $storage->id,
            'value' => '256GB'
        ]);
    
        // =====================
        // PRODUCTS + VARIANTS (100 تا واقعی)
        // =====================
        \App\Models\Product::factory()->count(100)->create()->each(function ($product) use (
            $mobile,
            $laptop,
            $black,
            $blue,
            $gb128,
            $gb256
        ) {
        
            // =====================
            // CATEGORY (pivot)
            // =====================
            $product->categories()->syncWithoutDetaching([
                $mobile->id
            ]);
        
            // =====================
            // VARIANT 1
            // =====================
            $variant1 = $product->variants()->create([
                'price' => rand(10000000, 50000000),
                'sku' => 'SKU-' . rand(100000, 999999),
            ]);
        
            $variant1->attributeValues()->sync([
                $black->id,
                $gb128->id,
            ]);
        
            $variant1->inventory()->create([
                'stock' => rand(1, 50),
            ]);
        
            // =====================
            // VARIANT 2
            // =====================
            $variant2 = $product->variants()->create([
                'price' => rand(10000000, 50000000),
                'sku' => 'SKU-' . rand(100000, 999999),
            ]);
        
            $variant2->attributeValues()->sync([
                $blue->id,
                $gb256->id,
            ]);
        
            $variant2->inventory()->create([
                'stock' => rand(1, 50),
            ]);
        
            // =====================
            // IMAGES
            // =====================
            $product->images()->create([
                'path' => 'products/default.jpg',
                'alt' => $product->title ?? 'product',
                'is_main' => true,
                'sort_order' => 1,
            ]);
        });
 }