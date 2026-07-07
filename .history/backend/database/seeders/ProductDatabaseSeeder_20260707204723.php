<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting complete product seeding with all categories and subcategories...');

        // ================================================================
        // 1. CREATE CATEGORIES WITH SUBCATEGORIES (ENGLISH)
        // ================================================================
        $categories = [
            // ===== Mobile (موبایل) =====
            [
                'name' => 'Mobile',
                'slug' => 'mobile',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Apple Phones', 'slug' => 'apple-phones'],
                    ['name' => 'Samsung Phones', 'slug' => 'samsung-phones'],
                    ['name' => 'Xiaomi Phones', 'slug' => 'xiaomi-phones'],
                    ['name' => 'Google Pixel', 'slug' => 'google-pixel'],
                    ['name' => 'OnePlus', 'slug' => 'oneplus'],
                    ['name' => 'Huawei', 'slug' => 'huawei'],
                    ['name' => 'Nokia', 'slug' => 'nokia'],
                    ['name' => 'Sony Xperia', 'slug' => 'sony-xperia'],
                    ['name' => 'Mobile Accessories', 'slug' => 'mobile-accessories'],
                ]
            ],
            // ===== Laptops (لپ‌تاپ) =====
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Apple MacBooks', 'slug' => 'apple-macbooks'],
                    ['name' => 'Gaming Laptops', 'slug' => 'gaming-laptops'],
                    ['name' => 'Business Laptops', 'slug' => 'business-laptops'],
                    ['name' => 'Ultrabooks', 'slug' => 'ultrabooks'],
                    ['name' => 'Student Laptops', 'slug' => 'student-laptops'],
                    ['name' => 'Laptop Accessories', 'slug' => 'laptop-accessories'],
                    ['name' => 'Laptop Bags', 'slug' => 'laptop-bags'],
                ]
            ],
            // ===== Digital Products (کالای دیجیتال) =====
            [
                'name' => 'Digital Products',
                'slug' => 'digital-products',
                'sort_order' => 3,
                'children' => [
                    // Computer Components (قطعات کامپیوتر)
                    ['name' => 'CPU Processors', 'slug' => 'cpu-processors'],
                    ['name' => 'Graphics Cards', 'slug' => 'graphics-cards'],
                    ['name' => 'Motherboards', 'slug' => 'motherboards'],
                    ['name' => 'RAM Memory', 'slug' => 'ram-memory'],
                    ['name' => 'Computer Cases', 'slug' => 'computer-cases'],
                    ['name' => 'Bluetooth Dongles', 'slug' => 'bluetooth-dongles'],
                    ['name' => 'Thermal Paste', 'slug' => 'thermal-paste'],
                    ['name' => 'Keyboards', 'slug' => 'keyboards'],
                    ['name' => 'Wireless Keyboards', 'slug' => 'wireless-keyboards'],
                    ['name' => 'Mechanical Keyboards', 'slug' => 'mechanical-keyboards'],
                    ['name' => 'Mice', 'slug' => 'mice'],
                    ['name' => 'Gaming Mice', 'slug' => 'gaming-mice'],
                    ['name' => 'Wireless Mice', 'slug' => 'wireless-mice'],
                    // Smart Home (خانه هوشمند)
                    ['name' => 'Smart Home', 'slug' => 'smart-home'],
                    ['name' => 'Smart Lighting', 'slug' => 'smart-lighting'],
                    ['name' => 'Smart Switches', 'slug' => 'smart-switches'],
                    // Printers (پرینتر)
                    ['name' => '3D Printers', 'slug' => '3d-printers'],
                    ['name' => 'Thermal Printers', 'slug' => 'thermal-printers'],
                    ['name' => 'Color Printers', 'slug' => 'color-printers'],
                    ['name' => 'HP Printers', 'slug' => 'hp-printers'],
                    ['name' => 'Office Machines', 'slug' => 'office-machines'],
                    ['name' => 'Printer Cables', 'slug' => 'printer-cables'],
                    ['name' => 'Cartridges', 'slug' => 'cartridges'],
                    ['name' => 'Scanners', 'slug' => 'scanners'],
                    // Telephones (تلفن)
                    ['name' => 'VoIP Phones', 'slug' => 'voip-phones'],
                    ['name' => 'Barcode Readers', 'slug' => 'barcode-readers'],
                    ['name' => 'Video Walls', 'slug' => 'video-walls'],
                    ['name' => 'Projectors', 'slug' => 'projectors'],
                    ['name' => 'Attendance Machines', 'slug' => 'attendance-machines'],
                    ['name' => 'Queue Systems', 'slug' => 'queue-systems'],
                    ['name' => 'Touch Kiosks', 'slug' => 'touch-kiosks'],
                    ['name' => 'Apple Watch', 'slug' => 'apple-watch-digital'],
                    // Headphones (هدفون)
                    ['name' => 'Wireless Headphones', 'slug' => 'wireless-headphones'],
                    ['name' => 'Gaming Headphones', 'slug' => 'gaming-headphones'],
                    ['name' => 'Anker Headphones', 'slug' => 'anker-headphones'],
                    ['name' => 'Apple AirPods', 'slug' => 'apple-airpods'],
                    ['name' => 'Beats Headphones', 'slug' => 'beats-headphones'],
                    ['name' => 'Sony Headphones', 'slug' => 'sony-headphones'],
                    ['name' => 'Samsung Buds', 'slug' => 'samsung-buds'],
                    ['name' => 'Xiaomi Headphones', 'slug' => 'xiaomi-headphones'],
                    ['name' => 'JBL Headphones', 'slug' => 'jbl-headphones'],
                    ['name' => 'Razer Headphones', 'slug' => 'razer-headphones'],
                    ['name' => 'Headsets', 'slug' => 'headsets'],
                    // Smartwatches (ساعت و مچ بند هوشمند)
                    ['name' => 'Smartwatches', 'slug' => 'smartwatches'],
                    ['name' => 'Samsung Smartwatches', 'slug' => 'samsung-smartwatches'],
                    ['name' => 'Xiaomi Smartwatches', 'slug' => 'xiaomi-smartwatches'],
                    ['name' => 'Smartwatch Bands', 'slug' => 'smartwatch-bands'],
                    ['name' => 'Smartwatch Accessories', 'slug' => 'smartwatch-accessories'],
                    ['name' => 'Apple Watch Accessories', 'slug' => 'apple-watch-accessories'],
                    // Gaming Consoles (کنسول بازی)
                    ['name' => 'PS5', 'slug' => 'ps5'],
                    ['name' => 'PS5 Slim', 'slug' => 'ps5-slim'],
                    ['name' => 'PS5 Pro', 'slug' => 'ps5-pro'],
                    ['name' => 'PS4', 'slug' => 'ps4'],
                    ['name' => 'Xbox', 'slug' => 'xbox'],
                    ['name' => 'GameStick', 'slug' => 'gamestick'],
                    ['name' => 'Nintendo', 'slug' => 'nintendo'],
                    ['name' => 'Gaming Accessories', 'slug' => 'gaming-accessories'],
                    ['name' => 'Gaming Chairs', 'slug' => 'gaming-chairs'],
                    ['name' => 'Game Controllers', 'slug' => 'game-controllers'],
                    ['name' => 'PS5 Games', 'slug' => 'ps5-games'],
                    ['name' => 'PS4 Games', 'slug' => 'ps4-games'],
                    ['name' => 'Xbox 360 Games', 'slug' => 'xbox-360-games'],
                    ['name' => 'PC Games', 'slug' => 'pc-games'],
                    ['name' => 'Game Discs', 'slug' => 'game-discs'],
                    ['name' => 'Racing Wheels', 'slug' => 'racing-wheels'],
                    ['name' => 'Gaming Desks', 'slug' => 'gaming-desks'],
                    ['name' => 'Gift Cards', 'slug' => 'gift-cards-digital'],
                ]
            ],
            // ===== Home & Kitchen =====
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Furniture', 'slug' => 'furniture'],
                    ['name' => 'Lighting', 'slug' => 'lighting'],
                    ['name' => 'Kitchenware', 'slug' => 'kitchenware'],
                    ['name' => 'Decorations', 'slug' => 'decorations'],
                    ['name' => 'Bedding', 'slug' => 'bedding'],
                    ['name' => 'Storage & Organization', 'slug' => 'storage-organization'],
                    ['name' => 'Rugs', 'slug' => 'rugs'],
                    ['name' => 'Curtains', 'slug' => 'curtains'],
                ]
            ],
            // ===== Home Appliances =====
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'sort_order' => 5,
                'children' => [
                    ['name' => 'Refrigerators', 'slug' => 'refrigerators'],
                    ['name' => 'Washing Machines', 'slug' => 'washing-machines'],
                    ['name' => 'Kitchen Appliances', 'slug' => 'kitchen-appliances'],
                    ['name' => 'Cleaning Appliances', 'slug' => 'cleaning-appliances'],
                    ['name' => 'Climate Appliances', 'slug' => 'climate-appliances'],
                    ['name' => 'Air Conditioners', 'slug' => 'air-conditioners'],
                    ['name' => 'Microwaves', 'slug' => 'microwaves'],
                    ['name' => 'Ovens', 'slug' => 'ovens'],
                ]
            ],
            // ===== Beauty & Health =====
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'sort_order' => 6,
                'children' => [
                    ['name' => 'Skin Care', 'slug' => 'skin-care'],
                    ['name' => 'Makeup', 'slug' => 'makeup'],
                    ['name' => 'Perfumes', 'slug' => 'perfumes'],
                    ['name' => 'Hair Care', 'slug' => 'hair-care'],
                    ['name' => 'Health & Medical', 'slug' => 'health-medical'],
                    ['name' => 'Supplements', 'slug' => 'supplements'],
                    ['name' => 'Oral Care', 'slug' => 'oral-care'],
                    ['name' => 'Sunscreen', 'slug' => 'sunscreen'],
                ]
            ],
            // ===== Fashion =====
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'sort_order' => 7,
                'children' => [
                    ['name' => 'Mens Clothing', 'slug' => 'mens-clothing'],
                    ['name' => 'Womens Clothing', 'slug' => 'womens-clothing'],
                    ['name' => 'Childrens Clothing', 'slug' => 'childrens-clothing'],
                    ['name' => 'Shoes', 'slug' => 'shoes'],
                    ['name' => 'Bags & Wallets', 'slug' => 'bags-wallets'],
                    ['name' => 'Accessories', 'slug' => 'accessories'],
                    ['name' => 'Sportswear', 'slug' => 'sportswear'],
                ]
            ],
            // ===== Gold & Jewelry =====
            [
                'name' => 'Gold & Jewelry',
                'slug' => 'gold-jewelry',
                'sort_order' => 8,
                'children' => [
                    ['name' => 'Gold Jewelry', 'slug' => 'gold-jewelry'],
                    ['name' => 'Silver Jewelry', 'slug' => 'silver-jewelry'],
                    ['name' => 'Diamonds & Gems', 'slug' => 'diamonds-gems'],
                    ['name' => 'Watches', 'slug' => 'watches'],
                    ['name' => 'Bracelets', 'slug' => 'bracelets'],
                    ['name' => 'Necklaces', 'slug' => 'necklaces'],
                    ['name' => 'Rings', 'slug' => 'rings'],
                ]
            ],
            // ===== Vehicles =====
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'sort_order' => 9,
                'children' => [
                    ['name' => 'Cars', 'slug' => 'cars'],
                    ['name' => 'Motorcycles', 'slug' => 'motorcycles'],
                    ['name' => 'Car Accessories', 'slug' => 'car-accessories'],
                    ['name' => 'Motorcycle Accessories', 'slug' => 'motorcycle-accessories'],
                    ['name' => 'Electric Vehicles', 'slug' => 'electric-vehicles'],
                    ['name' => 'Spare Parts', 'slug' => 'spare-parts'],
                ]
            ],
            // ===== Health & Medical =====
            [
                'name' => 'Health & Medical',
                'slug' => 'health-medical',
                'sort_order' => 10,
                'children' => [
                    ['name' => 'Medical Equipment', 'slug' => 'medical-equipment'],
                    ['name' => 'Orthopedic', 'slug' => 'orthopedic'],
                    ['name' => 'Supplements Medical', 'slug' => 'supplements-medical'],
                    ['name' => 'Dental Care', 'slug' => 'dental-care'],
                    ['name' => 'First Aid', 'slug' => 'first-aid'],
                    ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment'],
                ]
            ],
            // ===== Tools & Equipment =====
            [
                'name' => 'Tools & Equipment',
                'slug' => 'tools-equipment',
                'sort_order' => 11,
                'children' => [
                    ['name' => 'Power Tools', 'slug' => 'power-tools'],
                    ['name' => 'Hand Tools', 'slug' => 'hand-tools'],
                    ['name' => 'Gardening Tools', 'slug' => 'gardening-tools'],
                    ['name' => 'Safety Equipment', 'slug' => 'safety-equipment'],
                    ['name' => 'Measuring Tools', 'slug' => 'measuring-tools'],
                    ['name' => 'Tool Sets', 'slug' => 'tool-sets'],
                ]
            ],
            // ===== Books & Art =====
            [
                'name' => 'Books & Art',
                'slug' => 'books-art',
                'sort_order' => 12,
                'children' => [
                    ['name' => 'Novels', 'slug' => 'novels'],
                    ['name' => 'Science Books', 'slug' => 'science-books'],
                    ['name' => 'History Books', 'slug' => 'history-books'],
                    ['name' => 'Children Books', 'slug' => 'children-books'],
                    ['name' => 'Art & Painting', 'slug' => 'art-painting'],
                    ['name' => 'Handicrafts', 'slug' => 'handicrafts'],
                    ['name' => 'Poetry', 'slug' => 'poetry'],
                    ['name' => 'Self-Help Books', 'slug' => 'self-help-books'],
                ]
            ],
            // ===== Sports & Travel =====
            [
                'name' => 'Sports & Travel',
                'slug' => 'sports-travel',
                'sort_order' => 13,
                'children' => [
                    ['name' => 'Sports Equipment', 'slug' => 'sports-equipment'],
                    ['name' => 'Sportswear', 'slug' => 'sportswear'],
                    ['name' => 'Travel Equipment', 'slug' => 'travel-equipment'],
                    ['name' => 'Outdoor Sports', 'slug' => 'outdoor-sports'],
                    ['name' => 'Cycling', 'slug' => 'cycling'],
                    ['name' => 'Camping', 'slug' => 'camping'],
                    ['name' => 'Hiking', 'slug' => 'hiking'],
                ]
            ],
            // ===== Gift Cards =====
            [
                'name' => 'Gift Cards',
                'slug' => 'gift-cards',
                'sort_order' => 14,
                'children' => [
                    ['name' => 'Store Gift Cards', 'slug' => 'store-gift-cards'],
                    ['name' => 'Digital Gift Cards', 'slug' => 'digital-gift-cards'],
                    ['name' => 'Virtual Gift Cards', 'slug' => 'virtual-gift-cards'],
                    ['name' => 'Custom Gift Cards', 'slug' => 'custom-gift-cards'],
                ]
            ],
        ];

        $categoryIds = [];

        foreach ($categories as $mainCat) {
            // بررسی وجود دسته‌بندی اصلی
            $mainCategory = DB::table('categories')->where('slug', $mainCat['slug'])->first();
            
            if (!$mainCategory) {
                $mainId = DB::table('categories')->insertGetId([
                    'name' => $mainCat['name'],
                    'slug' => $mainCat['slug'],
                    'sort_order' => $mainCat['sort_order'],
                    'is_active' => 1,
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->info('➕ Created main category: ' . $mainCat['name']);
            } else {
                $mainId = $mainCategory->id;
                DB::table('categories')
                    ->where('id', $mainId)
                    ->update([
                        'sort_order' => $mainCat['sort_order'],
                        'updated_at' => now()
                    ]);
                $this->command->info('⏭️ Skipped existing main category: ' . $mainCat['name']);
            }
            
            $categoryIds[$mainCat['slug']] = $mainId;
        
            foreach ($mainCat['children'] as $child) {
                // بررسی وجود زیردسته
                $childCategory = DB::table('categories')
                    ->where('slug', $child['slug'])
                    ->where('parent_id', $mainId)
                    ->first();
                
                if (!$childCategory) {
                    $childId = DB::table('categories')->insertGetId([
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'sort_order' => 0,
                        'is_active' => 1,
                        'parent_id' => $mainId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $this->command->info('  ➕ Created sub-category: ' . $child['name']);
                } else {
                    $childId = $childCategory->id;
                    $this->command->info('  ⏭️ Skipped existing sub-category: ' . $child['name']);
                }
                
                $categoryIds[$child['slug']] = $childId;
            }
        }

        // ================================================================
        // 2. CREATE BRANDS
        // ================================================================
        $brands = [
            // Tech
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Google', 'slug' => 'google'],
            ['name' => 'OnePlus', 'slug' => 'oneplus'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'Nokia', 'slug' => 'nokia'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'LG', 'slug' => 'lg'],
            // Laptops
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'Asus', 'slug' => 'asus'],
            ['name' => 'Acer', 'slug' => 'acer'],
            ['name' => 'MSI', 'slug' => 'msi'],
            ['name' => 'Razer', 'slug' => 'razer'],
            // Audio & Wearables
            ['name' => 'JBL', 'slug' => 'jbl'],
            ['name' => 'Bose', 'slug' => 'bose'],
            ['name' => 'Anker', 'slug' => 'anker'],
            ['name' => 'Canon', 'slug' => 'canon'],
            ['name' => 'Nikon', 'slug' => 'nikon'],
            ['name' => 'Beats', 'slug' => 'beats'],
            // Fashion
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Puma', 'slug' => 'puma'],
            ['name' => 'Zara', 'slug' => 'zara'],
            ['name' => 'H&M', 'slug' => 'hm'],
            ['name' => 'Levis', 'slug' => 'levis'],
            // Luxury
            ['name' => 'Rolex', 'slug' => 'rolex'],
            ['name' => 'Omega', 'slug' => 'omega'],
            ['name' => 'Seiko', 'slug' => 'seiko'],
            ['name' => 'Citizen', 'slug' => 'citizen'],
            ['name' => 'Tissot', 'slug' => 'tissot'],
            // Vehicles
            ['name' => 'BMW', 'slug' => 'bmw'],
            ['name' => 'Mercedes', 'slug' => 'mercedes'],
            ['name' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'Honda', 'slug' => 'honda'],
            ['name' => 'Hyundai', 'slug' => 'hyundai'],
            ['name' => 'Kia', 'slug' => 'kia'],
            // Home
            ['name' => 'IKEA', 'slug' => 'ikea'],
            ['name' => 'Zara Home', 'slug' => 'zara-home'],
            ['name' => 'Bosch', 'slug' => 'bosch'],
            ['name' => 'Whirlpool', 'slug' => 'whirlpool'],
            ['name' => 'Philips', 'slug' => 'philips'],
            ['name' => 'Kenwood', 'slug' => 'kenwood'],
            // Tools
            ['name' => 'Makita', 'slug' => 'makita'],
            ['name' => 'DeWalt', 'slug' => 'dewalt'],
            ['name' => 'Stanley', 'slug' => 'stanley'],
            ['name' => 'Milwaukee', 'slug' => 'milwaukee'],
            // Beauty
            ['name' => 'Loreal', 'slug' => 'loreal'],
            ['name' => 'Maybelline', 'slug' => 'maybelline'],
            ['name' => 'Nivea', 'slug' => 'nivea'],
            ['name' => 'Dior', 'slug' => 'dior'],
            ['name' => 'Chanel', 'slug' => 'chanel'],
            ['name' => 'Clinique', 'slug' => 'clinique'],
            // Gaming
            ['name' => 'Nintendo', 'slug' => 'nintendo'],
            ['name' => 'PlayStation', 'slug' => 'playstation'],
            ['name' => 'Xbox', 'slug' => 'xbox'],
            ['name' => 'GameStick', 'slug' => 'gamestick'],
        ];

        $brandIds = [];
        foreach ($brands as $brand) {
            $id = DB::table('brands')->insertGetId([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $brandIds[$brand['slug']] = $id;
        }

        // ================================================================
        // 3. PRODUCT DATA (100+ products with all categories)
        // ================================================================
        $productsData = [
            // ==========================================
            // MOBILE PRODUCTS
            // ==========================================
            ['title' => 'iPhone 16 Pro Max', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 35000000, 'max_price' => 45000000, 'weight' => 220],
            ['title' => 'iPhone 16 Pro', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 30000000, 'max_price' => 38000000, 'weight' => 200],
            ['title' => 'iPhone 16', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 25000000, 'max_price' => 32000000, 'weight' => 190],
            ['title' => 'iPhone 15 Pro Max', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 30000000, 'max_price' => 40000000, 'weight' => 220],
            ['title' => 'iPhone 15 Pro', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 200],
            ['title' => 'iPhone 15', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 190],
            ['title' => 'iPhone 14 Pro Max', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 210],
            ['title' => 'iPhone 14', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 190],
            ['title' => 'Galaxy S24 Ultra', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 28000000, 'max_price' => 38000000, 'weight' => 210],
            ['title' => 'Galaxy S24 Plus', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 23000000, 'max_price' => 32000000, 'weight' => 200],
            ['title' => 'Galaxy S24', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 190],
            ['title' => 'Galaxy Z Fold 6', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 35000000, 'max_price' => 45000000, 'weight' => 250],
            ['title' => 'Galaxy Z Flip 6', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 190],
            ['title' => 'Xiaomi 14 Ultra', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 200],
            ['title' => 'Xiaomi 14 Pro', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 190],
            ['title' => 'Xiaomi 14', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi', 'min_price' => 15000000, 'max_price' => 22000000, 'weight' => 180],
            ['title' => 'Redmi Note 13 Pro', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 190],
            ['title' => 'Google Pixel 8 Pro', 'category' => 'Google Pixel', 'brand' => 'Google', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 200],
            ['title' => 'Google Pixel 8', 'category' => 'Google Pixel', 'brand' => 'Google', 'min_price' => 16000000, 'max_price' => 22000000, 'weight' => 190],
            ['title' => 'OnePlus 12', 'category' => 'OnePlus', 'brand' => 'OnePlus', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 200],
            ['title' => 'Huawei P60 Pro', 'category' => 'Huawei', 'brand' => 'Huawei', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 200],
            ['title' => 'Nokia X30', 'category' => 'Nokia', 'brand' => 'Nokia', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 180],
            ['title' => 'Sony Xperia 1 V', 'category' => 'Sony Xperia', 'brand' => 'Sony', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 180],
            ['title' => 'iPhone 13 Pro Max', 'category' => 'Apple Phones', 'brand' => 'Apple', 'min_price' => 20000000, 'max_price' => 28000000, 'weight' => 200],
            ['title' => 'Galaxy S23 Ultra', 'category' => 'Samsung Phones', 'brand' => 'Samsung', 'min_price' => 22000000, 'max_price' => 30000000, 'weight' => 200],

            // ==========================================
            // LAPTOPS
            // ==========================================
            ['title' => 'MacBook Pro M3', 'category' => 'Apple MacBooks', 'brand' => 'Apple', 'min_price' => 40000000, 'max_price' => 60000000, 'weight' => 1500],
            ['title' => 'MacBook Pro M2', 'category' => 'Apple MacBooks', 'brand' => 'Apple', 'min_price' => 35000000, 'max_price' => 50000000, 'weight' => 1400],
            ['title' => 'MacBook Air M3', 'category' => 'Apple MacBooks', 'brand' => 'Apple', 'min_price' => 30000000, 'max_price' => 40000000, 'weight' => 1200],
            ['title' => 'MacBook Air M2', 'category' => 'Apple MacBooks', 'brand' => 'Apple', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1100],
            ['title' => 'ASUS ROG Zephyrus G16', 'category' => 'Gaming Laptops', 'brand' => 'Asus', 'min_price' => 35000000, 'max_price' => 50000000, 'weight' => 2000],
            ['title' => 'MSI Titan GT77', 'category' => 'Gaming Laptops', 'brand' => 'MSI', 'min_price' => 40000000, 'max_price' => 60000000, 'weight' => 2500],
            ['title' => 'Razer Blade 16', 'category' => 'Gaming Laptops', 'brand' => 'Razer', 'min_price' => 45000000, 'max_price' => 65000000, 'weight' => 2200],
            ['title' => 'Acer Predator Helios 16', 'category' => 'Gaming Laptops', 'brand' => 'Acer', 'min_price' => 30000000, 'max_price' => 45000000, 'weight' => 2100],
            ['title' => 'Lenovo Legion Pro 7', 'category' => 'Gaming Laptops', 'brand' => 'Lenovo', 'min_price' => 35000000, 'max_price' => 50000000, 'weight' => 2300],
            ['title' => 'Dell XPS 16', 'category' => 'Business Laptops', 'brand' => 'Dell', 'min_price' => 30000000, 'max_price' => 45000000, 'weight' => 1500],
            ['title' => 'HP Spectre x360', 'category' => 'Business Laptops', 'brand' => 'HP', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1300],
            ['title' => 'Lenovo ThinkPad X1 Carbon', 'category' => 'Business Laptops', 'brand' => 'Lenovo', 'min_price' => 28000000, 'max_price' => 40000000, 'weight' => 1100],
            ['title' => 'ASUS ZenBook 14', 'category' => 'Ultrabooks', 'brand' => 'Asus', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 1000],
            ['title' => 'Acer Swift 5', 'category' => 'Ultrabooks', 'brand' => 'Acer', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 900],
            ['title' => 'LG Gram 17', 'category' => 'Ultrabooks', 'brand' => 'LG', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1300],
            ['title' => 'Acer Aspire 5', 'category' => 'Student Laptops', 'brand' => 'Acer', 'min_price' => 12000000, 'max_price' => 18000000, 'weight' => 1800],
            ['title' => 'HP Pavilion 15', 'category' => 'Student Laptops', 'brand' => 'HP', 'min_price' => 15000000, 'max_price' => 22000000, 'weight' => 1700],
            ['title' => 'Lenovo Ideapad 5', 'category' => 'Student Laptops', 'brand' => 'Lenovo', 'min_price' => 13000000, 'max_price' => 20000000, 'weight' => 1600],

            // ==========================================
            // DIGITAL PRODUCTS - COMPUTER COMPONENTS
            // ==========================================
            ['title' => 'Intel Core i9-14900K', 'category' => 'CPU Processors', 'brand' => 'Asus', 'min_price' => 15000000, 'max_price' => 20000000, 'weight' => 200],
            ['title' => 'Intel Core i7-14700K', 'category' => 'CPU Processors', 'brand' => 'Asus', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 180],
            ['title' => 'AMD Ryzen 9 7950X', 'category' => 'CPU Processors', 'brand' => 'Asus', 'min_price' => 12000000, 'max_price' => 18000000, 'weight' => 190],
            ['title' => 'NVIDIA RTX 4090', 'category' => 'Graphics Cards', 'brand' => 'Asus', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1500],
            ['title' => 'NVIDIA RTX 4080 Super', 'category' => 'Graphics Cards', 'brand' => 'Asus', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 1400],
            ['title' => 'ASUS ROG Motherboard Z790', 'category' => 'Motherboards', 'brand' => 'Asus', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 800],
            ['title' => 'MSI Motherboard B760', 'category' => 'Motherboards', 'brand' => 'MSI', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 700],
            ['title' => 'Corsair Vengeance 32GB RAM', 'category' => 'RAM Memory', 'brand' => 'Asus', 'min_price' => 4000000, 'max_price' => 6000000, 'weight' => 100],
            ['title' => 'G.Skill Trident 16GB RAM', 'category' => 'RAM Memory', 'brand' => 'Samsung', 'min_price' => 2500000, 'max_price' => 4000000, 'weight' => 80],
            ['title' => 'NZXT H7 Flow Case', 'category' => 'Computer Cases', 'brand' => 'Asus', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 6000],
            ['title' => 'Corsair 5000D Case', 'category' => 'Computer Cases', 'brand' => 'Samsung', 'min_price' => 3500000, 'max_price' => 5500000, 'weight' => 6500],
            ['title' => 'Logitech MX Keys', 'category' => 'Keyboards', 'brand' => 'Samsung', 'min_price' => 2000000, 'max_price' => 3000000, 'weight' => 800],
            ['title' => 'Razer BlackWidow V4', 'category' => 'Mechanical Keyboards', 'brand' => 'Razer', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 900],
            ['title' => 'Logitech G502 Hero', 'category' => 'Gaming Mice', 'brand' => 'Samsung', 'min_price' => 1500000, 'max_price' => 2500000, 'weight' => 120],
            ['title' => 'Razer DeathAdder V3', 'category' => 'Gaming Mice', 'brand' => 'Razer', 'min_price' => 1800000, 'max_price' => 2800000, 'weight' => 110],
            ['title' => 'LG Gram 17', 'category' => 'Ultrabooks', 'brand' => 'LG', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1300],

            // ==========================================
            // DIGITAL - HEADPHONES & AUDIO
            // ==========================================
            ['title' => 'Sony WH-1000XM5', 'category' => 'Sony Headphones', 'brand' => 'Sony', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 250],
            ['title' => 'Sony WH-1000XM4', 'category' => 'Sony Headphones', 'brand' => 'Sony', 'min_price' => 6000000, 'max_price' => 9000000, 'weight' => 230],
            ['title' => 'JBL Tune 770NC', 'category' => 'JBL Headphones', 'brand' => 'JBL', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 200],
            ['title' => 'JBL Tune 670NC', 'category' => 'JBL Headphones', 'brand' => 'JBL', 'min_price' => 3500000, 'max_price' => 5500000, 'weight' => 180],
            ['title' => 'Bose QC45', 'category' => 'Wireless Headphones', 'brand' => 'Bose', 'min_price' => 9000000, 'max_price' => 14000000, 'weight' => 230],
            ['title' => 'AirPods Pro 2', 'category' => 'Apple AirPods', 'brand' => 'Apple', 'min_price' => 7000000, 'max_price' => 10000000, 'weight' => 50],
            ['title' => 'AirPods Max', 'category' => 'Apple AirPods', 'brand' => 'Apple', 'min_price' => 12000000, 'max_price' => 18000000, 'weight' => 380],
            ['title' => 'Beats Studio Pro', 'category' => 'Beats Headphones', 'brand' => 'Beats', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 260],
            ['title' => 'Samsung Galaxy Buds 2 Pro', 'category' => 'Samsung Buds', 'brand' => 'Samsung', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 45],
            ['title' => 'Samsung Galaxy Buds FE', 'category' => 'Samsung Buds', 'brand' => 'Samsung', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 40],
            ['title' => 'Xiaomi Buds 3 Pro', 'category' => 'Xiaomi Headphones', 'brand' => 'Xiaomi', 'min_price' => 2000000, 'max_price' => 3500000, 'weight' => 40],
            ['title' => 'Xiaomi Buds 3', 'category' => 'Xiaomi Headphones', 'brand' => 'Xiaomi', 'min_price' => 1200000, 'max_price' => 2000000, 'weight' => 35],
            ['title' => 'Anker Soundcore Q45', 'category' => 'Anker Headphones', 'brand' => 'Anker', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 200],
            ['title' => 'Anker Soundcore Life Q30', 'category' => 'Anker Headphones', 'brand' => 'Anker', 'min_price' => 2000000, 'max_price' => 3500000, 'weight' => 180],
            ['title' => 'Razer Barracuda Pro', 'category' => 'Razer Headphones', 'brand' => 'Razer', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 250],
            ['title' => 'Razer Kraken V3 Pro', 'category' => 'Razer Headphones', 'brand' => 'Razer', 'min_price' => 4000000, 'max_price' => 6000000, 'weight' => 280],

            // ==========================================
            // DIGITAL - SMARTWATCHES
            // ==========================================
            ['title' => 'Apple Watch Ultra 2', 'category' => 'Smartwatches', 'brand' => 'Apple', 'min_price' => 15000000, 'max_price' => 20000000, 'weight' => 60],
            ['title' => 'Apple Watch Series 9', 'category' => 'Smartwatches', 'brand' => 'Apple', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 50],
            ['title' => 'Apple Watch SE 2', 'category' => 'Smartwatches', 'brand' => 'Apple', 'min_price' => 6000000, 'max_price' => 9000000, 'weight' => 45],
            ['title' => 'Samsung Galaxy Watch 6', 'category' => 'Samsung Smartwatches', 'brand' => 'Samsung', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 50],
            ['title' => 'Samsung Galaxy Watch 6 Classic', 'category' => 'Samsung Smartwatches', 'brand' => 'Samsung', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 55],
            ['title' => 'Xiaomi Watch S3', 'category' => 'Xiaomi Smartwatches', 'brand' => 'Xiaomi', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 45],
            ['title' => 'Xiaomi Watch 2', 'category' => 'Xiaomi Smartwatches', 'brand' => 'Xiaomi', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 40],
            ['title' => 'Smartwatch Bands Collection', 'category' => 'Smartwatch Bands', 'brand' => 'Apple', 'min_price' => 500000, 'max_price' => 1500000, 'weight' => 20],

            // ==========================================
            // DIGITAL - GAMING CONSOLES
            // ==========================================
            ['title' => 'PlayStation 5', 'category' => 'PS5', 'brand' => 'PlayStation', 'min_price' => 18000000, 'max_price' => 22000000, 'weight' => 4500],
            ['title' => 'PlayStation 5 Slim', 'category' => 'PS5 Slim', 'brand' => 'PlayStation', 'min_price' => 15000000, 'max_price' => 18000000, 'weight' => 3500],
            ['title' => 'PlayStation 5 Pro', 'category' => 'PS5 Pro', 'brand' => 'PlayStation', 'min_price' => 22000000, 'max_price' => 28000000, 'weight' => 4800],
            ['title' => 'PlayStation 4', 'category' => 'PS4', 'brand' => 'PlayStation', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 3500],
            ['title' => 'Xbox Series X', 'category' => 'Xbox', 'brand' => 'Xbox', 'min_price' => 18000000, 'max_price' => 22000000, 'weight' => 4000],
            ['title' => 'Xbox Series S', 'category' => 'Xbox', 'brand' => 'Xbox', 'min_price' => 10000000, 'max_price' => 14000000, 'weight' => 2500],
            ['title' => 'Nintendo Switch OLED', 'category' => 'Nintendo', 'brand' => 'Nintendo', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 1000],
            ['title' => 'Nintendo Switch Lite', 'category' => 'Nintendo', 'brand' => 'Nintendo', 'min_price' => 6000000, 'max_price' => 9000000, 'weight' => 800],
            ['title' => 'PS5 God of War Bundle', 'category' => 'PS5 Games', 'brand' => 'PlayStation', 'min_price' => 2500000, 'max_price' => 4000000, 'weight' => 100],
            ['title' => 'PS5 Spider-Man 2', 'category' => 'PS5 Games', 'brand' => 'PlayStation', 'min_price' => 2500000, 'max_price' => 4000000, 'weight' => 100],
            ['title' => 'Xbox Forza Horizon 5', 'category' => 'Xbox 360 Games', 'brand' => 'Xbox', 'min_price' => 2000000, 'max_price' => 3500000, 'weight' => 100],
            ['title' => 'Gaming Controller DualSense', 'category' => 'Game Controllers', 'brand' => 'PlayStation', 'min_price' => 2000000, 'max_price' => 3000000, 'weight' => 250],
            ['title' => 'Gaming Controller Xbox Elite', 'category' => 'Game Controllers', 'brand' => 'Xbox', 'min_price' => 2500000, 'max_price' => 4000000, 'weight' => 300],
            ['title' => 'Racing Wheel Logitech G29', 'category' => 'Racing Wheels', 'brand' => 'PlayStation', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 5000],
            ['title' => 'Racing Wheel Thrustmaster T300', 'category' => 'Racing Wheels', 'brand' => 'PlayStation', 'min_price' => 6000000, 'max_price' => 9000000, 'weight' => 5500],

            // ==========================================
            // DIGITAL - PRINTERS
            // ==========================================
            ['title' => 'HP LaserJet Pro MFP', 'category' => 'HP Printers', 'brand' => 'HP', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 8000],
            ['title' => 'HP Deskjet 2755', 'category' => 'HP Printers', 'brand' => 'HP', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 3000],
            ['title' => '3D Printer Creality Ender 3', 'category' => '3D Printers', 'brand' => 'Asus', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 8000],
            ['title' => 'Thermal Printer Brother', 'category' => 'Thermal Printers', 'brand' => 'Samsung', 'min_price' => 4000000, 'max_price' => 7000000, 'weight' => 2000],
            ['title' => 'Epson EcoTank L3110', 'category' => 'Color Printers', 'brand' => 'Samsung', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 4000],
            ['title' => 'Canon ImageClass MF', 'category' => 'Office Machines', 'brand' => 'Canon', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 10000],

            // ==========================================
            // SMART HOME
            // ==========================================
            ['title' => 'Xiaomi Smart Home Hub', 'category' => 'Smart Home', 'brand' => 'Xiaomi', 'min_price' => 2000000, 'max_price' => 3500000, 'weight' => 200],
            ['title' => 'Xiaomi Smart Light Bulb', 'category' => 'Smart Lighting', 'brand' => 'Xiaomi', 'min_price' => 500000, 'max_price' => 1000000, 'weight' => 100],
            ['title' => 'Xiaomi Smart Plug', 'category' => 'Smart Switches', 'brand' => 'Xiaomi', 'min_price' => 300000, 'max_price' => 600000, 'weight' => 80],
            ['title' => 'Google Nest Hub 2', 'category' => 'Smart Home', 'brand' => 'Google', 'min_price' => 5000000, 'max_price' => 8000000, 'weight' => 500],
            ['title' => 'Amazon Echo Show 8', 'category' => 'Smart Home', 'brand' => 'Google', 'min_price' => 4000000, 'max_price' => 6000000, 'weight' => 600],

            // ==========================================
            // HOME & KITCHEN
            // ==========================================
            ['title' => 'Royal Leather Sofa', 'category' => 'Furniture', 'brand' => 'IKEA', 'min_price' => 15000000, 'max_price' => 25000000, 'weight' => 35000],
            ['title' => 'IKEA Lack Table', 'category' => 'Furniture', 'brand' => 'IKEA', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 5000],
            ['title' => 'Modern Chandelier LED', 'category' => 'Lighting', 'brand' => 'IKEA', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 3000],
            ['title' => 'Dining Table Set 6 Chairs', 'category' => 'Furniture', 'brand' => 'IKEA', 'min_price' => 12000000, 'max_price' => 20000000, 'weight' => 30000],
            ['title' => 'Kitchen Knife Set 7 Pieces', 'category' => 'Kitchenware', 'brand' => 'Samsung', 'min_price' => 1000000, 'max_price' => 2000000, 'weight' => 1500],
            ['title' => 'Cookware Set Teflon', 'category' => 'Kitchenware', 'brand' => 'Samsung', 'min_price' => 2000000, 'max_price' => 4000000, 'weight' => 3000],
            ['title' => 'Handmade Persian Rug', 'category' => 'Rugs', 'brand' => 'IKEA', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 8000],
            ['title' => 'Decorative Vase Set', 'category' => 'Decorations', 'brand' => 'IKEA', 'min_price' => 800000, 'max_price' => 1500000, 'weight' => 500],
            ['title' => 'King Size Bed Frame', 'category' => 'Furniture', 'brand' => 'IKEA', 'min_price' => 10000000, 'max_price' => 18000000, 'weight' => 25000],
            ['title' => 'Wardrobe 4 Door Sliding', 'category' => 'Furniture', 'brand' => 'IKEA', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 20000],

            // ==========================================
            // HOME APPLIANCES
            // ==========================================
            ['title' => 'Samsung Refrigerator 4 Door', 'category' => 'Refrigerators', 'brand' => 'Samsung', 'min_price' => 25000000, 'max_price' => 40000000, 'weight' => 80000],
            ['title' => 'LG Refrigerator 3 Door', 'category' => 'Refrigerators', 'brand' => 'LG', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 70000],
            ['title' => 'Bosch Washing Machine 9kg', 'category' => 'Washing Machines', 'brand' => 'Bosch', 'min_price' => 12000000, 'max_price' => 20000000, 'weight' => 65000],
            ['title' => 'Whirlpool Washing Machine', 'category' => 'Washing Machines', 'brand' => 'Whirlpool', 'min_price' => 10000000, 'max_price' => 15000000, 'weight' => 60000],
            ['title' => 'LG Microwave Smart Inverter', 'category' => 'Microwaves', 'brand' => 'LG', 'min_price' => 6000000, 'max_price' => 12000000, 'weight' => 15000],
            ['title' => 'Samsung Microwave 30L', 'category' => 'Microwaves', 'brand' => 'Samsung', 'min_price' => 5000000, 'max_price' => 9000000, 'weight' => 14000],
            ['title' => 'Electric Oven 75L', 'category' => 'Ovens', 'brand' => 'Samsung', 'min_price' => 10000000, 'max_price' => 18000000, 'weight' => 35000],
            ['title' => 'Dishwasher 12 Place Setting', 'category' => 'Kitchen Appliances', 'brand' => 'Bosch', 'min_price' => 15000000, 'max_price' => 25000000, 'weight' => 50000],
            ['title' => 'Robot Vacuum Cleaner', 'category' => 'Cleaning Appliances', 'brand' => 'LG', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 3000],
            ['title' => 'Air Conditioner 24000 BTU', 'category' => 'Air Conditioners', 'brand' => 'LG', 'min_price' => 15000000, 'max_price' => 25000000, 'weight' => 45000],

            // ==========================================
            // BEAUTY & HEALTH
            // ==========================================
            ['title' => 'Anti-Wrinkle Cream', 'category' => 'Skin Care', 'brand' => 'Loreal', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 50],
            ['title' => 'Moisturizer 50ml', 'category' => 'Skin Care', 'brand' => 'Nivea', 'min_price' => 300000, 'max_price' => 700000, 'weight' => 40],
            ['title' => 'Dior Sauvage Perfume', 'category' => 'Perfumes', 'brand' => 'Dior', 'min_price' => 3000000, 'max_price' => 6000000, 'weight' => 100],
            ['title' => 'Chanel No.5 Perfume', 'category' => 'Perfumes', 'brand' => 'Chanel', 'min_price' => 4000000, 'max_price' => 8000000, 'weight' => 120],
            ['title' => 'Shampoo Volume 500ml', 'category' => 'Hair Care', 'brand' => 'Loreal', 'min_price' => 200000, 'max_price' => 500000, 'weight' => 500],
            ['title' => 'Hair Serum 100ml', 'category' => 'Hair Care', 'brand' => 'Loreal', 'min_price' => 300000, 'max_price' => 600000, 'weight' => 100],
            ['title' => 'Sunscreen SPF 50', 'category' => 'Sunscreen', 'brand' => 'Nivea', 'min_price' => 200000, 'max_price' => 400000, 'weight' => 50],
            ['title' => 'Vitamin C Serum 30ml', 'category' => 'Skin Care', 'brand' => 'Clinique', 'min_price' => 800000, 'max_price' => 1500000, 'weight' => 30],
            ['title' => 'Lipstick Matte Red', 'category' => 'Makeup', 'brand' => 'Maybelline', 'min_price' => 150000, 'max_price' => 300000, 'weight' => 20],
            ['title' => 'Foundation Liquid 30ml', 'category' => 'Makeup', 'brand' => 'Maybelline', 'min_price' => 250000, 'max_price' => 500000, 'weight' => 40],

            // ==========================================
            // FASHION
            // ==========================================
            ['title' => 'Men\'s Suit Slim Fit', 'category' => 'Mens Clothing', 'brand' => 'Zara', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 800],
            ['title' => 'Men\'s Shirt White', 'category' => 'Mens Clothing', 'brand' => 'Zara', 'min_price' => 500000, 'max_price' => 1000000, 'weight' => 300],
            ['title' => 'Women\'s Dress Summer', 'category' => 'Womens Clothing', 'brand' => 'Zara', 'min_price' => 1000000, 'max_price' => 3000000, 'weight' => 400],
            ['title' => 'Women\'s Blazer Jacket', 'category' => 'Womens Clothing', 'brand' => 'H&M', 'min_price' => 1500000, 'max_price' => 2500000, 'weight' => 600],
            ['title' => 'Levi\'s Jeans 501', 'category' => 'Mens Clothing', 'brand' => 'Levis', 'min_price' => 800000, 'max_price' => 1500000, 'weight' => 500],
            ['title' => 'Nike Air Max 270', 'category' => 'Shoes', 'brand' => 'Nike', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 400],
            ['title' => 'Adidas Ultraboost 22', 'category' => 'Shoes', 'brand' => 'Adidas', 'min_price' => 2000000, 'max_price' => 3500000, 'weight' => 400],
            ['title' => 'Leather Crossbody Bag', 'category' => 'Bags & Wallets', 'brand' => 'Zara', 'min_price' => 1200000, 'max_price' => 2500000, 'weight' => 500],
            ['title' => 'Men\'s Belt Leather', 'category' => 'Accessories', 'brand' => 'Levis', 'min_price' => 300000, 'max_price' => 600000, 'weight' => 150],
            ['title' => 'Cotton T-Shirt Crew Neck', 'category' => 'Mens Clothing', 'brand' => 'H&M', 'min_price' => 300000, 'max_price' => 800000, 'weight' => 150],

            // ==========================================
            // GOLD & JEWELRY
            // ==========================================
            ['title' => 'Gold Necklace 24K', 'category' => 'Gold Jewelry', 'brand' => 'Tissot', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 10],
            ['title' => 'Gold Ring 22K', 'category' => 'Gold Jewelry', 'brand' => 'Tissot', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 5],
            ['title' => 'Silver Bracelet 925', 'category' => 'Silver Jewelry', 'brand' => 'Seiko', 'min_price' => 1000000, 'max_price' => 2000000, 'weight' => 15],
            ['title' => 'Silver Necklace 925', 'category' => 'Silver Jewelry', 'brand' => 'Seiko', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 20],
            ['title' => 'Diamond Ring 1 Carat', 'category' => 'Diamonds & Gems', 'brand' => 'Rolex', 'min_price' => 10000000, 'max_price' => 20000000, 'weight' => 8],
            ['title' => 'Rolex Submariner', 'category' => 'Watches', 'brand' => 'Rolex', 'min_price' => 8000000, 'max_price' => 15000000, 'weight' => 150],
            ['title' => 'Omega Speedmaster', 'category' => 'Watches', 'brand' => 'Omega', 'min_price' => 6000000, 'max_price' => 10000000, 'weight' => 140],
            ['title' => 'Seiko 5 Sports', 'category' => 'Watches', 'brand' => 'Seiko', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 130],
            ['title' => 'Tissot PRX', 'category' => 'Watches', 'brand' => 'Tissot', 'min_price' => 4000000, 'max_price' => 7000000, 'weight' => 130],
            ['title' => 'Gold Earrings 21K', 'category' => 'Gold Jewelry', 'brand' => 'Tissot', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 6],

            // ==========================================
            // VEHICLES
            // ==========================================
            ['title' => 'BMW 5 Series', 'category' => 'Cars', 'brand' => 'BMW', 'min_price' => 50000000, 'max_price' => 80000000, 'weight' => 1800000],
            ['title' => 'Mercedes E-Class', 'category' => 'Cars', 'brand' => 'Mercedes', 'min_price' => 45000000, 'max_price' => 70000000, 'weight' => 1700000],
            ['title' => 'Toyota Camry 2024', 'category' => 'Cars', 'brand' => 'Toyota', 'min_price' => 30000000, 'max_price' => 45000000, 'weight' => 1600000],
            ['title' => 'Honda Civic 2024', 'category' => 'Cars', 'brand' => 'Honda', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1500000],
            ['title' => 'Hyundai Sonata', 'category' => 'Cars', 'brand' => 'Hyundai', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 1450000],
            ['title' => 'Kia Sportage', 'category' => 'Cars', 'brand' => 'Kia', 'min_price' => 22000000, 'max_price' => 32000000, 'weight' => 1550000],
            ['title' => 'Honda CBR 500R', 'category' => 'Motorcycles', 'brand' => 'Honda', 'min_price' => 15000000, 'max_price' => 22000000, 'weight' => 200000],
            ['title' => 'BMW R 1250 GS', 'category' => 'Motorcycles', 'brand' => 'BMW', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 250000],
            ['title' => 'Car Alloy Rims 18"', 'category' => 'Car Accessories', 'brand' => 'BMW', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 5000],
            ['title' => 'Car Audio System JBL', 'category' => 'Car Accessories', 'brand' => 'JBL', 'min_price' => 3000000, 'max_price' => 8000000, 'weight' => 2000],
            ['title' => 'Motorcycle Helmet Full Face', 'category' => 'Motorcycle Accessories', 'brand' => 'Honda', 'min_price' => 2000000, 'max_price' => 4000000, 'weight' => 1500],

            // ==========================================
            // HEALTH & MEDICAL
            // ==========================================
            ['title' => 'Digital Blood Pressure Monitor', 'category' => 'Medical Equipment', 'brand' => 'Nivea', 'min_price' => 1000000, 'max_price' => 3000000, 'weight' => 300],
            ['title' => 'Digital Thermometer', 'category' => 'Medical Equipment', 'brand' => 'Nivea', 'min_price' => 200000, 'max_price' => 500000, 'weight' => 100],
            ['title' => 'Back Support Belt', 'category' => 'Orthopedic', 'brand' => 'Nivea', 'min_price' => 500000, 'max_price' => 1500000, 'weight' => 200],
            ['title' => 'Knee Support Brace', 'category' => 'Orthopedic', 'brand' => 'Nivea', 'min_price' => 400000, 'max_price' => 1000000, 'weight' => 150],
            ['title' => 'Vitamin D3 1000iu', 'category' => 'Supplements', 'brand' => 'Nivea', 'min_price' => 200000, 'max_price' => 400000, 'weight' => 50],
            ['title' => 'Omega-3 Fish Oil', 'category' => 'Supplements', 'brand' => 'Nivea', 'min_price' => 300000, 'max_price' => 600000, 'weight' => 100],
            ['title' => 'Electric Toothbrush', 'category' => 'Dental Care', 'brand' => 'Nivea', 'min_price' => 800000, 'max_price' => 2000000, 'weight' => 100],
            ['title' => 'First Aid Kit', 'category' => 'First Aid', 'brand' => 'Nivea', 'min_price' => 300000, 'max_price' => 700000, 'weight' => 500],

            // ==========================================
            // TOOLS & EQUIPMENT
            // ==========================================
            ['title' => 'Makita Drill 18V', 'category' => 'Power Tools', 'brand' => 'Makita', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 3000],
            ['title' => 'DeWalt Angle Grinder', 'category' => 'Power Tools', 'brand' => 'DeWalt', 'min_price' => 2500000, 'max_price' => 4500000, 'weight' => 2500],
            ['title' => 'Stanley Screwdriver Set', 'category' => 'Hand Tools', 'brand' => 'Stanley', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 1000],
            ['title' => 'Milwaukee Tool Set 150pc', 'category' => 'Tool Sets', 'brand' => 'Milwaukee', 'min_price' => 1000000, 'max_price' => 3000000, 'weight' => 2000],
            ['title' => 'Gardening Shears', 'category' => 'Gardening Tools', 'brand' => 'DeWalt', 'min_price' => 300000, 'max_price' => 700000, 'weight' => 500],
            ['title' => 'Safety Helmet', 'category' => 'Safety Equipment', 'brand' => 'DeWalt', 'min_price' => 200000, 'max_price' => 500000, 'weight' => 400],
            ['title' => 'Measuring Tape Laser', 'category' => 'Measuring Tools', 'brand' => 'Stanley', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 300],
            ['title' => 'Circular Saw 7"', 'category' => 'Power Tools', 'brand' => 'Makita', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 4000],
            ['title' => 'Impact Driver', 'category' => 'Power Tools', 'brand' => 'DeWalt', 'min_price' => 2500000, 'max_price' => 4000000, 'weight' => 2500],

            // ==========================================
            // BOOKS & ART
            // ==========================================
            ['title' => '1984 George Orwell', 'category' => 'Novels', 'brand' => 'Nokia', 'min_price' => 150000, 'max_price' => 300000, 'weight' => 300],
            ['title' => 'The Metamorphosis Kafka', 'category' => 'Novels', 'brand' => 'Nokia', 'min_price' => 120000, 'max_price' => 250000, 'weight' => 250],
            ['title' => 'History of Civilization', 'category' => 'History Books', 'brand' => 'Nokia', 'min_price' => 500000, 'max_price' => 1000000, 'weight' => 800],
            ['title' => 'Self Help Atomic Habits', 'category' => 'Self-Help Books', 'brand' => 'Nokia', 'min_price' => 200000, 'max_price' => 400000, 'weight' => 350],
            ['title' => 'Calligraphy Art Set', 'category' => 'Art & Painting', 'brand' => 'Nokia', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 500],
            ['title' => 'Handmade Pottery Vase', 'category' => 'Handicrafts', 'brand' => 'Nokia', 'min_price' => 800000, 'max_price' => 1500000, 'weight' => 800],
            ['title' => 'Poetry Collection Rumi', 'category' => 'Poetry', 'brand' => 'Nokia', 'min_price' => 150000, 'max_price' => 300000, 'weight' => 250],
            ['title' => 'Oil Painting Canvas 40x60', 'category' => 'Art & Painting', 'brand' => 'Nokia', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 2000],
            ['title' => 'Children Book Set 5 Stories', 'category' => 'Children Books', 'brand' => 'Nokia', 'min_price' => 200000, 'max_price' => 500000, 'weight' => 600],

            // ==========================================
            // SPORTS & TRAVEL
            // ==========================================
            ['title' => 'Boxing Punching Bag', 'category' => 'Sports Equipment', 'brand' => 'Adidas', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 5000],
            ['title' => 'Mountain Bike XC 29"', 'category' => 'Cycling', 'brand' => 'Nike', 'min_price' => 8000000, 'max_price' => 15000000, 'weight' => 14000],
            ['title' => 'Travel Suitcase 4 Wheels', 'category' => 'Travel Equipment', 'brand' => 'Adidas', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 3000],
            ['title' => 'Camping Tent 4 Person', 'category' => 'Camping', 'brand' => 'Nike', 'min_price' => 3000000, 'max_price' => 6000000, 'weight' => 5000],
            ['title' => 'Hiking Backpack 65L', 'category' => 'Hiking', 'brand' => 'Adidas', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 1500],
            ['title' => 'Fitness Mat Yoga', 'category' => 'Sports Equipment', 'brand' => 'Adidas', 'min_price' => 300000, 'max_price' => 700000, 'weight' => 1000],
            ['title' => 'Sports T-Shirt Dry Fit', 'category' => 'Sportswear', 'brand' => 'Nike', 'min_price' => 200000, 'max_price' => 500000, 'weight' => 150],
            ['title' => 'Running Shoes Adidas', 'category' => 'Sportswear', 'brand' => 'Adidas', 'min_price' => 1200000, 'max_price' => 3000000, 'weight' => 350],
            ['title' => 'Travel Backpack 40L', 'category' => 'Travel Equipment', 'brand' => 'Adidas', 'min_price' => 1000000, 'max_price' => 2000000, 'weight' => 1000],
            ['title' => 'Sleeping Bag -10°C', 'category' => 'Camping', 'brand' => 'Nike', 'min_price' => 1500000, 'max_price' => 2500000, 'weight' => 2000],

            // ==========================================
            // GIFT CARDS
            // ==========================================
            ['title' => 'Digikala Gift Card 200k', 'category' => 'Store Gift Cards', 'brand' => 'Samsung', 'min_price' => 100000, 'max_price' => 200000, 'weight' => 5],
            ['title' => 'Digikala Gift Card 500k', 'category' => 'Store Gift Cards', 'brand' => 'Samsung', 'min_price' => 500000, 'max_price' => 600000, 'weight' => 5],
            ['title' => 'Digikala Gift Card 1M', 'category' => 'Store Gift Cards', 'brand' => 'Samsung', 'min_price' => 1000000, 'max_price' => 1100000, 'weight' => 5],
            ['title' => 'Snapp Gift Card 100k', 'category' => 'Store Gift Cards', 'brand' => 'Samsung', 'min_price' => 100000, 'max_price' => 200000, 'weight' => 5],
            ['title' => 'Alibaba Gift Card', 'category' => 'Store Gift Cards', 'brand' => 'Samsung', 'min_price' => 100000, 'max_price' => 200000, 'weight' => 5],
            ['title' => 'Virtual Gift Card Custom', 'category' => 'Virtual Gift Cards', 'brand' => 'Samsung', 'min_price' => 50000, 'max_price' => 100000, 'weight' => 5],
            ['title' => 'E-Gift Card Email', 'category' => 'Digital Gift Cards', 'brand' => 'Samsung', 'min_price' => 50000, 'max_price' => 100000, 'weight' => 5],
        ];

        $productCount = 0;
        foreach ($productsData as $productData) {
            $category = DB::table('categories')->where('name', $productData['category'])->first();
            if (!$category) continue;

            $brand = DB::table('brands')->where('name', $productData['brand'])->first();
            if (!$brand) {
                $brand = DB::table('brands')->first();
            }

            $title = $productData['title'];
            $price = rand($productData['min_price'], $productData['max_price']);
            $price = round($price / 1000) * 1000;
            $salePrice = $price * rand(8, 9) / 10;
            $salePrice = round($salePrice / 1000) * 1000;

            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
                'slug' => Str::slug($title . '-' . Str::random(4)),
                'short_description' => 'Short description for ' . $title,
                'description' => '<p>Full description for product ' . $title . '</p>',
                'status' => 'active',
                'meta_title' => $title,
                'meta_keywords' => $title . ', buy, shop',
                'meta_description' => 'Buy ' . $title . ' with best price',
                'view_count' => rand(0, 1000),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('category_product')->insert([
                'category_id' => $category->id,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $variantCount = rand(2, 3);
            for ($v = 1; $v <= $variantCount; $v++) {
                $variantPrice = $price * rand(9, 12) / 10;
                $variantPrice = round($variantPrice / 1000) * 1000;
                
                $variantSalePrice = $variantPrice * rand(7, 9) / 10;
                $variantSalePrice = round($variantSalePrice / 1000) * 1000;

                DB::table('product_variants')->insert([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(3),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'price' => $variantPrice,
                    'sale_price' => $variantSalePrice,
                    'stock' => rand(5, 50),
                    'weight' => $productData['weight'] * rand(8, 12) / 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $productCount++;
        }

        $this->command->info('✅ ' . $productCount . ' products created successfully!');
        $this->command->info('📂 Categories: ' . DB::table('categories')->count());
        $this->command->info('🏷️ Brands: ' . DB::table('brands')->count());
    }
}