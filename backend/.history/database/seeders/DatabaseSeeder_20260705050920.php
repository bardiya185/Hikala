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
        $categories = [
            ['name' => 'موبایل', 'slug' => 'mobile'],
            ['name' => 'لپ‌تاپ', 'slug' => 'laptop'],
            ['name' => 'هدفون', 'slug' => 'headphone'],
            ['name' => 'کنسول بازی', 'slug' => 'console'],
        ];
    
        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }
    
        // =====================
        // 100 PRODUCTS (درست همونی که گفتی)
        // =====================
        \App\Models\Product::factory(100)->create();
    
        // =====================
        // IMAGES برای همه محصولات
        // =====================
        $products = \App\Models\Product::all();
    
        foreach ($products as $product) {
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'path' => 'products/default.jpg',
                'alt' => $product->name,
                'sort_order' => 1,
                'is_main' => true,
            ]);
        }
    }
 }