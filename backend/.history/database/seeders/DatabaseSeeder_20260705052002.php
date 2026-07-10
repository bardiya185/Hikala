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
            'password' => bcrypt('123456'),
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
        $color = \App\Models\Attribute::create(['name' => 'Color']);
        $storage = \App\Models\Attribute::create(['name' => 'Storage']);
    
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
        \App\Models\Product::factory(100)->create()->each(function ($product) use ($mobile, $laptop, $black, $blue, $gb128, $gb256) {
    
            // اتصال به دسته‌بندی
            $product->categories()->attach($mobile->id);
    
            // Variant 1
            $v1 = $product->variants()->create([
                'price' => rand(10000000, 50000000),
                'sku' => 'SKU-' . rand(1000,9999),
            ]);
    
            $v1->attributeValues()->attach([$black->id, $gb128->id]);
    
            // Inventory
            $v1->inventory()->create([
                'stock' => rand(1, 50),
            ]);
    
            // Variant 2
            $v2 = $product->variants()->create([
                'price' => rand(10000000, 50000000),
                'sku' => 'SKU-' . rand(1000,9999),
            ]);
    
            $v2->attributeValues()->attach([$blue->id, $gb256->id]);
    
            $v2->inventory()->create([
                'stock' => rand(1, 50),
            ]);
    
            // Images
            $product->images()->create([
                'path' => 'products/default.jpg',
                'alt' => $product->id,
                'is_main' => true,
                'sort_order' => 1,
            ]);
        });
    }
 }