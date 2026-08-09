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
                'rating'            => rand(0, 5),
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
    
        // 2. تشخیص خانواده
        $rootSlug = $this->getRootCategorySlug($categoryId, $allCategoryAttributes);
    
        // 3. pool مناسب
        $familyPool = $this->getFamilyAttributePool($rootSlug);
    
        // 4. تبدیل slug به id
        $poolAttrIds = [];
        foreach ($familyPool as $slug) {
            $attr = $allAttributes->get($slug);
            if ($attr) {
                $poolAttrIds[] = $attr->id;
            }
        }
    
        if (empty($poolAttrIds)) return;
    
        // 5. انتخاب 10 attribute
        shuffle($poolAttrIds);
    
        while (count($poolAttrIds) < 10) {
            $poolAttrIds = array_merge($poolAttrIds, $poolAttrIds);
        }
    
        $usedAttrIds   = [];
        $insertedCount = 0;
    
        foreach ($poolAttrIds as $attrId) {
            if ($insertedCount >= 10) break;
            if (in_array($attrId, $usedAttrIds)) continue;
    
            $values = $allAttributeValues->get($attrId, collect());
            if ($values->isEmpty()) continue;
    
            // value مناسب بر اساس خانواده
            $value = $this->getFilteredValue($attrId, $values, $rootSlug, $allAttributes);
    
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
// ================================================================
// تشخیص root category slug
// ================================================================
private function getRootCategorySlug(int $categoryId, $allCategoryAttributes): string
{
    static $allCategories = null;
    if ($allCategories === null) {
        $allCategories = DB::table('categories')->get()->keyBy('id');
    }

    $current = $allCategories->get($categoryId);
    if (!$current) return 'general';

    while ($current && $current->parent_id) {
        $current = $allCategories->get($current->parent_id);
    }

    return $current ? $current->slug : 'general';
}

// ================================================================
// Pool ویژگی‌های هر خانواده محصول
// ================================================================
private function getFamilyAttributePool(string $rootSlug): array
{
    $pools = [
        'mobile' => [
            'storage', 'ram', 'processor', 'battery',
            'display-size', 'display-type', 'water-resistant',
            'camera-mp', 'sim-type', '5g-support', 'year', 'weight',
        ],
        'laptops' => [
            'ram', 'ssd-storage', 'cpu-model', 'screen-size',
            'graphics-card', 'operating-system', 'refresh-rate',
            'battery-life', 'weight', 'connectivity', 'year',
        ],
        'digital-products' => [
            'connectivity', 'battery-life', 'bluetooth-version',
            'waterproof', 'weight', 'noise-cancellation',
            'storage', 'ram', 'screen-size', 'camera-mp', 'year',
        ],
        'home-kitchen' => [
            'material', 'capacity', 'power-usage', 'weight',
            'year', 'size', 'style', 'season', 'connectivity',
        ],
        'home-appliances' => [
            'material', 'capacity', 'power-usage', 'weight',
            'year', 'screen-size', 'display-type', 'refresh-rate',
            'connectivity', 'battery-life',
        ],
        'beauty-health' => [
            'weight', 'year', 'material', 'gender',
            'season', 'style', 'size', 'capacity', 'power-usage',
        ],
        'fashion' => [
            'clothing-size', 'fabric-type', 'season', 'style',
            'gender', 'shoe-size', 'material', 'weight', 'year',
        ],
        'gold-jewelry' => [
            'gold-karat', 'metal-type', 'gemstone', 'watch-movement',
            'water-resistant', 'weight', 'year', 'style', 'gender',
        ],
        'vehicles' => [
            'engine-type', 'transmission', 'fuel-type',
            'year', 'weight', 'material', 'connectivity',
            'battery', 'capacity',
        ],
        'health-medical' => [
            'weight', 'year', 'material', 'power-usage',
            'size', 'gender', 'sport-type', 'capacity', 'connectivity',
        ],
        'tools-equipment' => [
            'tool-type', 'power-source', 'material', 'weight',
            'year', 'power-usage', 'size', 'capacity', 'connectivity',
        ],
        'sports-travel' => [
            'sport-type', 'material', 'weight', 'size',
            'gender', 'season', 'style', 'waterproof', 'year',
        ],
    ];

    return $pools[$rootSlug] ?? [
        'material', 'weight', 'year', 'size',
        'style', 'season', 'gender', 'capacity', 'connectivity',
    ];
}

// ================================================================
// فیلتر value مناسب بر اساس خانواده
// ================================================================
private function getFilteredValue($attrId, $values, string $rootSlug, $allAttributes)
{
    $attr = $allAttributes->first(fn($a) => $a->id == $attrId);
    if (!$attr) return $values->random();

    $allowedValues = $this->getAllowedValues($attr->slug, $rootSlug);

    if (empty($allowedValues)) {
        return $values->random();
    }

    $filtered = $values->filter(fn($v) => in_array($v->value, $allowedValues));

    return $filtered->isEmpty() ? $values->random() : $filtered->random();
}

private function getAllowedValues(string $attrSlug, string $rootSlug): array
{
    $rules = [
        'mobile' => [
            'storage'         => ['64GB', '128GB', '256GB', '512GB', '1TB'],
            'ram'             => ['4GB', '6GB', '8GB', '12GB', '16GB'],
            'processor'       => ['Apple A15 Bionic', 'Apple A16 Bionic', 'Apple A17 Pro', 'Snapdragon 8 Gen 1', 'Snapdragon 8 Gen 2', 'Snapdragon 8 Gen 3'],
            'battery'         => ['3000mAh', '4000mAh', '5000mAh', '6000mAh'],
            'display-size'    => ['5.5 inch', '6.1 inch', '6.5 inch', '6.7 inch', '7.0 inch'],
            'display-type'    => ['AMOLED', 'OLED', 'Super Retina XDR', 'IPS LCD'],
            'water-resistant' => ['IP67', 'IP68', 'No'],
            'camera-mp'       => ['12MP', '48MP', '50MP', '108MP', '200MP'],
            'sim-type'        => ['Single SIM', 'Dual SIM', 'eSIM', 'Dual SIM + eSIM'],
            '5g-support'      => ['Yes', 'No'],
            'weight'          => ['Under 100g', '100-200g', '200-500g'],
            'year'            => ['2023', '2024', '2025'],
            'connectivity'    => ['Bluetooth 5.2', 'Bluetooth 5.3', 'USB-C'],
        ],
        'laptops' => [
            'ram'              => ['8GB', '16GB', '32GB'],
            'ssd-storage'      => ['256GB', '512GB', '1TB', '2TB'],
            'cpu-model'        => ['Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'Apple M2', 'Apple M3', 'AMD Ryzen 7', 'AMD Ryzen 9'],
            'screen-size'      => ['13.3 inch', '14 inch', '15.6 inch', '16 inch', '17.3 inch'],
            'graphics-card'    => ['Integrated', 'NVIDIA RTX 3060', 'NVIDIA RTX 4070', 'NVIDIA RTX 4080', 'NVIDIA RTX 4090'],
            'operating-system' => ['Windows 11', 'macOS', 'Linux'],
            'refresh-rate'     => ['60Hz', '120Hz', '144Hz', '165Hz', '240Hz'],
            'battery-life'     => ['Up to 5 hours', 'Up to 10 hours', 'Up to 20 hours'],
            'weight'           => ['1-2kg', '2-5kg'],
            'connectivity'     => ['WiFi 6', 'WiFi 6E', 'WiFi 7', 'USB-C'],
            'year'             => ['2023', '2024', '2025'],
        ],
        'digital-products' => [
            'connectivity'       => ['Bluetooth 5.2', 'Bluetooth 5.3', 'WiFi 6', 'USB-C'],
            'battery-life'       => ['Up to 5 hours', 'Up to 10 hours', 'Up to 20 hours', 'Up to 30 hours'],
            'bluetooth-version'  => ['5.0', '5.1', '5.2', '5.3'],
            'waterproof'         => ['IPX4', 'IPX5', 'IPX7', 'No'],
            'noise-cancellation' => ['Active ANC', 'Hybrid ANC', 'Passive', 'No'],
            'storage'            => ['128GB', '256GB', '512GB', '1TB'],
            'ram'                => ['4GB', '8GB', '12GB', '16GB'],
            'screen-size'        => ['6.1 inch', '6.7 inch', '7.0 inch'],
            'camera-mp'          => ['12MP', '48MP', '50MP'],
            'weight'             => ['Under 100g', '100-200g', '200-500g', '500g-1kg'],
            'year'               => ['2023', '2024', '2025'],
        ],
        'home-kitchen' => [
            'material'    => ['Stainless Steel', 'Aluminum', 'Glass', 'Plastic', 'Wood', 'Metal'],
            'capacity'    => ['1L', '2L', '5L', '10L'],
            'power-usage' => ['500W', '1000W', '1500W', '2000W'],
            'weight'      => ['500g-1kg', '1-2kg', '2-5kg'],
            'year'        => ['2023', '2024', '2025'],
            'size'        => ['S', 'M', 'L', 'XL'],
            'style'       => ['Classic', 'Modern', 'Minimal'],
            'season'      => ['All Seasons'],
            'connectivity'=> ['USB-C'],
        ],
        'home-appliances' => [
            'material'        => ['Stainless Steel', 'Metal', 'Glass', 'Plastic'],
            'capacity'        => ['5L', '10L', '20L', '300L', '500L'],
            'power-usage'     => ['500W', '1000W', '1500W', '2000W'],
            'weight'          => ['2-5kg', '5kg+'],
            'year'            => ['2023', '2024', '2025'],
            'screen-size'     => ['13.3 inch', '14 inch', '15.6 inch', '16 inch', '17.3 inch'],
            'display-type'    => ['OLED', 'QLED', 'LCD'],
            'refresh-rate'    => ['60Hz', '120Hz'],
            'connectivity'    => ['WiFi 6', 'Bluetooth 5.2'],
            'battery-life'    => ['Up to 5 hours', 'Up to 10 hours'],
        ],
        'beauty-health' => [
            'weight'      => ['Under 100g', '100-200g', '200-500g'],
            'year'        => ['2023', '2024', '2025'],
            'material'    => ['Plastic', 'Glass', 'Metal'],
            'gender'      => ['Men', 'Women', 'Unisex'],
            'season'      => ['Spring', 'Summer', 'Fall', 'Winter', 'All Seasons'],
            'style'       => ['Classic', 'Modern', 'Luxury'],
            'size'        => ['S', 'M', 'L'],
            'capacity'    => ['1L', '2L'],
            'power-usage' => ['500W', '1000W', '1500W'],
        ],
        'fashion' => [
            'clothing-size' => ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'fabric-type'   => ['Cotton', 'Polyester', 'Wool', 'Silk', 'Denim', 'Linen'],
            'season'        => ['Spring', 'Summer', 'Fall', 'Winter', 'All Seasons'],
            'style'         => ['Classic', 'Modern', 'Sport', 'Casual', 'Formal', 'Luxury'],
            'gender'        => ['Men', 'Women', 'Unisex'],
            'shoe-size'     => ['38', '39', '40', '41', '42', '43', '44', '45'],
            'material'      => ['Leather', 'Cotton', 'Polyester', 'Fabric'],
            'weight'        => ['Under 100g', '100-200g', '200-500g', '500g-1kg'],
            'year'          => ['2023', '2024', '2025'],
        ],
        'gold-jewelry' => [
            'gold-karat'      => ['18K', '21K', '22K', '24K'],
            'metal-type'      => ['Gold', 'Silver', 'Platinum', 'Rose Gold', 'Titanium'],
            'gemstone'        => ['Diamond', 'Ruby', 'Sapphire', 'Emerald', 'Pearl'],
            'watch-movement'  => ['Automatic', 'Quartz', 'Mechanical', 'Smart'],
            'water-resistant' => ['IP67', 'IP68', 'No'],
            'weight'          => ['Under 100g', '100-200g'],
            'year'            => ['2023', '2024', '2025'],
            'style'           => ['Classic', 'Modern', 'Luxury'],
            'gender'          => ['Men', 'Women', 'Unisex'],
        ],
        'vehicles' => [
            'engine-type'  => ['Petrol', 'Diesel', 'Electric', 'Hybrid', 'Plug-in Hybrid'],
            'transmission' => ['Manual', 'Automatic', 'CVT', 'Dual Clutch'],
            'fuel-type'    => ['Petrol', 'Diesel', 'Electric', 'Hybrid', 'CNG'],
            'year'         => ['2022', '2023', '2024', '2025'],
            'weight'       => ['2-5kg', '5kg+'],
            'material'     => ['Metal', 'Aluminum', 'Carbon Fiber'],
            'connectivity' => ['Bluetooth 5.2', 'USB-C'],
            'battery'      => ['5000mAh', '6000mAh', '10000mAh'],
            'capacity'     => ['10L', '20L'],
        ],
        'health-medical' => [
            'weight'      => ['Under 100g', '100-200g', '200-500g', '500g-1kg', '1-2kg', '2-5kg'],
            'year'        => ['2023', '2024', '2025'],
            'material'    => ['Plastic', 'Metal', 'Fabric', 'Stainless Steel'],
            'power-usage' => ['100W', '500W', '1000W', '1500W'],
            'size'        => ['S', 'M', 'L', 'XL'],
            'gender'      => ['Men', 'Women', 'Unisex'],
            'sport-type'  => ['Running', 'Gym', 'Yoga', 'Cycling', 'Swimming'],
            'capacity'    => ['1L', '2L', '5L'],
            'connectivity'=> ['Bluetooth 5.2', 'USB-C'],
        ],
        'tools-equipment' => [
            'tool-type'    => ['Drill', 'Grinder', 'Screwdriver', 'Hammer', 'Saw'],
            'power-source' => ['Battery', 'Electric', 'Manual'],
            'material'     => ['Metal', 'Stainless Steel', 'Plastic'],
            'weight'       => ['500g-1kg', '1-2kg', '2-5kg', '5kg+'],
            'year'         => ['2023', '2024', '2025'],
            'power-usage'  => ['500W', '1000W', '1500W', '2000W'],
            'size'         => ['S', 'M', 'L'],
            'capacity'     => ['1L', '2L'],
            'connectivity' => ['USB-C'],
        ],
        'sports-travel' => [
            'sport-type' => ['Running', 'Gym', 'Boxing', 'Yoga', 'Cycling', 'Swimming', 'Hiking'],
            'material'   => ['Metal', 'Plastic', 'Fabric', 'Leather', 'Cotton', 'Polyester'],
            'weight'     => ['200-500g', '500g-1kg', '1-2kg', '2-5kg', '5kg+'],
            'size'       => ['S', 'M', 'L', 'XL'],
            'gender'     => ['Men', 'Women', 'Unisex'],
            'season'     => ['Spring', 'Summer', 'Fall', 'Winter', 'All Seasons'],
            'style'      => ['Sport', 'Casual'],
            'waterproof' => ['IPX4', 'IPX5', 'IPX7', 'No'],
            'year'       => ['2023', '2024', '2025'],
        ],
    ];

    return $rules[$rootSlug][$attrSlug] ?? [];
}
}