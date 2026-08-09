<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ غیرفعال کردن محدودیت‌های کلید خارجی
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // فقط جدول categories رو truncate کن
        DB::table('categories')->truncate();
        
        // فعال کردن مجدد محدودیت‌ها
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------------------------------
        | 1. MAIN CATEGORIES (دسته‌بندی‌های اصلی)
        |--------------------------------------------------------------------------
        */
        $mainCategories = [
            ['name' => 'Mobile', 'slug' => 'mobile'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Digital Products', 'slug' => 'digital-products'],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
            ['name' => 'Home Appliances', 'slug' => 'home-appliances'],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Gold & Jewelry', 'slug' => 'gold-jewelry'],
            ['name' => 'Vehicles', 'slug' => 'vehicles'],
            ['name' => 'Health & Medical', 'slug' => 'health-medical'],
            ['name' => 'Tools & Equipment', 'slug' => 'tools-equipment'],
            ['name' => 'Books & Art', 'slug' => 'books-art'],
            ['name' => 'Sports & Travel', 'slug' => 'sports-travel'],
            ['name' => 'Gift Cards', 'slug' => 'gift-cards'],
        ];

        $mainIds = [];
        foreach ($mainCategories as $category) {
            $mainIds[$category['slug']] = DB::table('categories')->insertGetId([
                'parent_id' => null,
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. SELECT CATEGORIES (دسته‌بندی‌های واسط)
        |--------------------------------------------------------------------------
        */
        $selectCategories = [
            ['parent' => 'mobile', 'name' => 'Select Mobile', 'slug' => 'select-mobile'],
            ['parent' => 'laptops', 'name' => 'Select Laptop', 'slug' => 'select-laptop'],
            ['parent' => 'digital-products', 'name' => 'Select Digital', 'slug' => 'select-digital'],
            ['parent' => 'home-kitchen', 'name' => 'Select Home', 'slug' => 'select-home'],
            ['parent' => 'home-appliances', 'name' => 'Select Appliance', 'slug' => 'select-appliance'],
            ['parent' => 'beauty-health', 'name' => 'Select Beauty', 'slug' => 'select-beauty'],
            ['parent' => 'fashion', 'name' => 'Select Fashion', 'slug' => 'select-fashion'],
            ['parent' => 'gold-jewelry', 'name' => 'Select Jewelry', 'slug' => 'select-jewelry'],
            ['parent' => 'vehicles', 'name' => 'Select Vehicle', 'slug' => 'select-vehicle'],
            ['parent' => 'health-medical', 'name' => 'Select Medical', 'slug' => 'select-medical'],
            ['parent' => 'tools-equipment', 'name' => 'Select Tool', 'slug' => 'select-tool'],
            ['parent' => 'books-art', 'name' => 'Select Book', 'slug' => 'select-book'],
            ['parent' => 'sports-travel', 'name' => 'Select Sport', 'slug' => 'select-sport'],
            ['parent' => 'gift-cards', 'name' => 'Select Gift', 'slug' => 'select-gift'],
        ];

        $selectIds = [];
        foreach ($selectCategories as $category) {
            $parentId = $mainIds[$category['parent']] ?? null;
            $selectIds[$category['slug']] = DB::table('categories')->insertGetId([
                'parent_id' => $parentId,
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. CHILD CATEGORIES (دسته‌بندی‌های نهایی)
        |--------------------------------------------------------------------------
        */
        $children = [
            // ===== MOBILE =====
            ['parent' => 'select-mobile', 'name' => 'Apple Phones', 'slug' => 'apple-phones'],
            ['parent' => 'select-mobile', 'name' => 'Samsung Phones', 'slug' => 'samsung-phones'],
            ['parent' => 'select-mobile', 'name' => 'Xiaomi Phones', 'slug' => 'xiaomi-phones'],
            ['parent' => 'select-mobile', 'name' => 'Other Brands', 'slug' => 'other-brands'],
            
            // ===== LAPTOPS =====
            ['parent' => 'select-laptop', 'name' => 'Apple MacBooks', 'slug' => 'apple-macbooks'],
            ['parent' => 'select-laptop', 'name' => 'ASUS Laptops', 'slug' => 'asus-laptops'],
            ['parent' => 'select-laptop', 'name' => 'Lenovo Laptops', 'slug' => 'lenovo-laptops'],
            ['parent' => 'select-laptop', 'name' => 'Gaming Laptops', 'slug' => 'gaming-laptops'],
            ['parent' => 'select-laptop', 'name' => 'Business Laptops', 'slug' => 'business-laptops'],
            ['parent' => 'select-laptop', 'name' => 'Student Laptops', 'slug' => 'student-laptops'],
            
            // ===== DIGITAL PRODUCTS =====
            ['parent' => 'select-digital', 'name' => 'Gaming Consoles', 'slug' => 'gaming-consoles'],
            ['parent' => 'select-digital', 'name' => 'Headphones', 'slug' => 'headphones'],
            ['parent' => 'select-digital', 'name' => 'Smartwatches', 'slug' => 'smartwatches'],
            ['parent' => 'select-digital', 'name' => 'Tablets', 'slug' => 'tablets'],
            ['parent' => 'select-digital', 'name' => 'Speakers', 'slug' => 'speakers'],
            ['parent' => 'select-digital', 'name' => 'Cameras', 'slug' => 'cameras'],
            ['parent' => 'select-digital', 'name' => 'Power Banks', 'slug' => 'power-banks'],
            ['parent' => 'select-digital', 'name' => 'Computer Components', 'slug' => 'computer-components'],
            ['parent' => 'select-digital', 'name' => 'Smart Home', 'slug' => 'smart-home'],
            ['parent' => 'select-digital', 'name' => 'Printers', 'slug' => 'printers'],
            ['parent' => 'select-digital', 'name' => 'Storage Devices', 'slug' => 'storage-devices'],
            ['parent' => 'select-digital', 'name' => 'Networking', 'slug' => 'networking'],
            
            // ===== HOME & KITCHEN =====
            ['parent' => 'select-home', 'name' => 'Cookware', 'slug' => 'cookware'],
            ['parent' => 'select-home', 'name' => 'Tea & Coffee', 'slug' => 'tea-coffee'],
            ['parent' => 'select-home', 'name' => 'Furniture', 'slug' => 'furniture'],
            ['parent' => 'select-home', 'name' => 'Lighting', 'slug' => 'lighting'],
            ['parent' => 'select-home', 'name' => 'Carpets & Rugs', 'slug' => 'carpets-rugs'],
            ['parent' => 'select-home', 'name' => 'Bedroom', 'slug' => 'bedroom'],
            
            // ===== HOME APPLIANCES =====
            ['parent' => 'select-appliance', 'name' => 'Refrigerators', 'slug' => 'refrigerators'],
            ['parent' => 'select-appliance', 'name' => 'Washing Machines', 'slug' => 'washing-machines'],
            ['parent' => 'select-appliance', 'name' => 'Dishwashers', 'slug' => 'dishwashers'],
            ['parent' => 'select-appliance', 'name' => 'Vacuums', 'slug' => 'vacuums'],
            ['parent' => 'select-appliance', 'name' => 'Cooking Appliances', 'slug' => 'cooking-appliances'],
            ['parent' => 'select-appliance', 'name' => 'TVs', 'slug' => 'tvs'],
            
            // ===== BEAUTY & HEALTH =====
            ['parent' => 'select-beauty', 'name' => 'Skin Care', 'slug' => 'skin-care'],
            ['parent' => 'select-beauty', 'name' => 'Makeup', 'slug' => 'makeup'],
            ['parent' => 'select-beauty', 'name' => 'Hair Care', 'slug' => 'hair-care'],
            ['parent' => 'select-beauty', 'name' => 'Perfumes', 'slug' => 'perfumes'],
            ['parent' => 'select-beauty', 'name' => 'Oral Care', 'slug' => 'oral-care'],
            ['parent' => 'select-beauty', 'name' => 'Personal Care', 'slug' => 'personal-care'],
            
            // ===== FASHION =====
            ['parent' => 'select-fashion', 'name' => 'Men\'s Clothing', 'slug' => 'mens-clothing'],
            ['parent' => 'select-fashion', 'name' => 'Women\'s Clothing', 'slug' => 'womens-clothing'],
            ['parent' => 'select-fashion', 'name' => 'Children\'s Clothing', 'slug' => 'childrens-clothing'],
            ['parent' => 'select-fashion', 'name' => 'Shoes', 'slug' => 'shoes'],
            ['parent' => 'select-fashion', 'name' => 'Bags & Accessories', 'slug' => 'bags-accessories'],
            
            // ===== GOLD & JEWELRY =====
            ['parent' => 'select-jewelry', 'name' => 'Gold Jewelry', 'slug' => 'gold-jewelry-items'],
            ['parent' => 'select-jewelry', 'name' => 'Silver Jewelry', 'slug' => 'silver-jewelry'],
            ['parent' => 'select-jewelry', 'name' => 'Diamonds & Gems', 'slug' => 'diamonds-gems'],
            
            // ===== VEHICLES =====
            ['parent' => 'select-vehicle', 'name' => 'Cars', 'slug' => 'cars'],
            ['parent' => 'select-vehicle', 'name' => 'Motorcycles', 'slug' => 'motorcycles'],
            ['parent' => 'select-vehicle', 'name' => 'Car Accessories', 'slug' => 'car-accessories'],
            
            // ===== HEALTH & MEDICAL =====
            ['parent' => 'select-medical', 'name' => 'Medical Equipment', 'slug' => 'medical-equipment'],
            ['parent' => 'select-medical', 'name' => 'Orthopedic', 'slug' => 'orthopedic'],
            ['parent' => 'select-medical', 'name' => 'Supplements', 'slug' => 'supplements'],
            ['parent' => 'select-medical', 'name' => 'Fitness Equipment', 'slug' => 'fitness-equipment'],
            
            // ===== TOOLS & EQUIPMENT =====
            ['parent' => 'select-tool', 'name' => 'Power Tools', 'slug' => 'power-tools'],
            ['parent' => 'select-tool', 'name' => 'Hand Tools', 'slug' => 'hand-tools'],
            ['parent' => 'select-tool', 'name' => 'Gardening Tools', 'slug' => 'gardening-tools'],
            
            // ===== BOOKS & ART =====
            ['parent' => 'select-book', 'name' => 'Books', 'slug' => 'books'],
            ['parent' => 'select-book', 'name' => 'Art & Painting', 'slug' => 'art-painting'],
            
            // ===== SPORTS & TRAVEL =====
            ['parent' => 'select-sport', 'name' => 'Sports Equipment', 'slug' => 'sports-equipment'],
            ['parent' => 'select-sport', 'name' => 'Travel Equipment', 'slug' => 'travel-equipment'],
            
            // ===== GIFT CARDS =====
            ['parent' => 'select-gift', 'name' => 'Store Gift Cards', 'slug' => 'store-gift-cards'],
            ['parent' => 'select-gift', 'name' => 'Digital Gift Cards', 'slug' => 'digital-gift-cards'],
        ];

        foreach ($children as $child) {
            $parentId = $selectIds[$child['parent']] ?? null;
            if ($parentId) {
                DB::table('categories')->insert([
                    'parent_id' => $parentId,
                    'name' => $child['name'],
                    'slug' => $child['slug'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('✅ ' . DB::table('categories')->count() . ' categories seeded successfully!');
    }
}