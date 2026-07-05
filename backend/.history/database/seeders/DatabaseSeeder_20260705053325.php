<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
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
        User::create([
            'name' => 'Admin',
            'mobile' => '09120000000',
        ]);

        // =====================
        // CATEGORIES
        // =====================
        $mobile = Category::create([
            'name' => 'موبایل',
            'slug' => 'mobile',
        ]);

        $laptop = Category::create([
            'name' => 'لپ‌تاپ',
            'slug' => 'laptop',
        ]);

        // =====================
        // ATTRIBUTES
        // =====================
        $color = Attribute::create([
            'name' => 'Color',
            'slug' => 'color',
        ]);

        $storage = Attribute::create([
            'name' => 'Storage',
            'slug' => 'storage',
        ]);

        $black = AttributeValue::create([
            'attribute_id' => $color->id,
            'value' => 'Black',
        ]);

        $blue = AttributeValue::create([
            'attribute_id' => $color->id,
            'value' => 'Blue',
        ]);

        $gb128 = AttributeValue::create([
            'attribute_id' => $storage->id,
            'value' => '128GB',
        ]);

        $gb256 = AttributeValue::create([
            'attribute_id' => $storage->id,
            'value' => '256GB',
        ]);

        // =====================
        // PRODUCTS + VARIANTS (100 تا واقعی)
        // =====================
        Product::factory()->count(100)->create()->each(function ($product) use (
            $mobile,
            $black,
            $blue,
            $gb128,
            $gb256
        ) {

            // =====================
            // CATEGORY (pivot)
            // =====================
            $product->categories()->syncWithoutDetaching([
                $mobile->id,
            ]);

            // =====================
            // VARIANT 1
            // =====================
            $variant1 = $product->variants()->create([
                'price' => rand(10000000, 50000000),
                'sku' => 'SKU-'.rand(100000, 999999),
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
                'sku' => 'SKU-'.rand(100000, 999999),
            ]);

            $variant2->attributeValues()->sync([
                $blue->id,
                $gb256->id,
            ]);

            $v1->inventory()->create([
                'quantity' => rand(1, 50),
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
}
