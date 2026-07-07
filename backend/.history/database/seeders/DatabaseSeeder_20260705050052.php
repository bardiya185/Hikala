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
        // USERS
        // =====================
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
        ]);
    
        // =====================
        // CATEGORIES
        // =====================
        $categories = [
            ['name' => 'موبایل', 'slug' => 'mobile'],
            ['name' => 'لپ‌تاپ', 'slug' => 'laptop'],
            ['name' => 'هدفون', 'slug' => 'headphone'],
        ];
    
        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }
    
        // =====================
        // PRODUCTS (100 تا واقعی‌نما)
        // =====================
        \App\Models\Product::factory(100)->create();
    
        // =====================
        // PRODUCT IMAGES (برای هر محصول 1 تصویر)
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
