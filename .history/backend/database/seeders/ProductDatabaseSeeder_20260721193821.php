<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    private function saveChildren($children, $parentId)
    {
        foreach ($children as $child) {
            $baseSlug = $child['slug'];

            $existing = DB::table('categories')->where('slug', $baseSlug)->first();

            if ($existing) {
                $parent = DB::table('categories')->where('id', $parentId)->first();
                $parentSlug = $parent ? $parent->slug : 'sub';
                $finalSlug = $baseSlug . '-' . $parentSlug;

                $counter = 2;
                while (DB::table('categories')->where('slug', $finalSlug)->exists()) {
                    $finalSlug = $baseSlug . '-' . $parentSlug . '-' . $counter;
                    $counter++;
                }
            } else {
                $finalSlug = $baseSlug;
            }

            $childCategory = DB::table('categories')
                ->where('slug', $finalSlug)
                ->where('parent_id', $parentId)
                ->first();

            if (!$childCategory) {
                $childId = DB::table('categories')->insertGetId([
                    'name'       => $child['name'],
                    'slug'       => $finalSlug,
                    'icon_key'   => $child['icon_key'] ?? null,
                    'sort_order' => $child['sort_order'] ?? 0,
                    'is_active'  => 1,
                    'parent_id'  => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info('  ➕ Created: ' . $child['name'] . ' (slug: ' . $finalSlug . ')');
            } else {
                $childId = $childCategory->id;
                $this->command->info('  ⏭️ Skipped: ' . $child['name']);
            }

            if (!empty($child['children'])) {
                $this->saveChildren($child['children'], $childId);
            }
        }
    }

    public function run()
    {
        $this->command->info('🚀 Starting complete product seeding...');

        // ================================================================
        // 1. CATEGORIES
        // ================================================================
        $categories = [
            ['name' => 'Mobile', 'slug' => 'mobile', 'icon_key' => 'mobile', 'sort_order' => 1,
                'children' => [
                    ['name' => 'Select Mobile', 'slug' => 'select-mobile', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Apple Phones', 'slug' => 'apple-phones', 'children' => [
                                ['name' => 'iPhone 16', 'slug' => 'iphone-16'],
                                ['name' => 'iPhone 16 Pro', 'slug' => 'iphone-16-pro'],
                                ['name' => 'iPhone 16 Pro Max', 'slug' => 'iphone-16-pro-max'],
                                ['name' => 'iPhone 15', 'slug' => 'iphone-15'],
                                ['name' => 'iPhone 15 Pro', 'slug' => 'iphone-15-pro'],
                                ['name' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max'],
                                ['name' => 'iPhone 14', 'slug' => 'iphone-14'],
                                ['name' => 'iPhone SE', 'slug' => 'iphone-se'],
                            ]],
                            ['name' => 'Samsung Phones', 'slug' => 'samsung-phones', 'children' => [
                                ['name' => 'Galaxy S24 Ultra', 'slug' => 'galaxy-s24-ultra'],
                                ['name' => 'Galaxy S24 Plus', 'slug' => 'galaxy-s24-plus'],
                                ['name' => 'Galaxy S24', 'slug' => 'galaxy-s24'],
                                ['name' => 'Galaxy Z Fold 6', 'slug' => 'galaxy-z-fold-6'],
                                ['name' => 'Galaxy Z Flip 6', 'slug' => 'galaxy-z-flip-6'],
                                ['name' => 'Galaxy A55', 'slug' => 'galaxy-a55'],
                                ['name' => 'Galaxy A35', 'slug' => 'galaxy-a35'],
                            ]],
                            ['name' => 'Xiaomi Phones', 'slug' => 'xiaomi-phones', 'children' => [
                                ['name' => 'Xiaomi 14 Ultra', 'slug' => 'xiaomi-14-ultra'],
                                ['name' => 'Xiaomi 14 Pro', 'slug' => 'xiaomi-14-pro'],
                                ['name' => 'Xiaomi 14', 'slug' => 'xiaomi-14'],
                                ['name' => 'Redmi Note 13 Pro', 'slug' => 'redmi-note-13-pro'],
                                ['name' => 'Redmi Note 13', 'slug' => 'redmi-note-13'],
                                ['name' => 'Poco X7 Pro', 'slug' => 'poco-x7-pro'],
                            ]],
                            ['name' => 'Other Brands', 'slug' => 'other-brands', 'children' => [
                                ['name' => 'Google Pixel', 'slug' => 'google-pixel'],
                                ['name' => 'OnePlus', 'slug' => 'oneplus'],
                                ['name' => 'Huawei', 'slug' => 'huawei'],
                                ['name' => 'Nokia', 'slug' => 'nokia'],
                                ['name' => 'Sony Xperia', 'slug' => 'sony-xperia'],
                                ['name' => 'Motorola', 'slug' => 'motorola'],
                                ['name' => 'Nothing Phone', 'slug' => 'nothing-phone'],
                                ['name' => 'Realme', 'slug' => 'realme'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Laptops', 'slug' => 'laptops', 'icon_key' => 'laptops', 'sort_order' => 2,
                'children' => [
                    ['name' => 'Select Laptop', 'slug' => 'select-laptop', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Apple MacBooks', 'slug' => 'apple-macbooks', 'children' => [
                                ['name' => 'MacBook Pro M3', 'slug' => 'macbook-pro-m3'],
                                ['name' => 'MacBook Pro M4', 'slug' => 'macbook-pro-m4'],
                                ['name' => 'MacBook Air M3', 'slug' => 'macbook-air-m3'],
                                ['name' => 'MacBook Air M2', 'slug' => 'macbook-air-m2'],
                            ]],
                            ['name' => 'ASUS Laptops', 'slug' => 'asus-laptops', 'children' => [
                                ['name' => 'ASUS ROG Zephyrus', 'slug' => 'asus-rog-zephyrus'],
                                ['name' => 'ASUS TUF Gaming', 'slug' => 'asus-tuf-gaming'],
                            ]],
                            ['name' => 'Lenovo Laptops', 'slug' => 'lenovo-laptops', 'children' => [
                                ['name' => 'Lenovo ThinkPad X1', 'slug' => 'lenovo-thinkpad-x1'],
                                ['name' => 'Lenovo Legion Pro', 'slug' => 'lenovo-legion-pro'],
                            ]],
                            ['name' => 'Gaming Laptops', 'slug' => 'gaming-laptops', 'children' => [
                                ['name' => 'MSI Titan GT77', 'slug' => 'msi-titan-gt77'],
                                ['name' => 'Razer Blade 16', 'slug' => 'razer-blade-16'],
                                ['name' => 'HP Omen', 'slug' => 'hp-omen'],
                                ['name' => 'Dell Alienware', 'slug' => 'dell-alienware'],
                            ]],
                            ['name' => 'Business Laptops', 'slug' => 'business-laptops', 'children' => [
                                ['name' => 'Dell XPS 16', 'slug' => 'dell-xps-16'],
                                ['name' => 'HP Spectre x360', 'slug' => 'hp-spectre-x360'],
                                ['name' => 'LG Gram', 'slug' => 'lg-gram'],
                            ]],
                            ['name' => 'Student Laptops', 'slug' => 'student-laptops', 'children' => [
                                ['name' => 'Acer Aspire 5', 'slug' => 'acer-aspire-5'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Digital Products', 'slug' => 'digital-products', 'icon_key' => 'digital', 'sort_order' => 3,
                'children' => [
                    ['name' => 'Select Digital', 'slug' => 'select-digital', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Gaming Consoles', 'slug' => 'gaming-consoles', 'children' => [
                                ['name' => 'PS5', 'slug' => 'ps5'],
                                ['name' => 'PS5 Slim', 'slug' => 'ps5-slim'],
                                ['name' => 'Xbox Series X', 'slug' => 'xbox-series-x'],
                                ['name' => 'Xbox Series S', 'slug' => 'xbox-series-s'],
                                ['name' => 'Nintendo Switch OLED', 'slug' => 'nintendo-switch-oled'],
                                ['name' => 'Nintendo Switch Lite', 'slug' => 'nintendo-switch-lite'],
                            ]],
                            ['name' => 'Headphones', 'slug' => 'headphones', 'children' => [
                                ['name' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5'],
                                ['name' => 'Apple AirPods Pro 2', 'slug' => 'apple-airpods-pro-2'],
                                ['name' => 'Samsung Galaxy Buds 2 Pro', 'slug' => 'samsung-buds-2-pro'],
                                ['name' => 'Bose QC45', 'slug' => 'bose-qc45'],
                            ]],
                            ['name' => 'Smartwatches', 'slug' => 'smartwatches', 'children' => [
                                ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2'],
                                ['name' => 'Samsung Galaxy Watch 6', 'slug' => 'samsung-watch-6'],
                            ]],
                            ['name' => 'Tablets', 'slug' => 'tablets', 'children' => [
                                ['name' => 'iPad Pro M4', 'slug' => 'ipad-pro-m4'],
                                ['name' => 'Samsung Galaxy Tab S9', 'slug' => 'samsung-tab-s9'],
                            ]],
                            ['name' => 'Speakers', 'slug' => 'speakers', 'children' => [
                                ['name' => 'JBL Charge 5', 'slug' => 'jbl-charge-5'],
                            ]],
                            ['name' => 'Cameras', 'slug' => 'cameras', 'children' => [
                                ['name' => 'Canon EOS R5', 'slug' => 'canon-eos-r5'],
                                ['name' => 'Nikon Z8', 'slug' => 'nikon-z8'],
                            ]],
                            ['name' => 'Power Banks', 'slug' => 'power-banks', 'children' => [
                                ['name' => 'Anker 20000mAh', 'slug' => 'anker-20000mah'],
                            ]],
                            ['name' => 'Computer Components', 'slug' => 'computer-components', 'children' => [
                                ['name' => 'Intel Core i9-14900K', 'slug' => 'intel-core-i9-14900k'],
                                ['name' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090'],
                            ]],
                            ['name' => 'Smart Home', 'slug' => 'smart-home', 'children' => [
                                ['name' => 'Xiaomi Smart Hub', 'slug' => 'xiaomi-smart-hub'],
                                ['name' => 'Google Nest Hub 2', 'slug' => 'google-nest-hub-2'],
                            ]],
                            ['name' => 'Storage Devices', 'slug' => 'storage-devices', 'children' => [
                                ['name' => 'Samsung 1TB SSD', 'slug' => 'samsung-1tb-ssd'],
                            ]],
                            ['name' => 'Networking', 'slug' => 'networking', 'children' => [
                                ['name' => 'TP-Link Router', 'slug' => 'tplink-router'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen', 'icon_key' => 'home-kitchen', 'sort_order' => 4,
                'children' => [
                    ['name' => 'Select Home', 'slug' => 'select-home', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Cookware', 'slug' => 'cookware', 'children' => [
                                ['name' => 'Non-Stick Frying Pan', 'slug' => 'non-stick-frying-pan'],
                                ['name' => 'Pressure Cooker', 'slug' => 'pressure-cooker'],
                            ]],
                            ['name' => 'Tea & Coffee', 'slug' => 'tea-coffee', 'children' => [
                                ['name' => 'Coffee Maker', 'slug' => 'coffee-maker'],
                                ['name' => 'Electric Kettle', 'slug' => 'electric-kettle'],
                            ]],
                            ['name' => 'Furniture', 'slug' => 'furniture', 'children' => [
                                ['name' => 'Sofa Set', 'slug' => 'sofa-set'],
                            ]],
                            ['name' => 'Lighting', 'slug' => 'lighting', 'children' => [
                                ['name' => 'Chandelier', 'slug' => 'chandelier'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Home Appliances', 'slug' => 'home-appliances', 'icon_key' => 'home-appliances', 'sort_order' => 5,
                'children' => [
                    ['name' => 'Select Appliance', 'slug' => 'select-appliance', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Refrigerators', 'slug' => 'refrigerators', 'children' => [
                                ['name' => 'LG Refrigerator', 'slug' => 'lg-refrigerator'],
                                ['name' => 'Samsung Refrigerator', 'slug' => 'samsung-refrigerator'],
                            ]],
                            ['name' => 'Washing Machines', 'slug' => 'washing-machines', 'children' => [
                                ['name' => 'LG Washing Machine', 'slug' => 'lg-washing-machine'],
                            ]],
                            ['name' => 'Dishwashers', 'slug' => 'dishwashers', 'children' => [
                                ['name' => 'Bosch Dishwasher', 'slug' => 'bosch-dishwasher'],
                            ]],
                            ['name' => 'Vacuums', 'slug' => 'vacuums', 'children' => [
                                ['name' => 'Robot Vacuum', 'slug' => 'robot-vacuum'],
                            ]],
                            ['name' => 'Cooking Appliances', 'slug' => 'cooking-appliances', 'children' => [
                                ['name' => 'Air Fryer', 'slug' => 'air-fryer'],
                                ['name' => 'Microwave Oven', 'slug' => 'microwave-oven'],
                            ]],
                            ['name' => 'TVs', 'slug' => 'tvs', 'children' => [
                                ['name' => 'Sony OLED TV', 'slug' => 'sony-oled-tv'],
                                ['name' => 'Samsung QLED TV', 'slug' => 'samsung-qled-tv'],
                                ['name' => 'LG OLED TV', 'slug' => 'lg-oled-tv'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health', 'icon_key' => 'beauty-health', 'sort_order' => 6,
                'children' => [
                    ['name' => 'Select Beauty', 'slug' => 'select-beauty', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Skin Care', 'slug' => 'skin-care', 'children' => [
                                ['name' => 'Moisturizer Cream', 'slug' => 'moisturizer-cream'],
                                ['name' => 'Sunscreen SPF 50', 'slug' => 'sunscreen-spf-50'],
                            ]],
                            ['name' => 'Makeup', 'slug' => 'makeup', 'children' => [
                                ['name' => 'Lipstick', 'slug' => 'lipstick'],
                            ]],
                            ['name' => 'Hair Care', 'slug' => 'hair-care', 'children' => [
                                ['name' => 'Shampoo', 'slug' => 'shampoo'],
                                ['name' => 'Hair Dryer', 'slug' => 'hair-dryer'],
                            ]],
                            ['name' => 'Perfumes', 'slug' => 'perfumes', 'children' => [
                                ['name' => 'Dior Sauvage', 'slug' => 'dior-sauvage'],
                                ['name' => 'Chanel No.5', 'slug' => 'chanel-no-5'],
                            ]],
                            ['name' => 'Oral Care', 'slug' => 'oral-care', 'children' => [
                                ['name' => 'Electric Toothbrush', 'slug' => 'electric-toothbrush'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon_key' => 'fashion', 'sort_order' => 7,
                'children' => [
                    ['name' => 'Select Fashion', 'slug' => 'select-fashion', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'children' => [
                                ['name' => 'Men\'s T-Shirt', 'slug' => 'mens-t-shirt'],
                                ['name' => 'Men\'s Jeans', 'slug' => 'mens-jeans'],
                            ]],
                            ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'children' => [
                                ['name' => 'Women\'s Dress', 'slug' => 'womens-dress'],
                                ['name' => 'Manteau', 'slug' => 'manteau'],
                            ]],
                            ['name' => 'Shoes', 'slug' => 'shoes', 'children' => [
                                ['name' => 'Nike Air Max', 'slug' => 'nike-air-max'],
                                ['name' => 'Adidas Ultraboost', 'slug' => 'adidas-ultraboost'],
                            ]],
                            ['name' => 'Bags & Accessories', 'slug' => 'bags-accessories', 'children' => [
                                ['name' => 'Rolex Watch', 'slug' => 'rolex-watch'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Gold & Jewelry', 'slug' => 'gold-jewelry', 'icon_key' => 'gold-jewelry', 'sort_order' => 8,
                'children' => [
                    ['name' => 'Select Jewelry', 'slug' => 'select-jewelry', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Gold Jewelry', 'slug' => 'gold-jewelry-items', 'children' => [
                                ['name' => 'Gold Necklace', 'slug' => 'gold-necklace'],
                                ['name' => 'Gold Ring', 'slug' => 'gold-ring'],
                            ]],
                            ['name' => 'Silver Jewelry', 'slug' => 'silver-jewelry', 'children' => [
                                ['name' => 'Silver Necklace', 'slug' => 'silver-necklace'],
                            ]],
                            ['name' => 'Diamonds & Gems', 'slug' => 'diamonds-gems', 'children' => [
                                ['name' => 'Diamond Ring', 'slug' => 'diamond-ring'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Vehicles', 'slug' => 'vehicles', 'icon_key' => 'vehicles', 'sort_order' => 9,
                'children' => [
                    ['name' => 'Select Vehicle', 'slug' => 'select-vehicle', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Cars', 'slug' => 'cars', 'children' => [
                                ['name' => 'BMW 5 Series', 'slug' => 'bmw-5-series'],
                                ['name' => 'Mercedes E-Class', 'slug' => 'mercedes-e-class'],
                                ['name' => 'Toyota Camry', 'slug' => 'toyota-camry'],
                                ['name' => 'Honda Civic', 'slug' => 'honda-civic'],
                            ]],
                            ['name' => 'Motorcycles', 'slug' => 'motorcycles', 'children' => [
                                ['name' => 'Honda CBR 500R', 'slug' => 'honda-cbr-500r'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Health & Medical', 'slug' => 'health-medical', 'icon_key' => 'health-medical', 'sort_order' => 10,
                'children' => [
                    ['name' => 'Select Medical', 'slug' => 'select-medical', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Medical Equipment', 'slug' => 'medical-equipment', 'children' => [
                                ['name' => 'Blood Pressure Monitor', 'slug' => 'blood-pressure-monitor'],
                            ]],
                            ['name' => 'Supplements', 'slug' => 'supplements', 'children' => [
                                ['name' => 'Vitamin C', 'slug' => 'vitamin-c'],
                                ['name' => 'Omega-3', 'slug' => 'omega-3'],
                            ]],
                            ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment', 'children' => [
                                ['name' => 'Treadmill', 'slug' => 'treadmill'],
                                ['name' => 'Yoga Mat', 'slug' => 'yoga-mat'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Tools & Equipment', 'slug' => 'tools-equipment', 'icon_key' => 'tools-equipment', 'sort_order' => 11,
                'children' => [
                    ['name' => 'Select Tool', 'slug' => 'select-tool', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Power Tools', 'slug' => 'power-tools', 'children' => [
                                ['name' => 'Makita Drill', 'slug' => 'makita-drill'],
                                ['name' => 'DeWalt Grinder', 'slug' => 'dewalt-grinder'],
                            ]],
                            ['name' => 'Hand Tools', 'slug' => 'hand-tools', 'children' => [
                                ['name' => 'Screwdriver Set', 'slug' => 'screwdriver-set'],
                            ]],
                        ]
                    ],
                ]
            ],
            ['name' => 'Sports & Travel', 'slug' => 'sports-travel', 'icon_key' => 'sports-travel', 'sort_order' => 12,
                'children' => [
                    ['name' => 'Select Sport', 'slug' => 'select-sport', 'sort_order' => 0,
                        'children' => [
                            ['name' => 'Sports Equipment', 'slug' => 'sports-equipment', 'children' => [
                                ['name' => 'Boxing Punching Bag', 'slug' => 'boxing-punching-bag'],
                            ]],
                            ['name' => 'Travel Equipment', 'slug' => 'travel-equipment', 'children' => [
                                ['name' => 'Suitcase 4 Wheels', 'slug' => 'suitcase-4-wheels'],
                            ]],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($categories as $mainCat) {
            $mainCategory = DB::table('categories')->where('slug', $mainCat['slug'])->first();
            if (!$mainCategory) {
                $mainId = DB::table('categories')->insertGetId([
                    'name'       => $mainCat['name'],
                    'slug'       => $mainCat['slug'],
                    'icon_key'   => $mainCat['icon_key'] ?? null,
                    'sort_order' => $mainCat['sort_order'],
                    'is_active'  => 1,
                    'parent_id'  => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info('➕ Created: ' . $mainCat['name']);
            } else {
                $mainId = $mainCategory->id;
                $this->command->info('⏭️ Skipped: ' . $mainCat['name']);
            }

            if (!empty($mainCat['children'])) {
                $this->saveChildren($mainCat['children'], $mainId);
            }
        }

        $this->command->info('✅ All categories created!');

        // ================================================================
        // 2. BRANDS
        // ================================================================
        $this->command->info('🏷️ Creating brands...');

        $brands = [
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Google', 'slug' => 'google'],
            ['name' => 'OnePlus', 'slug' => 'oneplus'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'Nokia', 'slug' => 'nokia'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Motorola', 'slug' => 'motorola'],
            ['name' => 'Nothing', 'slug' => 'nothing'],
            ['name' => 'Realme', 'slug' => 'realme'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'Asus', 'slug' => 'asus'],
            ['name' => 'Acer', 'slug' => 'acer'],
            ['name' => 'MSI', 'slug' => 'msi'],
            ['name' => 'Razer', 'slug' => 'razer'],
            ['name' => 'LG', 'slug' => 'lg'],
            ['name' => 'Bosch', 'slug' => 'bosch'],
            ['name' => 'Philips', 'slug' => 'philips'],
            ['name' => 'IKEA', 'slug' => 'ikea'],
            ['name' => 'Zara', 'slug' => 'zara'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Levis', 'slug' => 'levis'],
            ['name' => 'Rolex', 'slug' => 'rolex'],
            ['name' => 'Seiko', 'slug' => 'seiko'],
            ['name' => 'BMW', 'slug' => 'bmw'],
            ['name' => 'Mercedes', 'slug' => 'mercedes'],
            ['name' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'Honda', 'slug' => 'honda'],
            ['name' => 'Canon', 'slug' => 'canon'],
            ['name' => 'Nikon', 'slug' => 'nikon'],
            ['name' => 'JBL', 'slug' => 'jbl'],
            ['name' => 'Bose', 'slug' => 'bose'],
            ['name' => 'Anker', 'slug' => 'anker'],
            ['name' => 'Makita', 'slug' => 'makita'],
            ['name' => 'DeWalt', 'slug' => 'dewalt'],
            ['name' => 'Stanley', 'slug' => 'stanley'],
            ['name' => 'Loreal', 'slug' => 'loreal'],
            ['name' => 'Maybelline', 'slug' => 'maybelline'],
            ['name' => 'Nivea', 'slug' => 'nivea'],
            ['name' => 'Dior', 'slug' => 'dior'],
            ['name' => 'Chanel', 'slug' => 'chanel'],
            ['name' => 'Nintendo', 'slug' => 'nintendo'],
            ['name' => 'PlayStation', 'slug' => 'playstation'],
            ['name' => 'Xbox', 'slug' => 'xbox'],
            ['name' => 'TP-Link', 'slug' => 'tp-link'],
            ['name' => 'Poco', 'slug' => 'poco'],
            ['name' => 'Intel', 'slug' => 'intel'],
            ['name' => 'NVIDIA', 'slug' => 'nvidia'],
        ];

        foreach ($brands as $brand) {
            if (!DB::table('brands')->where('slug', $brand['slug'])->exists()) {
                DB::table('brands')->insert([
                    'name'       => $brand['name'],
                    'slug'       => $brand['slug'],
                    'is_active'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Brands created!');
        $this->command->info('🔧 Creating attributes and category attributes...');

$this->call(AttributeSeeder::class);

$this->command->info('✅ Attributes created!');

        // ================================================================
        // 3. PRODUCTS
        // ================================================================
        $this->command->info('🔄 Creating products...');

        $productList = [
            // Mobile - Apple
            ['title' => 'iPhone 16',            'slug' => 'iphone-16',            'brand' => 'Apple'],
            ['title' => 'iPhone 16 Pro',         'slug' => 'iphone-16-pro',        'brand' => 'Apple'],
            ['title' => 'iPhone 16 Pro Max',     'slug' => 'iphone-16-pro-max',    'brand' => 'Apple'],
            ['title' => 'iPhone 15',             'slug' => 'iphone-15',            'brand' => 'Apple'],
            ['title' => 'iPhone 15 Pro',         'slug' => 'iphone-15-pro',        'brand' => 'Apple'],
            ['title' => 'iPhone 15 Pro Max',     'slug' => 'iphone-15-pro-max',    'brand' => 'Apple'],
            ['title' => 'iPhone 14',             'slug' => 'iphone-14',            'brand' => 'Apple'],
            ['title' => 'iPhone SE',             'slug' => 'iphone-se',            'brand' => 'Apple'],
            // Mobile - Samsung
            ['title' => 'Galaxy S24 Ultra',      'slug' => 'galaxy-s24-ultra',     'brand' => 'Samsung'],
            ['title' => 'Galaxy S24 Plus',       'slug' => 'galaxy-s24-plus',      'brand' => 'Samsung'],
            ['title' => 'Galaxy S24',            'slug' => 'galaxy-s24',           'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Fold 6',       'slug' => 'galaxy-z-fold-6',      'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Flip 6',       'slug' => 'galaxy-z-flip-6',      'brand' => 'Samsung'],
            ['title' => 'Galaxy A55',            'slug' => 'galaxy-a55',           'brand' => 'Samsung'],
            ['title' => 'Galaxy A35',            'slug' => 'galaxy-a35',           'brand' => 'Samsung'],
            // Mobile - Xiaomi
            ['title' => 'Xiaomi 14 Ultra',       'slug' => 'xiaomi-14-ultra',      'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14 Pro',         'slug' => 'xiaomi-14-pro',        'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14',             'slug' => 'xiaomi-14',            'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13 Pro',     'slug' => 'redmi-note-13-pro',    'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13',         'slug' => 'redmi-note-13',        'brand' => 'Xiaomi'],
            ['title' => 'Poco X7 Pro',           'slug' => 'poco-x7-pro',          'brand' => 'Poco'],
            // Mobile - Others
            ['title' => 'Google Pixel 8 Pro',    'slug' => 'google-pixel',         'brand' => 'Google'],
            ['title' => 'OnePlus 12',            'slug' => 'oneplus',              'brand' => 'OnePlus'],
            ['title' => 'Huawei P60 Pro',        'slug' => 'huawei',               'brand' => 'Huawei'],
            ['title' => 'Nokia X30',             'slug' => 'nokia',                'brand' => 'Nokia'],
            ['title' => 'Sony Xperia 1 V',       'slug' => 'sony-xperia',          'brand' => 'Sony'],
            ['title' => 'Motorola Edge 40',      'slug' => 'motorola',             'brand' => 'Motorola'],
            ['title' => 'Nothing Phone 2',       'slug' => 'nothing-phone',        'brand' => 'Nothing'],
            ['title' => 'Realme GT 3',           'slug' => 'realme',               'brand' => 'Realme'],
            // Laptops
            ['title' => 'MacBook Pro M3',        'slug' => 'macbook-pro-m3',       'brand' => 'Apple'],
            ['title' => 'MacBook Pro M4',        'slug' => 'macbook-pro-m4',       'brand' => 'Apple'],
            ['title' => 'MacBook Air M3',        'slug' => 'macbook-air-m3',       'brand' => 'Apple'],
            ['title' => 'MacBook Air M2',        'slug' => 'macbook-air-m2',       'brand' => 'Apple'],
            ['title' => 'ASUS ROG Zephyrus',     'slug' => 'asus-rog-zephyrus',    'brand' => 'Asus'],
            ['title' => 'ASUS TUF Gaming',       'slug' => 'asus-tuf-gaming',      'brand' => 'Asus'],
            ['title' => 'Lenovo ThinkPad X1',    'slug' => 'lenovo-thinkpad-x1',   'brand' => 'Lenovo'],
            ['title' => 'Lenovo Legion Pro',     'slug' => 'lenovo-legion-pro',    'brand' => 'Lenovo'],
            ['title' => 'MSI Titan GT77',        'slug' => 'msi-titan-gt77',       'brand' => 'MSI'],
            ['title' => 'Razer Blade 16',        'slug' => 'razer-blade-16',       'brand' => 'Razer'],
            ['title' => 'Dell XPS 16',           'slug' => 'dell-xps-16',          'brand' => 'Dell'],
            ['title' => 'Dell Alienware',        'slug' => 'dell-alienware',       'brand' => 'Dell'],
            ['title' => 'HP Spectre x360',       'slug' => 'hp-spectre-x360',      'brand' => 'HP'],
            ['title' => 'HP Omen',               'slug' => 'hp-omen',              'brand' => 'HP'],
            ['title' => 'Acer Aspire 5',         'slug' => 'acer-aspire-5',        'brand' => 'Acer'],
            ['title' => 'LG Gram',               'slug' => 'lg-gram',              'brand' => 'LG'],
            // Digital
            ['title' => 'PS5',                   'slug' => 'ps5',                  'brand' => 'PlayStation'],
            ['title' => 'PS5 Slim',              'slug' => 'ps5-slim',             'brand' => 'PlayStation'],
            ['title' => 'Xbox Series X',         'slug' => 'xbox-series-x',        'brand' => 'Xbox'],
            ['title' => 'Xbox Series S',         'slug' => 'xbox-series-s',        'brand' => 'Xbox'],
            ['title' => 'Nintendo Switch OLED',  'slug' => 'nintendo-switch-oled', 'brand' => 'Nintendo'],
            ['title' => 'Nintendo Switch Lite',  'slug' => 'nintendo-switch-lite', 'brand' => 'Nintendo'],
            ['title' => 'Sony WH-1000XM5',       'slug' => 'sony-wh-1000xm5',     'brand' => 'Sony'],
            ['title' => 'Apple AirPods Pro 2',   'slug' => 'apple-airpods-pro-2',  'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Buds 2', 'slug' => 'samsung-buds-2-pro',   'brand' => 'Samsung'],
            ['title' => 'Apple Watch Ultra 2',   'slug' => 'apple-watch-ultra-2',  'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Watch 6','slug' => 'samsung-watch-6',      'brand' => 'Samsung'],
            ['title' => 'iPad Pro M4',           'slug' => 'ipad-pro-m4',          'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Tab S9', 'slug' => 'samsung-tab-s9',       'brand' => 'Samsung'],
            ['title' => 'JBL Charge 5',          'slug' => 'jbl-charge-5',         'brand' => 'JBL'],
            ['title' => 'Bose QC45',             'slug' => 'bose-qc45',            'brand' => 'Bose'],
            ['title' => 'Canon EOS R5',          'slug' => 'canon-eos-r5',         'brand' => 'Canon'],
            ['title' => 'Nikon Z8',              'slug' => 'nikon-z8',             'brand' => 'Nikon'],
            ['title' => 'Anker 20000mAh',        'slug' => 'anker-20000mah',       'brand' => 'Anker'],
            ['title' => 'Intel Core i9-14900K',  'slug' => 'intel-core-i9-14900k', 'brand' => 'Intel'],
            ['title' => 'NVIDIA RTX 4090',       'slug' => 'nvidia-rtx-4090',      'brand' => 'NVIDIA'],
            ['title' => 'Xiaomi Smart Hub',      'slug' => 'xiaomi-smart-hub',     'brand' => 'Xiaomi'],
            ['title' => 'Google Nest Hub 2',     'slug' => 'google-nest-hub-2',    'brand' => 'Google'],
            ['title' => 'Samsung 1TB SSD',       'slug' => 'samsung-1tb-ssd',      'brand' => 'Samsung'],
            ['title' => 'TP-Link Router',        'slug' => 'tplink-router',        'brand' => 'TP-Link'],
            // Home & Kitchen
            ['title' => 'Non-Stick Frying Pan',  'slug' => 'non-stick-frying-pan', 'brand' => 'IKEA'],
            ['title' => 'Pressure Cooker',       'slug' => 'pressure-cooker',      'brand' => 'IKEA'],
            ['title' => 'Coffee Maker',          'slug' => 'coffee-maker',         'brand' => 'Philips'],
            ['title' => 'Electric Kettle',       'slug' => 'electric-kettle',      'brand' => 'Philips'],
            ['title' => 'Sofa Set',              'slug' => 'sofa-set',             'brand' => 'IKEA'],
            ['title' => 'Chandelier',            'slug' => 'chandelier',           'brand' => 'IKEA'],
            // Home Appliances
            ['title' => 'LG Refrigerator',       'slug' => 'lg-refrigerator',      'brand' => 'LG'],
            ['title' => 'Samsung Refrigerator',  'slug' => 'samsung-refrigerator', 'brand' => 'Samsung'],
            ['title' => 'LG Washing Machine',    'slug' => 'lg-washing-machine',   'brand' => 'LG'],
            ['title' => 'Bosch Dishwasher',      'slug' => 'bosch-dishwasher',     'brand' => 'Bosch'],
            ['title' => 'Robot Vacuum',          'slug' => 'robot-vacuum',         'brand' => 'LG'],
            ['title' => 'Air Fryer',             'slug' => 'air-fryer',            'brand' => 'Philips'],
            ['title' => 'Microwave Oven',        'slug' => 'microwave-oven',       'brand' => 'LG'],
            ['title' => 'Sony OLED TV',          'slug' => 'sony-oled-tv',         'brand' => 'Sony'],
            ['title' => 'Samsung QLED TV',       'slug' => 'samsung-qled-tv',      'brand' => 'Samsung'],
            ['title' => 'LG OLED TV',            'slug' => 'lg-oled-tv',           'brand' => 'LG'],
            // Beauty
            ['title' => 'Moisturizer Cream',     'slug' => 'moisturizer-cream',    'brand' => 'Loreal'],
            ['title' => 'Sunscreen SPF 50',      'slug' => 'sunscreen-spf-50',     'brand' => 'Nivea'],
            ['title' => 'Lipstick',              'slug' => 'lipstick',             'brand' => 'Maybelline'],
            ['title' => 'Shampoo',               'slug' => 'shampoo',              'brand' => 'Loreal'],
            ['title' => 'Hair Dryer',            'slug' => 'hair-dryer',           'brand' => 'Philips'],
            ['title' => 'Dior Sauvage',          'slug' => 'dior-sauvage',         'brand' => 'Dior'],
            ['title' => 'Chanel No.5',           'slug' => 'chanel-no-5',          'brand' => 'Chanel'],
            ['title' => 'Electric Toothbrush',   'slug' => 'electric-toothbrush',  'brand' => 'Philips'],
            // Fashion
            ['title' => 'Men\'s T-Shirt',        'slug' => 'mens-t-shirt',         'brand' => 'Nike'],
            ['title' => 'Men\'s Jeans',          'slug' => 'mens-jeans',           'brand' => 'Levis'],
            ['title' => 'Women\'s Dress',        'slug' => 'womens-dress',         'brand' => 'Zara'],
            ['title' => 'Manteau',               'slug' => 'manteau',              'brand' => 'Zara'],
            ['title' => 'Nike Air Max',          'slug' => 'nike-air-max',         'brand' => 'Nike'],
            ['title' => 'Adidas Ultraboost',     'slug' => 'adidas-ultraboost',    'brand' => 'Adidas'],
            ['title' => 'Rolex Watch',           'slug' => 'rolex-watch',          'brand' => 'Rolex'],
            // Jewelry
            ['title' => 'Gold Necklace',         'slug' => 'gold-necklace',        'brand' => 'Rolex'],
            ['title' => 'Gold Ring',             'slug' => 'gold-ring',            'brand' => 'Rolex'],
            ['title' => 'Diamond Ring',          'slug' => 'diamond-ring',         'brand' => 'Rolex'],
            ['title' => 'Silver Necklace',       'slug' => 'silver-necklace',      'brand' => 'Seiko'],
            // Vehicles
            ['title' => 'BMW 5 Series',          'slug' => 'bmw-5-series',         'brand' => 'BMW'],
            ['title' => 'Mercedes E-Class',      'slug' => 'mercedes-e-class',     'brand' => 'Mercedes'],
            ['title' => 'Toyota Camry',          'slug' => 'toyota-camry',         'brand' => 'Toyota'],
            ['title' => 'Honda Civic',           'slug' => 'honda-civic',          'brand' => 'Honda'],
            ['title' => 'Honda CBR 500R',        'slug' => 'honda-cbr-500r',       'brand' => 'Honda'],
            // Health
            ['title' => 'Blood Pressure Monitor','slug' => 'blood-pressure-monitor','brand' => 'Philips'],
            ['title' => 'Vitamin C',             'slug' => 'vitamin-c',            'brand' => 'Nivea'],
            ['title' => 'Omega-3',               'slug' => 'omega-3',              'brand' => 'Nivea'],
            ['title' => 'Treadmill',             'slug' => 'treadmill',            'brand' => 'Nike'],
            ['title' => 'Yoga Mat',              'slug' => 'yoga-mat',             'brand' => 'Nike'],
            // Tools
            ['title' => 'Makita Drill',          'slug' => 'makita-drill',         'brand' => 'Makita'],
            ['title' => 'DeWalt Grinder',        'slug' => 'dewalt-grinder',       'brand' => 'DeWalt'],
            ['title' => 'Screwdriver Set',       'slug' => 'screwdriver-set',      'brand' => 'Stanley'],
            // Sports
            ['title' => 'Boxing Punching Bag',   'slug' => 'boxing-punching-bag',  'brand' => 'Adidas'],
            ['title' => 'Suitcase 4 Wheels',     'slug' => 'suitcase-4-wheels',    'brand' => 'Adidas'],
        ];

        $productCount = 0;
        $variantCount = 0;

        // رنگ‌های موجود در AttributeSeeder
        $colorNames = [
            'Black', 'White', 'Red', 'Blue', 'Green',
            'Gold', 'Silver', 'Pink', 'Purple', 'Gray', 'Rose Gold',
        ];
        // Pre-load همه داده‌ها
                $this->command->info('📥 Pre-loading attributes...');

                $allAttributes         = DB::table('attributes')->get()->keyBy('slug');
                $allAttributeValues    = DB::table('attribute_values')->get()->groupBy('attribute_id');
                $allCategoryAttributes = DB::table('category_attributes')->get()->groupBy('category_id');
                $colorAttribute        = $allAttributes->get('color');

                $this->command->info('✅ Pre-loading done!');

                $this->command->info('attributes count: ' . DB::table('attributes')->count());
                $this->command->info('attribute_values count: ' . DB::table('attribute_values')->count());
                $this->command->info('category_attributes count: ' . DB::table('category_attributes')->count());

        foreach ($productList as $productData) {
            $category = DB::table('categories')
                ->where('slug', $productData['slug'])
                ->first();

            if (!$category) {
                $this->command->warn("⚠️ Category not found: " . $productData['slug']);
                continue;
            }

            $brand = DB::table('brands')
                ->where('name', $productData['brand'])
                ->first();

            if (!$brand) {
                $this->command->warn("⚠️ Brand not found: " . $productData['brand']);
                continue;
            }

            $title       = $productData['title'];
            $productSlug = Str::slug($title) . '-' . Str::random(6);
            $description = "Premium {$title} with high-quality features. Perfect for everyday use.";

            $productId = DB::table('products')->insertGetId([
                'brand_id'          => $brand->id,
                'title'             => $title,
                'slug'              => $productSlug,
                'short_description' => Str::limit($description, 150),
                'description'       => '<p>' . $description . '</p>',
                'status'            => 'active',
                'meta_title'        => $title . ' | Buy with best price',
                'meta_keywords'     => $title . ', buy, shop, best price, ' . $productData['brand'],
                'meta_description'  => 'Buy ' . $title . ' with best price.',
                'view_count'        => rand(100, 50000),
                'rating'            => rand(30, 50) / 10,
                'sort_order'        => $productCount,
                'is_active'         => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // اتصال به دسته‌بندی‌ها (خودش، Select، و Main)
            $this->attachProductToCategories($productId, $category);

            // ================================================================
            // ✅ ساخت variants با 1 تا 3 رنگ مختلف
            // هر variant = یک رنگ + 10 ویژگی دیگر
            // ================================================================
            $colorCount       = rand(1, 3);
            $shuffledColors   = collect($colorNames)->shuffle()->take($colorCount);
            $isDefault        = true;

            foreach ($shuffledColors as $variantIndex => $colorName) {
                $basePrice = rand(100, 5000);

                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'sku'        => 'SKU-' . $productId . '-' . ($variantIndex + 1) . '-' . Str::random(4),
                    'barcode'    => rand(1000000000000, 9999999999999),
                    'base_price' => $basePrice,
                    'stock'      => rand(5, 50),
                    'weight'     => rand(100, 1000),
                    'is_active'  => 1,
                    'is_default' => $isDefault ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // ✅ attach ویژگی‌ها به این variant
                $this->attachVariantAttributes(
                    $variantId,
                    $colorName,
                    $category->id,
                    $allAttributes,
                    $allAttributeValues,
                    $allCategoryAttributes
                );

                $isDefault = false;
                $variantCount++;
            }

            $productCount++;
            if ($productCount % 10 === 0) {
                $this->command->info('  📦 Created ' . $productCount . ' products...');
            }
        }

        $this->command->info('✅ ' . $productCount . ' products created!');
        $this->command->info('✅ ' . $variantCount . ' variants created!');
    }

    // ================================================================
    // متد اتصال محصول به دسته‌بندی‌ها
    // ================================================================
    private function attachProductToCategories(int $productId, object $category): void
    {
        // 1. دسته خودش
        $this->insertCategoryProduct($productId, $category->id);

        // 2. والد (Select ...)
        if ($category->parent_id) {
            $parent = DB::table('categories')->where('id', $category->parent_id)->first();
            if ($parent) {
                $this->insertCategoryProduct($productId, $parent->id);

                // 3. والد والد (Apple Phones, Samsung Phones, ...)
                if ($parent->parent_id) {
                    $grandParent = DB::table('categories')->where('id', $parent->parent_id)->first();
                    if ($grandParent) {
                        $this->insertCategoryProduct($productId, $grandParent->id);

                        // 4. بالاتر (Select Mobile, ...)
                        if ($grandParent->parent_id) {
                            $greatGrandParent = DB::table('categories')->where('id', $grandParent->parent_id)->first();
                            if ($greatGrandParent) {
                                $this->insertCategoryProduct($productId, $greatGrandParent->id);

                                // 5. Root (Mobile, Laptops, ...)
                                if ($greatGrandParent->parent_id) {
                                    $root = DB::table('categories')->where('id', $greatGrandParent->parent_id)->first();
                                    if ($root) {
                                        $this->insertCategoryProduct($productId, $root->id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function attachVariantAttributes(
        int $variantId,
        string $colorName,
        int $categoryId,
        $allAttributes,
        $allAttributeValues,
        $allCategoryAttributes
    ): void {
        $colorAttr = $allAttributes->get('color');
    
        // 1. یک رنگ
        if ($colorAttr) {
            $colorValues = $allAttributeValues->get($colorAttr->id, collect());
            $colorValue  = $colorValues->firstWhere('value', $colorName);
    
            if ($colorValue) {
                DB::table('product_variant_attribute_values')->insert([
                    'product_variant_id' => $variantId,
                    'attribute_value_id' => $colorValue->id,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        }
    
        // 2. attribute های دسته‌بندی (به جز color)
        $categoryAttrs = $allCategoryAttributes
            ->get($categoryId, collect())
            ->pluck('attribute_id')
            ->reject(fn($id) => $colorAttr && $id == $colorAttr->id)
            ->values()
            ->toArray();
    
        // اگر کمتر از 10 تا بود از عمومی اضافه کن
        if (count($categoryAttrs) < 10) {
            $generalSlugs = [
                'storage', 'ram', 'processor', 'battery',
                'material', 'weight', 'display-size', 'display-type',
                'connectivity', 'battery-life', 'year', 'waterproof',
            ];
    
            foreach ($generalSlugs as $slug) {
                $attr = $allAttributes->get($slug);
                if (!$attr) continue;
                if ($colorAttr && $attr->id == $colorAttr->id) continue;
                if (in_array($attr->id, $categoryAttrs)) continue;
    
                $categoryAttrs[] = $attr->id;
                if (count($categoryAttrs) >= 10) break;
            }
        }
    
        // 3. انتخاب دقیقاً 10 attribute
        shuffle($categoryAttrs);
    
        // اگر هنوز کمتر از 10 تا بود، تکرار کن
        if (empty($categoryAttrs)) {
            $this->command->warn("No attributes found for category_id={$categoryId}, variant_id={$variantId}");
            return;
        }
        
        while (count($categoryAttrs) < 10) {
            $categoryAttrs = array_merge($categoryAttrs, $categoryAttrs);
        }
    
        $usedAttrIds   = [];
        $insertedCount = 0;
    
        foreach ($categoryAttrs as $attrId) {
            if ($insertedCount >= 10) break;
            if (in_array($attrId, $usedAttrIds)) continue;
    
            $values = $allAttributeValues->get($attrId, collect());
            if ($values->isEmpty()) continue;
    
            $value = $values->random();
    
            DB::table('product_variant_attribute_values')->insert([
                'product_variant_id' => $variantId,
                'attribute_value_id' => $value->id,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
    
            $usedAttrIds[] = $attrId;
            $insertedCount++;
        }

        
    }
    private function insertCategoryProduct(int $productId, int $categoryId): void
{
    $exists = DB::table('category_product')
        ->where('category_id', $categoryId)
        ->where('product_id', $productId)
        ->exists();

    if (!$exists) {
        DB::table('category_product')->insert([
            'category_id' => $categoryId,
            'product_id'  => $productId,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Creating attributes and values...');

        // ================================================================
        // 1. ATTRIBUTES
        // ================================================================
        $attributes = [
            ['name' => 'Color',            'slug' => 'color',            'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 1],
            ['name' => 'Size',             'slug' => 'size',             'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 2],
            ['name' => 'Material',         'slug' => 'material',         'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 3],
            ['name' => 'Weight',           'slug' => 'weight',           'type' => 'select', 'unit' => 'g',     'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 4],
            ['name' => 'Year',             'slug' => 'year',             'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 5],
            ['name' => 'Storage',          'slug' => 'storage',          'type' => 'select', 'unit' => 'GB',    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 10],
            ['name' => 'RAM',              'slug' => 'ram',              'type' => 'select', 'unit' => 'GB',    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 11],
            ['name' => 'Processor',        'slug' => 'processor',        'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 12],
            ['name' => 'Battery',          'slug' => 'battery',          'type' => 'select', 'unit' => 'mAh',   'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 13],
            ['name' => 'Display Size',     'slug' => 'display-size',     'type' => 'select', 'unit' => 'inch',  'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 14],
            ['name' => 'Display Type',     'slug' => 'display-type',     'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 15],
            ['name' => 'Water Resistant',  'slug' => 'water-resistant',  'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 16],
            ['name' => 'Camera MP',        'slug' => 'camera-mp',        'type' => 'select', 'unit' => 'MP',    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 17],
            ['name' => 'SIM Type',         'slug' => 'sim-type',         'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 18],
            ['name' => '5G Support',       'slug' => '5g-support',       'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 19],
            ['name' => 'Screen Size',      'slug' => 'screen-size',      'type' => 'select', 'unit' => 'inch',  'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 20],
            ['name' => 'Graphics Card',    'slug' => 'graphics-card',    'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 21],
            ['name' => 'Operating System', 'slug' => 'operating-system', 'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 22],
            ['name' => 'SSD Storage',      'slug' => 'ssd-storage',      'type' => 'select', 'unit' => 'GB',    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 23],
            ['name' => 'CPU Model',        'slug' => 'cpu-model',        'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 24],
            ['name' => 'Refresh Rate',     'slug' => 'refresh-rate',     'type' => 'select', 'unit' => 'Hz',    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 25],
            ['name' => 'Connectivity',     'slug' => 'connectivity',     'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 30],
            ['name' => 'Battery Life',     'slug' => 'battery-life',     'type' => 'select', 'unit' => 'hours', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 31],
            ['name' => 'Noise Cancellation','slug' => 'noise-cancellation','type' => 'select', 'unit' => null,  'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 32],
            ['name' => 'Waterproof',       'slug' => 'waterproof',       'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 33],
            ['name' => 'Bluetooth Version','slug' => 'bluetooth-version','type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 34],
            ['name' => 'Capacity',         'slug' => 'capacity',         'type' => 'select', 'unit' => 'L',     'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 42],
            ['name' => 'Power Usage',      'slug' => 'power-usage',      'type' => 'select', 'unit' => 'W',     'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 43],
            ['name' => 'Clothing Size',    'slug' => 'clothing-size',    'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 50],
            ['name' => 'Fabric Type',      'slug' => 'fabric-type',      'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 51],
            ['name' => 'Season',           'slug' => 'season',           'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 52],
            ['name' => 'Style',            'slug' => 'style',            'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 53],
            ['name' => 'Gender',           'slug' => 'gender',           'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 54],
            ['name' => 'Gold Karat',       'slug' => 'gold-karat',       'type' => 'select', 'unit' => 'K',     'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 60],
            ['name' => 'Metal Type',       'slug' => 'metal-type',       'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 61],
            ['name' => 'Gemstone',         'slug' => 'gemstone',         'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 62],
            ['name' => 'Watch Movement',   'slug' => 'watch-movement',   'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 63],
            ['name' => 'Engine Type',      'slug' => 'engine-type',      'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 70],
            ['name' => 'Transmission',     'slug' => 'transmission',     'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 71],
            ['name' => 'Fuel Type',        'slug' => 'fuel-type',        'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 72],
            ['name' => 'Tool Type',        'slug' => 'tool-type',        'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 80],
            ['name' => 'Power Source',     'slug' => 'power-source',     'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 81],
            ['name' => 'Shoe Size',        'slug' => 'shoe-size',        'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 102],
            ['name' => 'Sport Type',       'slug' => 'sport-type',       'type' => 'select', 'unit' => null,    'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 100],
        ];

        $attributeIds = [];
        foreach ($attributes as $attr) {
            // جلوگیری از duplicate
            $existing = DB::table('attributes')->where('slug', $attr['slug'])->first();
            if ($existing) {
                $attributeIds[$attr['slug']] = $existing->id;
                continue;
            }

            $id = DB::table('attributes')->insertGetId([
                'name'          => $attr['name'],
                'slug'          => $attr['slug'],
                'type'          => $attr['type'],
                'unit'          => $attr['unit'],
                'is_filterable' => $attr['is_filterable'],
                'is_variant'    => $attr['is_variant'],
                'is_required'   => 0,
                'sort_order'    => $attr['sort_order'],
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            $attributeIds[$attr['slug']] = $id;
        }

        $this->command->info('✅ ' . count($attributeIds) . ' attributes created!');

        // ================================================================
        // 2. ATTRIBUTE VALUES
        // ================================================================
        $attributeValues = [
            'color' => [
                ['value' => 'Black',    'slug' => 'black',     'color_code' => '#1a1a1a'],
                ['value' => 'White',    'slug' => 'white',     'color_code' => '#ffffff'],
                ['value' => 'Red',      'slug' => 'red',       'color_code' => '#e74c3c'],
                ['value' => 'Blue',     'slug' => 'blue',      'color_code' => '#3498db'],
                ['value' => 'Green',    'slug' => 'green',     'color_code' => '#2ecc71'],
                ['value' => 'Gold',     'slug' => 'gold',      'color_code' => '#f1c40f'],
                ['value' => 'Silver',   'slug' => 'silver',    'color_code' => '#bdc3c7'],
                ['value' => 'Pink',     'slug' => 'pink',      'color_code' => '#fd79a8'],
                ['value' => 'Purple',   'slug' => 'purple',    'color_code' => '#9b59b6'],
                ['value' => 'Gray',     'slug' => 'gray',      'color_code' => '#95a5a6'],
                ['value' => 'Rose Gold','slug' => 'rose-gold', 'color_code' => '#e8a87c'],
            ],
            'storage' => [
                ['value' => '64GB',  'slug' => '64gb',  'color_code' => null],
                ['value' => '128GB', 'slug' => '128gb', 'color_code' => null],
                ['value' => '256GB', 'slug' => '256gb', 'color_code' => null],
                ['value' => '512GB', 'slug' => '512gb', 'color_code' => null],
                ['value' => '1TB',   'slug' => '1tb',   'color_code' => null],
                ['value' => '2TB',   'slug' => '2tb',   'color_code' => null],
            ],
            'ram' => [
                ['value' => '4GB',  'slug' => '4gb-ram',  'color_code' => null],
                ['value' => '6GB',  'slug' => '6gb-ram',  'color_code' => null],
                ['value' => '8GB',  'slug' => '8gb-ram',  'color_code' => null],
                ['value' => '12GB', 'slug' => '12gb-ram', 'color_code' => null],
                ['value' => '16GB', 'slug' => '16gb-ram', 'color_code' => null],
                ['value' => '32GB', 'slug' => '32gb-ram', 'color_code' => null],
            ],
            'processor' => [
                ['value' => 'Apple A15 Bionic',    'slug' => 'apple-a15',          'color_code' => null],
                ['value' => 'Apple A16 Bionic',    'slug' => 'apple-a16',          'color_code' => null],
                ['value' => 'Apple A17 Pro',        'slug' => 'apple-a17',          'color_code' => null],
                ['value' => 'Apple M1',             'slug' => 'apple-m1',           'color_code' => null],
                ['value' => 'Apple M2',             'slug' => 'apple-m2',           'color_code' => null],
                ['value' => 'Apple M3',             'slug' => 'apple-m3',           'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 1',   'slug' => 'snapdragon-8-gen-1', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 2',   'slug' => 'snapdragon-8-gen-2', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 3',   'slug' => 'snapdragon-8-gen-3', 'color_code' => null],
                ['value' => 'Intel Core i5',        'slug' => 'intel-core-i5',      'color_code' => null],
                ['value' => 'Intel Core i7',        'slug' => 'intel-core-i7',      'color_code' => null],
                ['value' => 'Intel Core i9',        'slug' => 'intel-core-i9',      'color_code' => null],
                ['value' => 'AMD Ryzen 5',          'slug' => 'amd-ryzen-5',        'color_code' => null],
                ['value' => 'AMD Ryzen 7',          'slug' => 'amd-ryzen-7',        'color_code' => null],
                ['value' => 'AMD Ryzen 9',          'slug' => 'amd-ryzen-9',        'color_code' => null],
            ],
            'battery' => [
                ['value' => '2000mAh',  'slug' => '2000mah',  'color_code' => null],
                ['value' => '3000mAh',  'slug' => '3000mah',  'color_code' => null],
                ['value' => '4000mAh',  'slug' => '4000mah',  'color_code' => null],
                ['value' => '5000mAh',  'slug' => '5000mah',  'color_code' => null],
                ['value' => '6000mAh',  'slug' => '6000mah',  'color_code' => null],
                ['value' => '10000mAh', 'slug' => '10000mah', 'color_code' => null],
            ],
            'display-size' => [
                ['value' => '5.5 inch', 'slug' => '5-5-inch', 'color_code' => null],
                ['value' => '6.1 inch', 'slug' => '6-1-inch', 'color_code' => null],
                ['value' => '6.5 inch', 'slug' => '6-5-inch', 'color_code' => null],
                ['value' => '6.7 inch', 'slug' => '6-7-inch', 'color_code' => null],
                ['value' => '7.0 inch', 'slug' => '7-0-inch', 'color_code' => null],
                ['value' => '7.6 inch', 'slug' => '7-6-inch', 'color_code' => null],
            ],
            'display-type' => [
                ['value' => 'AMOLED',           'slug' => 'amoled',           'color_code' => null],
                ['value' => 'OLED',             'slug' => 'oled',             'color_code' => null],
                ['value' => 'LCD',              'slug' => 'lcd',              'color_code' => null],
                ['value' => 'Super Retina XDR', 'slug' => 'super-retina-xdr', 'color_code' => null],
                ['value' => 'IPS LCD',          'slug' => 'ips-lcd',          'color_code' => null],
                ['value' => 'QLED',             'slug' => 'qled',             'color_code' => null],
            ],
            'water-resistant' => [
                ['value' => 'IP67', 'slug' => 'ip67', 'color_code' => null],
                ['value' => 'IP68', 'slug' => 'ip68', 'color_code' => null],
                ['value' => 'IPX4', 'slug' => 'ipx4', 'color_code' => null],
                ['value' => 'No',   'slug' => 'no-water', 'color_code' => null],
            ],
            'camera-mp' => [
                ['value' => '12MP',  'slug' => '12mp',  'color_code' => null],
                ['value' => '48MP',  'slug' => '48mp',  'color_code' => null],
                ['value' => '50MP',  'slug' => '50mp',  'color_code' => null],
                ['value' => '108MP', 'slug' => '108mp', 'color_code' => null],
                ['value' => '200MP', 'slug' => '200mp', 'color_code' => null],
            ],
            'sim-type' => [
                ['value' => 'Single SIM',      'slug' => 'single-sim',    'color_code' => null],
                ['value' => 'Dual SIM',        'slug' => 'dual-sim',      'color_code' => null],
                ['value' => 'eSIM',            'slug' => 'esim',          'color_code' => null],
                ['value' => 'Dual SIM + eSIM', 'slug' => 'dual-sim-esim', 'color_code' => null],
            ],
            '5g-support' => [
                ['value' => 'Yes', 'slug' => 'yes-5g', 'color_code' => null],
                ['value' => 'No',  'slug' => 'no-5g',  'color_code' => null],
            ],
            'screen-size' => [
                ['value' => '13.3 inch', 'slug' => '133-inch', 'color_code' => null],
                ['value' => '14 inch',   'slug' => '14-inch',  'color_code' => null],
                ['value' => '15.6 inch', 'slug' => '156-inch', 'color_code' => null],
                ['value' => '16 inch',   'slug' => '16-inch',  'color_code' => null],
                ['value' => '17.3 inch', 'slug' => '173-inch', 'color_code' => null],
            ],
            'graphics-card' => [
                ['value' => 'Integrated',       'slug' => 'integrated',      'color_code' => null],
                ['value' => 'NVIDIA RTX 3060',  'slug' => 'nvidia-rtx-3060', 'color_code' => null],
                ['value' => 'NVIDIA RTX 3070',  'slug' => 'nvidia-rtx-3070', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4070',  'slug' => 'nvidia-rtx-4070', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4080',  'slug' => 'nvidia-rtx-4080', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4090',  'slug' => 'nvidia-rtx-4090', 'color_code' => null],
                ['value' => 'AMD Radeon RX 7900','slug' => 'amd-radeon-rx-7900','color_code' => null],
            ],
            'operating-system' => [
                ['value' => 'Windows 11', 'slug' => 'windows-11', 'color_code' => null],
                ['value' => 'macOS',      'slug' => 'macos',      'color_code' => null],
                ['value' => 'Linux',      'slug' => 'linux',      'color_code' => null],
                ['value' => 'Android',    'slug' => 'android',    'color_code' => null],
                ['value' => 'iOS',        'slug' => 'ios',        'color_code' => null],
            ],
            'ssd-storage' => [
                ['value' => '256GB', 'slug' => '256gb-ssd', 'color_code' => null],
                ['value' => '512GB', 'slug' => '512gb-ssd', 'color_code' => null],
                ['value' => '1TB',   'slug' => '1tb-ssd',   'color_code' => null],
                ['value' => '2TB',   'slug' => '2tb-ssd',   'color_code' => null],
                ['value' => '4TB',   'slug' => '4tb-ssd',   'color_code' => null],
            ],
            'cpu-model' => [
                ['value' => 'Intel Core i5', 'slug' => 'intel-core-i5-cpu', 'color_code' => null],
                ['value' => 'Intel Core i7', 'slug' => 'intel-core-i7-cpu', 'color_code' => null],
                ['value' => 'Intel Core i9', 'slug' => 'intel-core-i9-cpu', 'color_code' => null],
                ['value' => 'Apple M2',      'slug' => 'apple-m2-cpu',      'color_code' => null],
                ['value' => 'Apple M3',      'slug' => 'apple-m3-cpu',      'color_code' => null],
                ['value' => 'AMD Ryzen 7',   'slug' => 'amd-ryzen-7-cpu',   'color_code' => null],
                ['value' => 'AMD Ryzen 9',   'slug' => 'amd-ryzen-9-cpu',   'color_code' => null],
            ],
            'refresh-rate' => [
                ['value' => '60Hz',  'slug' => '60hz',  'color_code' => null],
                ['value' => '90Hz',  'slug' => '90hz',  'color_code' => null],
                ['value' => '120Hz', 'slug' => '120hz', 'color_code' => null],
                ['value' => '144Hz', 'slug' => '144hz', 'color_code' => null],
                ['value' => '165Hz', 'slug' => '165hz', 'color_code' => null],
                ['value' => '240Hz', 'slug' => '240hz', 'color_code' => null],
            ],
            'connectivity' => [
                ['value' => 'Bluetooth 5.2', 'slug' => 'bluetooth-5-2', 'color_code' => null],
                ['value' => 'Bluetooth 5.3', 'slug' => 'bluetooth-5-3', 'color_code' => null],
                ['value' => 'WiFi 6',        'slug' => 'wifi-6',        'color_code' => null],
                ['value' => 'WiFi 6E',       'slug' => 'wifi-6e',       'color_code' => null],
                ['value' => 'WiFi 7',        'slug' => 'wifi-7',        'color_code' => null],
                ['value' => 'USB-C',         'slug' => 'usb-c',         'color_code' => null],
            ],
            'battery-life' => [
                ['value' => 'Up to 5 hours',  'slug' => 'up-to-5-hours',  'color_code' => null],
                ['value' => 'Up to 10 hours', 'slug' => 'up-to-10-hours', 'color_code' => null],
                ['value' => 'Up to 20 hours', 'slug' => 'up-to-20-hours', 'color_code' => null],
                ['value' => 'Up to 30 hours', 'slug' => 'up-to-30-hours', 'color_code' => null],
            ],
            'noise-cancellation' => [
                ['value' => 'Active ANC',  'slug' => 'active-anc',  'color_code' => null],
                ['value' => 'Hybrid ANC',  'slug' => 'hybrid-anc',  'color_code' => null],
                ['value' => 'Passive',     'slug' => 'passive',     'color_code' => null],
                ['value' => 'No',          'slug' => 'no-nc',       'color_code' => null],
            ],
            'waterproof' => [
                ['value' => 'IPX4', 'slug' => 'ipx4-wp', 'color_code' => null],
                ['value' => 'IPX5', 'slug' => 'ipx5-wp', 'color_code' => null],
                ['value' => 'IPX7', 'slug' => 'ipx7-wp', 'color_code' => null],
                ['value' => 'IPX8', 'slug' => 'ipx8-wp', 'color_code' => null],
                ['value' => 'No',   'slug' => 'no-wp',   'color_code' => null],
            ],
            'bluetooth-version' => [
                ['value' => '5.0', 'slug' => '5-0-bt', 'color_code' => null],
                ['value' => '5.1', 'slug' => '5-1-bt', 'color_code' => null],
                ['value' => '5.2', 'slug' => '5-2-bt', 'color_code' => null],
                ['value' => '5.3', 'slug' => '5-3-bt', 'color_code' => null],
            ],
            'material' => [
                ['value' => 'Aluminum',       'slug' => 'aluminum',       'color_code' => null],
                ['value' => 'Stainless Steel','slug' => 'stainless-steel','color_code' => null],
                ['value' => 'Plastic',        'slug' => 'plastic',        'color_code' => null],
                ['value' => 'Glass',          'slug' => 'glass',          'color_code' => null],
                ['value' => 'Carbon Fiber',   'slug' => 'carbon-fiber',   'color_code' => null],
                ['value' => 'Leather',        'slug' => 'leather',        'color_code' => null],
                ['value' => 'Fabric',         'slug' => 'fabric',         'color_code' => null],
                ['value' => 'Wood',           'slug' => 'wood',           'color_code' => null],
                ['value' => 'Metal',          'slug' => 'metal',          'color_code' => null],
                ['value' => 'Cotton',         'slug' => 'cotton',         'color_code' => null],
                ['value' => 'Polyester',      'slug' => 'polyester',      'color_code' => null],
            ],
            'weight' => [
                ['value' => 'Under 100g',  'slug' => 'under-100g',  'color_code' => null],
                ['value' => '100-200g',    'slug' => '100-200g',    'color_code' => null],
                ['value' => '200-500g',    'slug' => '200-500g',    'color_code' => null],
                ['value' => '500g-1kg',    'slug' => '500g-1kg',    'color_code' => null],
                ['value' => '1-2kg',       'slug' => '1-2kg',       'color_code' => null],
                ['value' => '2-5kg',       'slug' => '2-5kg',       'color_code' => null],
                ['value' => '5kg+',        'slug' => '5kg-plus',    'color_code' => null],
            ],
            'year' => [
                ['value' => '2022', 'slug' => '2022', 'color_code' => null],
                ['value' => '2023', 'slug' => '2023', 'color_code' => null],
                ['value' => '2024', 'slug' => '2024', 'color_code' => null],
                ['value' => '2025', 'slug' => '2025', 'color_code' => null],
            ],
            'capacity' => [
                ['value' => '1L',   'slug' => '1l',   'color_code' => null],
                ['value' => '2L',   'slug' => '2l',   'color_code' => null],
                ['value' => '5L',   'slug' => '5l',   'color_code' => null],
                ['value' => '10L',  'slug' => '10l',  'color_code' => null],
                ['value' => '20L',  'slug' => '20l',  'color_code' => null],
                ['value' => '300L', 'slug' => '300l', 'color_code' => null],
                ['value' => '500L', 'slug' => '500l', 'color_code' => null],
            ],
            'power-usage' => [
                ['value' => '100W',  'slug' => '100w',  'color_code' => null],
                ['value' => '500W',  'slug' => '500w',  'color_code' => null],
                ['value' => '1000W', 'slug' => '1000w', 'color_code' => null],
                ['value' => '1500W', 'slug' => '1500w', 'color_code' => null],
                ['value' => '2000W', 'slug' => '2000w', 'color_code' => null],
            ],
            'clothing-size' => [
                ['value' => 'S',    'slug' => 's-clothing',    'color_code' => null],
                ['value' => 'M',    'slug' => 'm-clothing',    'color_code' => null],
                ['value' => 'L',    'slug' => 'l-clothing',    'color_code' => null],
                ['value' => 'XL',   'slug' => 'xl-clothing',   'color_code' => null],
                ['value' => 'XXL',  'slug' => 'xxl-clothing',  'color_code' => null],
                ['value' => 'XXXL', 'slug' => 'xxxl-clothing', 'color_code' => null],
            ],
            'fabric-type' => [
                ['value' => 'Cotton',    'slug' => 'cotton-fabric',    'color_code' => null],
                ['value' => 'Polyester', 'slug' => 'polyester-fabric', 'color_code' => null],
                ['value' => 'Wool',      'slug' => 'wool-fabric',      'color_code' => null],
                ['value' => 'Silk',      'slug' => 'silk-fabric',      'color_code' => null],
                ['value' => 'Denim',     'slug' => 'denim-fabric',     'color_code' => null],
                ['value' => 'Linen',     'slug' => 'linen',            'color_code' => null],
            ],
            'season' => [
                ['value' => 'Spring',      'slug' => 'spring',      'color_code' => null],
                ['value' => 'Summer',      'slug' => 'summer',      'color_code' => null],
                ['value' => 'Fall',        'slug' => 'fall',        'color_code' => null],
                ['value' => 'Winter',      'slug' => 'winter',      'color_code' => null],
                ['value' => 'All Seasons', 'slug' => 'all-seasons', 'color_code' => null],
            ],
            'style' => [
                ['value' => 'Classic', 'slug' => 'classic-style', 'color_code' => null],
                ['value' => 'Modern',  'slug' => 'modern-style',  'color_code' => null],
                ['value' => 'Sport',   'slug' => 'sport-style',   'color_code' => null],
                ['value' => 'Casual',  'slug' => 'casual-style',  'color_code' => null],
                ['value' => 'Formal',  'slug' => 'formal-style',  'color_code' => null],
                ['value' => 'Luxury',  'slug' => 'luxury-style',  'color_code' => null],
            ],
            'gender' => [
                ['value' => 'Men',    'slug' => 'men',    'color_code' => null],
                ['value' => 'Women',  'slug' => 'women',  'color_code' => null],
                ['value' => 'Unisex', 'slug' => 'unisex', 'color_code' => null],
            ],
            'gold-karat' => [
                ['value' => '18K', 'slug' => '18k', 'color_code' => null],
                ['value' => '21K', 'slug' => '21k', 'color_code' => null],
                ['value' => '22K', 'slug' => '22k', 'color_code' => null],
                ['value' => '24K', 'slug' => '24k', 'color_code' => null],
            ],
            'metal-type' => [
                ['value' => 'Gold',      'slug' => 'gold-metal',      'color_code' => null],
                ['value' => 'Silver',    'slug' => 'silver-metal',    'color_code' => null],
                ['value' => 'Platinum',  'slug' => 'platinum',        'color_code' => null],
                ['value' => 'Rose Gold', 'slug' => 'rose-gold-metal', 'color_code' => null],
                ['value' => 'Titanium',  'slug' => 'titanium',        'color_code' => null],
            ],
            'gemstone' => [
                ['value' => 'Diamond',  'slug' => 'diamond',  'color_code' => null],
                ['value' => 'Ruby',     'slug' => 'ruby',     'color_code' => null],
                ['value' => 'Sapphire', 'slug' => 'sapphire', 'color_code' => null],
                ['value' => 'Emerald',  'slug' => 'emerald',  'color_code' => null],
                ['value' => 'Pearl',    'slug' => 'pearl',    'color_code' => null],
            ],
            'watch-movement' => [
                ['value' => 'Automatic', 'slug' => 'automatic',     'color_code' => null],
                ['value' => 'Quartz',    'slug' => 'quartz-watch',  'color_code' => null],
                ['value' => 'Mechanical','slug' => 'mechanical',    'color_code' => null],
                ['value' => 'Smart',     'slug' => 'smart-movement','color_code' => null],
            ],
            'engine-type' => [
                ['value' => 'Petrol',         'slug' => 'petrol',         'color_code' => null],
                ['value' => 'Diesel',         'slug' => 'diesel',         'color_code' => null],
                ['value' => 'Electric',       'slug' => 'electric',       'color_code' => null],
                ['value' => 'Hybrid',         'slug' => 'hybrid',         'color_code' => null],
                ['value' => 'Plug-in Hybrid', 'slug' => 'plug-in-hybrid', 'color_code' => null],
            ],
            'transmission' => [
                ['value' => 'Manual',      'slug' => 'manual',       'color_code' => null],
                ['value' => 'Automatic',   'slug' => 'automatic-tr', 'color_code' => null],
                ['value' => 'CVT',         'slug' => 'cvt',          'color_code' => null],
                ['value' => 'Dual Clutch', 'slug' => 'dual-clutch',  'color_code' => null],
            ],
            'fuel-type' => [
                ['value' => 'Petrol',   'slug' => 'petrol-fuel',   'color_code' => null],
                ['value' => 'Diesel',   'slug' => 'diesel-fuel',   'color_code' => null],
                ['value' => 'Electric', 'slug' => 'electric-fuel', 'color_code' => null],
                ['value' => 'Hybrid',   'slug' => 'hybrid-fuel',   'color_code' => null],
                ['value' => 'CNG',      'slug' => 'cng',           'color_code' => null],
            ],
            'tool-type' => [
                ['value' => 'Drill',       'slug' => 'drill',       'color_code' => null],
                ['value' => 'Grinder',     'slug' => 'grinder',     'color_code' => null],
                ['value' => 'Screwdriver', 'slug' => 'screwdriver', 'color_code' => null],
                ['value' => 'Hammer',      'slug' => 'hammer',      'color_code' => null],
                ['value' => 'Saw',         'slug' => 'saw',         'color_code' => null],
            ],
            'power-source' => [
                ['value' => 'Battery',  'slug' => 'battery-ps',  'color_code' => null],
                ['value' => 'Electric', 'slug' => 'electric-ps', 'color_code' => null],
                ['value' => 'Manual',   'slug' => 'manual-ps',   'color_code' => null],
                ['value' => 'Gas',      'slug' => 'gas',         'color_code' => null],
            ],
            'shoe-size' => [
                ['value' => '38', 'slug' => '38-shoe', 'color_code' => null],
                ['value' => '39', 'slug' => '39-shoe', 'color_code' => null],
                ['value' => '40', 'slug' => '40-shoe', 'color_code' => null],
                ['value' => '41', 'slug' => '41-shoe', 'color_code' => null],
                ['value' => '42', 'slug' => '42-shoe', 'color_code' => null],
                ['value' => '43', 'slug' => '43-shoe', 'color_code' => null],
                ['value' => '44', 'slug' => '44-shoe', 'color_code' => null],
                ['value' => '45', 'slug' => '45-shoe', 'color_code' => null],
            ],
            'sport-type' => [
                ['value' => 'Running',  'slug' => 'running',  'color_code' => null],
                ['value' => 'Gym',      'slug' => 'gym',      'color_code' => null],
                ['value' => 'Boxing',   'slug' => 'boxing',   'color_code' => null],
                ['value' => 'Yoga',     'slug' => 'yoga',     'color_code' => null],
                ['value' => 'Cycling',  'slug' => 'cycling',  'color_code' => null],
                ['value' => 'Swimming', 'slug' => 'swimming', 'color_code' => null],
                ['value' => 'Hiking',   'slug' => 'hiking',   'color_code' => null],
            ],
            'size' => [
                ['value' => 'S',    'slug' => 's',    'color_code' => null],
                ['value' => 'M',    'slug' => 'm',    'color_code' => null],
                ['value' => 'L',    'slug' => 'l',    'color_code' => null],
                ['value' => 'XL',   'slug' => 'xl',   'color_code' => null],
                ['value' => 'XXL',  'slug' => 'xxl',  'color_code' => null],
            ],
        ];

        $totalValues = 0;
        foreach ($attributeValues as $attrSlug => $values) {
            $attrId = $attributeIds[$attrSlug] ?? null;
            if (!$attrId) continue;

            $sortOrder = 1;
            foreach ($values as $item) {
                $exists = DB::table('attribute_values')
                    ->where('attribute_id', $attrId)
                    ->where('slug', $item['slug'])
                    ->exists();

                if (!$exists) {
                    DB::table('attribute_values')->insert([
                        'attribute_id' => $attrId,
                        'value'        => $item['value'],
                        'slug'         => $item['slug'],
                        'color_code'   => $item['color_code'],
                        'image'        => null,
                        'sort_order'   => $sortOrder,
                        'is_active'    => 1,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                    $totalValues++;
                }
                $sortOrder++;
            }
        }

        $this->command->info('✅ ' . $totalValues . ' attribute values created!');

        // ================================================================
        // 3. LINK ATTRIBUTES TO CATEGORIES
        // ================================================================
        $this->command->info('🔗 Linking attributes to categories...');

        $categories   = DB::table('categories')->get();
        $allAttrs     = DB::table('attributes')->get()->keyBy('slug');

        // map از نام دسته به slug های attribute
        $categoryAttributeMap = [
            // Mobile
            'Mobile'             => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', 'sim-type', '5g-support', 'year'],
            'Select Mobile'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', '5g-support', 'camera-mp', 'sim-type', 'water-resistant', 'year'],
            'Apple Phones'       => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Samsung Phones'     => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Xiaomi Phones'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Other Brands'       => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type'],
            'iPhone 16'          => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 16 Pro'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 16 Pro Max'  => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 15'          => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 15 Pro'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 15 Pro Max'  => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone 14'          => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'iPhone SE'          => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy S24 Ultra'   => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy S24 Plus'    => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy S24'         => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy Z Fold 6'    => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy Z Flip 6'    => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy A55'         => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Galaxy A35'         => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Xiaomi 14 Ultra'    => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Xiaomi 14 Pro'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Xiaomi 14'          => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Redmi Note 13 Pro'  => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Redmi Note 13'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Poco X7 Pro'        => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Google Pixel'       => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'OnePlus'            => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Huawei'             => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Nokia'              => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'sim-type', '5g-support', 'year'],
            'Sony Xperia'        => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support', 'sim-type', 'year'],
            'Motorola'           => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', '5g-support', 'sim-type', 'year'],
            'Nothing Phone'      => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', '5g-support', 'sim-type', 'year'],
            'Realme'             => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', '5g-support', 'sim-type', 'year'],

            // Laptops
            'Laptops'            => ['color', 'ram', 'processor', 'screen-size', 'graphics-card', 'operating-system', 'ssd-storage', 'cpu-model', 'refresh-rate', 'weight', 'year'],
            'Select Laptop'      => ['color', 'ram', 'processor', 'screen-size', 'graphics-card', 'operating-system', 'ssd-storage', 'cpu-model', 'refresh-rate', 'weight', 'year'],
            'Apple MacBooks'     => ['color', 'ram', 'processor', 'screen-size', 'operating-system', 'ssd-storage', 'cpu-model', 'weight', 'battery-life', 'year'],
            'ASUS Laptops'       => ['color', 'ram', 'processor', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model', 'refresh-rate', 'weight', 'year'],
            'Lenovo Laptops'     => ['color', 'ram', 'processor', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model', 'weight', 'year'],
            'Gaming Laptops'     => ['color', 'ram', 'processor', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model', 'refresh-rate', 'weight', 'year'],
            'Business Laptops'   => ['color', 'ram', 'processor', 'screen-size', 'operating-system', 'ssd-storage', 'cpu-model', 'weight', 'battery-life', 'year'],
            'Student Laptops'    => ['color', 'ram', 'processor', 'screen-size', 'ssd-storage', 'cpu-model', 'weight', 'year'],
            'MacBook Pro M3'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'battery-life', 'weight', 'refresh-rate', 'year'],
            'MacBook Pro M4'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'battery-life', 'weight', 'refresh-rate', 'year'],
            'MacBook Air M3'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'battery-life', 'weight', 'refresh-rate', 'year'],
            'MacBook Air M2'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'battery-life', 'weight', 'refresh-rate', 'year'],
            'ASUS ROG Zephyrus'  => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'ASUS TUF Gaming'    => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'Lenovo ThinkPad X1' => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'weight', 'battery-life', 'year'],
            'Lenovo Legion Pro'  => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'MSI Titan GT77'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'Razer Blade 16'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'Dell XPS 16'        => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'weight', 'battery-life', 'year'],
            'Dell Alienware'     => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'HP Spectre x360'    => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'weight', 'battery-life', 'year'],
            'HP Omen'            => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'graphics-card', 'refresh-rate', 'weight', 'operating-system', 'year'],
            'Acer Aspire 5'      => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'weight', 'year'],
            'LG Gram'            => ['color', 'ram', 'cpu-model', 'ssd-storage', 'screen-size', 'operating-system', 'weight', 'battery-life', 'year'],

            // Digital
            'Digital Products'   => ['color', 'connectivity', 'battery-life', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Select Digital'     => ['color', 'connectivity', 'battery-life', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Gaming Consoles'    => ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Headphones'         => ['color', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Smartwatches'       => ['color', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version', 'weight', 'year'],
            'Tablets'            => ['color', 'storage', 'ram', 'screen-size', 'connectivity', 'weight', 'year'],
            'Speakers'           => ['color', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version', 'weight', 'year'],
            'Cameras'            => ['color', 'camera-mp', 'battery', 'weight', 'connectivity', 'year'],
            'Power Banks'        => ['color', 'battery', 'connectivity', 'weight', 'year'],
            'Computer Components'=> ['processor', 'ram', 'weight', 'year'],
            'Smart Home'         => ['color', 'connectivity', 'waterproof', 'weight', 'year'],
            'Storage Devices'    => ['storage', 'connectivity', 'weight', 'year'],
            'Networking'         => ['connectivity', 'weight', 'year'],
            'PS5'                => ['color', 'storage', 'connectivity', 'weight', 'year'],
            'PS5 Slim'           => ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Xbox Series X'      => ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Xbox Series S'      => ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Nintendo Switch OLED'=> ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Nintendo Switch Lite'=> ['color', 'storage', 'connectivity', 'weight', 'year'],
            'Sony WH-1000XM5'   => ['color', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Apple AirPods Pro 2'=> ['color', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Samsung Galaxy Buds 2 Pro'=> ['color', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version', 'waterproof', 'weight', 'year'],
            'Bose QC45'          => ['color', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version', 'weight', 'year'],
            'Apple Watch Ultra 2'=> ['color', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version', 'weight', 'year'],
            'Samsung Galaxy Watch 6'=> ['color', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version', 'weight', 'year'],
            'iPad Pro M4'        => ['color', 'storage', 'ram', 'screen-size', 'connectivity', 'weight', 'year'],
            'Samsung Galaxy Tab S9'=> ['color', 'storage', 'ram', 'screen-size', 'connectivity', 'weight', 'year'],
            'JBL Charge 5'       => ['color', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version', 'weight', 'year'],
            'Canon EOS R5'       => ['color', 'camera-mp', 'battery', 'weight', 'connectivity', 'year'],
            'Nikon Z8'           => ['color', 'camera-mp', 'battery', 'weight', 'connectivity', 'year'],
            'Anker 20000mAh'     => ['color', 'battery', 'connectivity', 'weight', 'year'],
            'Intel Core i9-14900K'=> ['processor', 'year'],
            'NVIDIA RTX 4090'    => ['graphics-card', 'year'],
            'Xiaomi Smart Hub'   => ['color', 'connectivity', 'weight', 'year'],
            'Google Nest Hub 2'  => ['color', 'connectivity', 'weight', 'year'],
            'Samsung 1TB SSD'    => ['storage', 'connectivity', 'weight', 'year'],
            'TP-Link Router'     => ['connectivity', 'weight', 'year'],

            // Home
            'Home & Kitchen'     => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Select Home'        => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Cookware'           => ['color', 'material', 'capacity', 'weight'],
            'Tea & Coffee'       => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Furniture'          => ['color', 'material', 'weight', 'size'],
            'Lighting'           => ['color', 'material', 'power-usage', 'weight'],
            'Non-Stick Frying Pan'=> ['color', 'material', 'capacity', 'weight', 'size'],
            'Pressure Cooker'    => ['color', 'material', 'capacity', 'weight'],
            'Coffee Maker'       => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Electric Kettle'    => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Sofa Set'           => ['color', 'material', 'weight', 'size', 'style'],
            'Chandelier'         => ['color', 'material', 'power-usage', 'weight'],

            // Home Appliances
            'Home Appliances'    => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Select Appliance'   => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Refrigerators'      => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Washing Machines'   => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Dishwashers'        => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Vacuums'            => ['color', 'material', 'power-usage', 'weight', 'battery-life'],
            'Cooking Appliances' => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'TVs'                => ['color', 'screen-size', 'display-type', 'refresh-rate', 'connectivity', 'weight', 'year'],
            'LG Refrigerator'    => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Samsung Refrigerator'=> ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'LG Washing Machine' => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Bosch Dishwasher'   => ['color', 'material', 'capacity', 'power-usage', 'weight', 'year'],
            'Robot Vacuum'       => ['color', 'material', 'power-usage', 'weight', 'battery-life', 'connectivity'],
            'Air Fryer'          => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Microwave Oven'     => ['color', 'material', 'capacity', 'power-usage', 'weight'],
            'Sony OLED TV'       => ['color', 'screen-size', 'display-type', 'refresh-rate', 'connectivity', 'weight', 'year'],
            'Samsung QLED TV'    => ['color', 'screen-size', 'display-type', 'refresh-rate', 'connectivity', 'weight', 'year'],
            'LG OLED TV'         => ['color', 'screen-size', 'display-type', 'refresh-rate', 'connectivity', 'weight', 'year'],

            // Beauty
            'Beauty & Health'    => ['color', 'weight', 'year'],
            'Select Beauty'      => ['color', 'weight', 'year'],
            'Skin Care'          => ['weight', 'year'],
            'Makeup'             => ['color', 'weight'],
            'Hair Care'          => ['weight', 'year'],
            'Perfumes'           => ['weight', 'year'],
            'Oral Care'          => ['color', 'weight'],
            'Moisturizer Cream'  => ['weight', 'year'],
            'Sunscreen SPF 50'   => ['weight', 'year'],
            'Lipstick'           => ['color', 'weight'],
            'Shampoo'            => ['weight', 'year'],
            'Hair Dryer'         => ['color', 'power-usage', 'weight'],
            'Dior Sauvage'       => ['weight', 'year'],
            'Chanel No.5'        => ['weight', 'year'],
            'Electric Toothbrush'=> ['color', 'power-usage', 'weight'],

            // Fashion
            'Fashion'            => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender'],
            'Select Fashion'     => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender'],
            'Men\'s Clothing'    => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender'],
            'Women\'s Clothing'  => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender'],
            'Shoes'              => ['color', 'shoe-size', 'material', 'gender'],
            'Bags & Accessories' => ['color', 'material', 'gender', 'style'],
            'Men\'s T-Shirt'     => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender', 'year'],
            'Men\'s Jeans'       => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender', 'year'],
            'Women\'s Dress'     => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender', 'year'],
            'Manteau'            => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender', 'year'],
            'Nike Air Max'       => ['color', 'shoe-size', 'material', 'gender', 'sport-type', 'year'],
            'Adidas Ultraboost'  => ['color', 'shoe-size', 'material', 'gender', 'sport-type', 'year'],
            'Rolex Watch'        => ['color', 'material', 'watch-movement', 'water-resistant', 'weight', 'year'],

            // Jewelry
            'Gold & Jewelry'     => ['gold-karat', 'metal-type', 'gemstone', 'weight', 'year'],
            'Select Jewelry'     => ['gold-karat', 'metal-type', 'gemstone', 'weight', 'year'],
            'Gold Jewelry'       => ['gold-karat', 'metal-type', 'weight', 'year'],
            'Silver Jewelry'     => ['metal-type', 'weight', 'year'],
            'Diamonds & Gems'    => ['gold-karat', 'metal-type', 'gemstone', 'weight', 'year'],
            'Gold Necklace'      => ['gold-karat', 'metal-type', 'weight', 'year'],
            'Gold Ring'          => ['gold-karat', 'metal-type', 'weight', 'year'],
            'Silver Necklace'    => ['metal-type', 'weight', 'year'],
            'Diamond Ring'       => ['gold-karat', 'metal-type', 'gemstone', 'weight', 'year'],

            // Vehicles
            'Vehicles'           => ['engine-type', 'transmission', 'fuel-type', 'color', 'weight', 'year'],
            'Select Vehicle'     => ['engine-type', 'transmission', 'fuel-type', 'color', 'year'],
            'Cars'               => ['engine-type', 'transmission', 'fuel-type', 'color', 'year'],
            'Motorcycles'        => ['engine-type', 'transmission', 'fuel-type', 'color', 'year'],
            'BMW 5 Series'       => ['color', 'engine-type', 'transmission', 'fuel-type', 'year'],
            'Mercedes E-Class'   => ['color', 'engine-type', 'transmission', 'fuel-type', 'year'],
            'Toyota Camry'       => ['color', 'engine-type', 'transmission', 'fuel-type', 'year'],
            'Honda Civic'        => ['color', 'engine-type', 'transmission', 'fuel-type', 'year'],
            'Honda CBR 500R'     => ['color', 'engine-type', 'transmission', 'fuel-type', 'year'],

            // Health
            'Health & Medical'   => ['weight', 'year', 'material'],
            'Select Medical'     => ['weight', 'year'],
            'Medical Equipment'  => ['weight', 'power-usage', 'year'],
            'Supplements'        => ['weight', 'year'],
            'Fitness Equipment'  => ['material', 'weight', 'power-usage', 'year'],
            'Blood Pressure Monitor'=> ['weight', 'power-usage', 'year'],
            'Vitamin C'          => ['weight', 'year'],
            'Omega-3'            => ['weight', 'year'],
            'Treadmill'          => ['material', 'weight', 'power-usage', 'year'],
            'Yoga Mat'           => ['material', 'weight', 'size', 'year'],

            // Tools
            'Tools & Equipment'  => ['tool-type', 'power-source', 'material', 'weight', 'year'],
            'Select Tool'        => ['tool-type', 'power-source', 'material', 'weight'],
            'Power Tools'        => ['tool-type', 'power-source', 'weight', 'year'],
            'Hand Tools'         => ['tool-type', 'material', 'weight'],
            'Makita Drill'       => ['color', 'tool-type', 'power-source', 'weight', 'year'],
            'DeWalt Grinder'     => ['color', 'tool-type', 'power-source', 'weight', 'year'],
            'Screwdriver Set'    => ['color', 'tool-type', 'material', 'weight'],

            // Sports
            'Sports & Travel'    => ['sport-type', 'gender', 'size', 'material', 'weight', 'year'],
            'Select Sport'       => ['sport-type', 'material', 'weight', 'year'],
            'Sports Equipment'   => ['sport-type', 'material', 'weight', 'year'],
            'Travel Equipment'   => ['material', 'weight', 'size', 'year'],
            'Boxing Punching Bag'=> ['sport-type', 'material', 'weight', 'year'],
            'Suitcase 4 Wheels'  => ['color', 'material', 'weight', 'size', 'year'],
        ];

        $linkedCount = 0;
        foreach ($categories as $category) {
            $attrSlugs = $categoryAttributeMap[$category->name]
                      ?? ['color', 'material', 'weight', 'year'];

            $sortOrder = 1;
            foreach ($attrSlugs as $slug) {
                $attribute = $allAttrs->get($slug);
                if (!$attribute) continue;

                $exists = DB::table('category_attributes')
                    ->where('category_id', $category->id)
                    ->where('attribute_id', $attribute->id)
                    ->exists();

                if (!$exists) {
                    DB::table('category_attributes')->insert([
                        'category_id'  => $category->id,
                        'attribute_id' => $attribute->id,
                        'sort_order'   => $sortOrder,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                    $linkedCount++;
                }
                $sortOrder++;
            }
        }

        $this->command->info('✅ ' . $linkedCount . ' category-attribute links created!');
        $this->command->info('🎯 AttributeSeeder completed!');
    }
}بیا خودت درست کن من شاید اشتباه کنم اصلا باید این قسمت تغییر کنه ؟
}