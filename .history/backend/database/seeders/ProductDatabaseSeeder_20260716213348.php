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
                    'name' => $child['name'],
                    'slug' => $finalSlug,
                    'icon_key' => $child['icon_key'] ?? null,
                    'sort_order' => $child['sort_order'] ?? 0,
                    'is_active' => 1,
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->info('  ➕ Created: ' . $child['name'] . ' (slug: ' . $finalSlug . ')');
            } else {
                $childId = $childCategory->id;
                $this->command->info('  ⏭️ Skipped: ' . $child['name']);
            }
            
            if (isset($child['children']) && !empty($child['children'])) {
                $this->saveChildren($child['children'], $childId);
            }
        }
    }

    public function run()
    {
        $this->command->info('🚀 Starting complete product seeding...');

        // ================================================================
        // 1. CREATE CATEGORIES (دسته‌بندی‌ها مثل قبل)
        // ================================================================
        $categories = [
            // ===== MOBILE =====
            [
                'name' => 'Mobile',
                'slug' => 'mobile',
                'icon_key' => 'mobile',
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Select Mobile',
                        'slug' => 'select-mobile',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Apple Phones',
                                'slug' => 'apple-phones',
                                'children' => [
                                    ['name' => 'iPhone 16', 'slug' => 'iphone-16'],
                                    ['name' => 'iPhone 16 Pro', 'slug' => 'iphone-16-pro'],
                                    ['name' => 'iPhone 16 Pro Max', 'slug' => 'iphone-16-pro-max'],
                                    ['name' => 'iPhone 15', 'slug' => 'iphone-15'],
                                    ['name' => 'iPhone 15 Pro', 'slug' => 'iphone-15-pro'],
                                    ['name' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max'],
                                    ['name' => 'iPhone 14', 'slug' => 'iphone-14'],
                                    ['name' => 'iPhone SE', 'slug' => 'iphone-se'],
                                ]
                            ],
                            [
                                'name' => 'Samsung Phones',
                                'slug' => 'samsung-phones',
                                'children' => [
                                    ['name' => 'Galaxy S24 Ultra', 'slug' => 'galaxy-s24-ultra'],
                                    ['name' => 'Galaxy S24 Plus', 'slug' => 'galaxy-s24-plus'],
                                    ['name' => 'Galaxy S24', 'slug' => 'galaxy-s24'],
                                    ['name' => 'Galaxy Z Fold 6', 'slug' => 'galaxy-z-fold-6'],
                                    ['name' => 'Galaxy Z Flip 6', 'slug' => 'galaxy-z-flip-6'],
                                    ['name' => 'Galaxy A55', 'slug' => 'galaxy-a55'],
                                    ['name' => 'Galaxy A35', 'slug' => 'galaxy-a35'],
                                ]
                            ],
                            [
                                'name' => 'Xiaomi Phones',
                                'slug' => 'xiaomi-phones',
                                'children' => [
                                    ['name' => 'Xiaomi 14 Ultra', 'slug' => 'xiaomi-14-ultra'],
                                    ['name' => 'Xiaomi 14 Pro', 'slug' => 'xiaomi-14-pro'],
                                    ['name' => 'Xiaomi 14', 'slug' => 'xiaomi-14'],
                                    ['name' => 'Redmi Note 13 Pro', 'slug' => 'redmi-note-13-pro'],
                                    ['name' => 'Redmi Note 13', 'slug' => 'redmi-note-13'],
                                    ['name' => 'Poco X7 Pro', 'slug' => 'poco-x7-pro'],
                                ]
                            ],
                            [
                                'name' => 'Other Brands',
                                'slug' => 'other-brands',
                                'children' => [
                                    ['name' => 'Google Pixel', 'slug' => 'google-pixel'],
                                    ['name' => 'OnePlus', 'slug' => 'oneplus'],
                                    ['name' => 'Huawei', 'slug' => 'huawei'],
                                    ['name' => 'Nokia', 'slug' => 'nokia'],
                                    ['name' => 'Sony Xperia', 'slug' => 'sony-xperia'],
                                    ['name' => 'Motorola', 'slug' => 'motorola'],
                                    ['name' => 'Nothing Phone', 'slug' => 'nothing-phone'],
                                    ['name' => 'Realme', 'slug' => 'realme'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== LAPTOPS =====
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'icon_key' => 'laptops',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Select Laptop',
                        'slug' => 'select-laptop',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Apple MacBooks',
                                'slug' => 'apple-macbooks',
                                'children' => [
                                    ['name' => 'MacBook Pro M3', 'slug' => 'macbook-pro-m3'],
                                    ['name' => 'MacBook Pro M2', 'slug' => 'macbook-pro-m2'],
                                    ['name' => 'MacBook Air M3', 'slug' => 'macbook-air-m3'],
                                    ['name' => 'MacBook Air M2', 'slug' => 'macbook-air-m2'],
                                    ['name' => 'MacBook Pro M4', 'slug' => 'macbook-pro-m4'],
                                ]
                            ],
                            [
                                'name' => 'ASUS Laptops',
                                'slug' => 'asus-laptops',
                                'children' => [
                                    ['name' => 'ASUS ROG Zephyrus', 'slug' => 'asus-rog-zephyrus'],
                                    ['name' => 'ASUS TUF Gaming', 'slug' => 'asus-tuf-gaming'],
                                    ['name' => 'ASUS ZenBook', 'slug' => 'asus-zenbook'],
                                    ['name' => 'ASUS Vivobook', 'slug' => 'asus-vivobook'],
                                ]
                            ],
                            [
                                'name' => 'Lenovo Laptops',
                                'slug' => 'lenovo-laptops',
                                'children' => [
                                    ['name' => 'Lenovo ThinkPad X1', 'slug' => 'lenovo-thinkpad-x1'],
                                    ['name' => 'Lenovo Legion Pro', 'slug' => 'lenovo-legion-pro'],
                                    ['name' => 'Lenovo IdeaPad 5', 'slug' => 'lenovo-ideapad-5'],
                                    ['name' => 'Lenovo LOQ', 'slug' => 'lenovo-loq'],
                                ]
                            ],
                            [
                                'name' => 'Gaming Laptops',
                                'slug' => 'gaming-laptops',
                                'children' => [
                                    ['name' => 'MSI Titan GT77', 'slug' => 'msi-titan-gt77'],
                                    ['name' => 'Razer Blade 16', 'slug' => 'razer-blade-16'],
                                    ['name' => 'Acer Predator Helios', 'slug' => 'acer-predator-helios'],
                                    ['name' => 'HP Omen', 'slug' => 'hp-omen'],
                                    ['name' => 'Dell Alienware', 'slug' => 'dell-alienware'],
                                ]
                            ],
                            [
                                'name' => 'Business Laptops',
                                'slug' => 'business-laptops',
                                'children' => [
                                    ['name' => 'Dell XPS 16', 'slug' => 'dell-xps-16'],
                                    ['name' => 'HP Spectre x360', 'slug' => 'hp-spectre-x360'],
                                    ['name' => 'Microsoft Surface Laptop', 'slug' => 'microsoft-surface-laptop'],
                                    ['name' => 'LG Gram', 'slug' => 'lg-gram'],
                                ]
                            ],
                            [
                                'name' => 'Student Laptops',
                                'slug' => 'student-laptops',
                                'children' => [
                                    ['name' => 'Acer Aspire 5', 'slug' => 'acer-aspire-5'],
                                    ['name' => 'HP Pavilion 15', 'slug' => 'hp-pavilion-15'],
                                    ['name' => 'Dell Inspiron', 'slug' => 'dell-inspiron'],
                                    ['name' => 'Lenovo IdeaPad Slim', 'slug' => 'lenovo-ideapad-slim'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== DIGITAL PRODUCTS =====
            [
                'name' => 'Digital Products',
                'slug' => 'digital-products',
                'icon_key' => 'digital',
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Select Digital',
                        'slug' => 'select-digital',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Gaming Consoles',
                                'slug' => 'gaming-consoles',
                                'children' => [
                                    ['name' => 'PS5', 'slug' => 'ps5'],
                                    ['name' => 'PS5 Slim', 'slug' => 'ps5-slim'],
                                    ['name' => 'PS5 Pro', 'slug' => 'ps5-pro'],
                                    ['name' => 'Xbox Series X', 'slug' => 'xbox-series-x'],
                                    ['name' => 'Xbox Series S', 'slug' => 'xbox-series-s'],
                                    ['name' => 'Nintendo Switch OLED', 'slug' => 'nintendo-switch-oled'],
                                    ['name' => 'Nintendo Switch Lite', 'slug' => 'nintendo-switch-lite'],
                                ]
                            ],
                            [
                                'name' => 'Headphones',
                                'slug' => 'headphones',
                                'children' => [
                                    ['name' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5'],
                                    ['name' => 'Sony WH-1000XM4', 'slug' => 'sony-wh-1000xm4'],
                                    ['name' => 'JBL Tune 770NC', 'slug' => 'jbl-tune-770nc'],
                                    ['name' => 'Bose QC45', 'slug' => 'bose-qc45'],
                                    ['name' => 'Apple AirPods Pro 2', 'slug' => 'apple-airpods-pro-2'],
                                    ['name' => 'Apple AirPods Max', 'slug' => 'apple-airpods-max'],
                                    ['name' => 'Samsung Galaxy Buds 2 Pro', 'slug' => 'samsung-buds-2-pro'],
                                    ['name' => 'Xiaomi Buds 3 Pro', 'slug' => 'xiaomi-buds-3-pro'],
                                ]
                            ],
                            [
                                'name' => 'Smartwatches',
                                'slug' => 'smartwatches',
                                'children' => [
                                    ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2'],
                                    ['name' => 'Apple Watch Series 9', 'slug' => 'apple-watch-series-9'],
                                    ['name' => 'Samsung Galaxy Watch 6', 'slug' => 'samsung-watch-6'],
                                    ['name' => 'Xiaomi Watch S3', 'slug' => 'xiaomi-watch-s3'],
                                ]
                            ],
                            [
                                'name' => 'Tablets',
                                'slug' => 'tablets',
                                'children' => [
                                    ['name' => 'iPad Pro M4', 'slug' => 'ipad-pro-m4'],
                                    ['name' => 'Samsung Galaxy Tab S9', 'slug' => 'samsung-tab-s9'],
                                    ['name' => 'Xiaomi Pad 6', 'slug' => 'xiaomi-pad-6'],
                                ]
                            ],
                            [
                                'name' => 'Speakers',
                                'slug' => 'speakers',
                                'children' => [
                                    ['name' => 'JBL Charge 5', 'slug' => 'jbl-charge-5'],
                                    ['name' => 'Sony SRS-XG300', 'slug' => 'sony-srs-xg300'],
                                    ['name' => 'Bose SoundLink Max', 'slug' => 'bose-soundlink-max'],
                                ]
                            ],
                            [
                                'name' => 'Cameras',
                                'slug' => 'cameras',
                                'children' => [
                                    ['name' => 'Canon EOS R5', 'slug' => 'canon-eos-r5'],
                                    ['name' => 'Sony Alpha A7 IV', 'slug' => 'sony-alpha-a7-iv'],
                                    ['name' => 'Nikon Z8', 'slug' => 'nikon-z8'],
                                ]
                            ],
                            [
                                'name' => 'Power Banks',
                                'slug' => 'power-banks',
                                'children' => [
                                    ['name' => 'Anker 20000mAh', 'slug' => 'anker-20000mah'],
                                    ['name' => 'Xiaomi 30000mAh', 'slug' => 'xiaomi-30000mah'],
                                ]
                            ],
                            [
                                'name' => 'Computer Components',
                                'slug' => 'computer-components',
                                'children' => [
                                    ['name' => 'Intel Core i9-14900K', 'slug' => 'intel-core-i9-14900k'],
                                    ['name' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090'],
                                ]
                            ],
                            [
                                'name' => 'Smart Home',
                                'slug' => 'smart-home',
                                'children' => [
                                    ['name' => 'Xiaomi Smart Hub', 'slug' => 'xiaomi-smart-hub'],
                                    ['name' => 'Google Nest Hub 2', 'slug' => 'google-nest-hub-2'],
                                ]
                            ],
                            [
                                'name' => 'Printers',
                                'slug' => 'printers',
                                'children' => [
                                    ['name' => 'HP LaserJet Pro MFP', 'slug' => 'hp-laserjet-pro-mfp'],
                                ]
                            ],
                            [
                                'name' => 'Storage Devices',
                                'slug' => 'storage-devices',
                                'children' => [
                                    ['name' => 'Samsung 1TB SSD', 'slug' => 'samsung-1tb-ssd'],
                                    ['name' => 'Western Digital 2TB HDD', 'slug' => 'wd-2tb-hdd'],
                                ]
                            ],
                            [
                                'name' => 'Networking',
                                'slug' => 'networking',
                                'children' => [
                                    ['name' => 'TP-Link Router', 'slug' => 'tplink-router'],
                                    ['name' => 'Asus Router', 'slug' => 'asus-router'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== HOME & KITCHEN =====
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'icon_key' => 'home-kitchen',
                'sort_order' => 4,
                'children' => [
                    [
                        'name' => 'Select Home',
                        'slug' => 'select-home',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Cookware',
                                'slug' => 'cookware',
                                'children' => [
                                    ['name' => 'Non-Stick Frying Pan', 'slug' => 'non-stick-frying-pan'],
                                    ['name' => 'Pressure Cooker', 'slug' => 'pressure-cooker'],
                                    ['name' => 'Knife Set', 'slug' => 'knife-set'],
                                ]
                            ],
                            [
                                'name' => 'Tea & Coffee',
                                'slug' => 'tea-coffee',
                                'children' => [
                                    ['name' => 'Coffee Maker', 'slug' => 'coffee-maker'],
                                    ['name' => 'Electric Kettle', 'slug' => 'electric-kettle'],
                                ]
                            ],
                            [
                                'name' => 'Furniture',
                                'slug' => 'furniture',
                                'children' => [
                                    ['name' => 'Sofa Set', 'slug' => 'sofa-set'],
                                    ['name' => 'Dining Table', 'slug' => 'dining-table'],
                                    ['name' => 'Office Chair', 'slug' => 'office-chair'],
                                ]
                            ],
                            [
                                'name' => 'Lighting',
                                'slug' => 'lighting',
                                'children' => [
                                    ['name' => 'Chandelier', 'slug' => 'chandelier'],
                                    ['name' => 'Table Lamp', 'slug' => 'table-lamp'],
                                ]
                            ],
                            [
                                'name' => 'Carpets & Rugs',
                                'slug' => 'carpets-rugs',
                                'children' => [
                                    ['name' => 'Persian Carpet', 'slug' => 'persian-carpet'],
                                ]
                            ],
                            [
                                'name' => 'Bedroom',
                                'slug' => 'bedroom',
                                'children' => [
                                    ['name' => 'King Size Bed', 'slug' => 'king-size-bed'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== HOME APPLIANCES =====
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'icon_key' => 'home-appliances',
                'sort_order' => 5,
                'children' => [
                    [
                        'name' => 'Select Appliance',
                        'slug' => 'select-appliance',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Refrigerators',
                                'slug' => 'refrigerators',
                                'children' => [
                                    ['name' => 'LG Refrigerator', 'slug' => 'lg-refrigerator'],
                                    ['name' => 'Samsung Refrigerator', 'slug' => 'samsung-refrigerator'],
                                ]
                            ],
                            [
                                'name' => 'Washing Machines',
                                'slug' => 'washing-machines',
                                'children' => [
                                    ['name' => 'LG Washing Machine', 'slug' => 'lg-washing-machine'],
                                    ['name' => 'Samsung Washing Machine', 'slug' => 'samsung-washing-machine'],
                                ]
                            ],
                            [
                                'name' => 'Dishwashers',
                                'slug' => 'dishwashers',
                                'children' => [
                                    ['name' => 'Bosch Dishwasher', 'slug' => 'bosch-dishwasher'],
                                ]
                            ],
                            [
                                'name' => 'Vacuums',
                                'slug' => 'vacuums',
                                'children' => [
                                    ['name' => 'Robot Vacuum', 'slug' => 'robot-vacuum'],
                                ]
                            ],
                            [
                                'name' => 'Cooking Appliances',
                                'slug' => 'cooking-appliances',
                                'children' => [
                                    ['name' => 'Air Fryer', 'slug' => 'air-fryer'],
                                    ['name' => 'Microwave Oven', 'slug' => 'microwave-oven'],
                                ]
                            ],
                            [
                                'name' => 'TVs',
                                'slug' => 'tvs',
                                'children' => [
                                    ['name' => 'Sony OLED TV', 'slug' => 'sony-oled-tv'],
                                    ['name' => 'Samsung QLED TV', 'slug' => 'samsung-qled-tv'],
                                    ['name' => 'LG OLED TV', 'slug' => 'lg-oled-tv'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== BEAUTY & HEALTH =====
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'icon_key' => 'beauty-health',
                'sort_order' => 6,
                'children' => [
                    [
                        'name' => 'Select Beauty',
                        'slug' => 'select-beauty',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Skin Care',
                                'slug' => 'skin-care',
                                'children' => [
                                    ['name' => 'Moisturizer Cream', 'slug' => 'moisturizer-cream'],
                                    ['name' => 'Sunscreen SPF 50', 'slug' => 'sunscreen-spf-50'],
                                ]
                            ],
                            [
                                'name' => 'Makeup',
                                'slug' => 'makeup',
                                'children' => [
                                    ['name' => 'Foundation', 'slug' => 'foundation'],
                                    ['name' => 'Lipstick', 'slug' => 'lipstick'],
                                ]
                            ],
                            [
                                'name' => 'Hair Care',
                                'slug' => 'hair-care',
                                'children' => [
                                    ['name' => 'Shampoo', 'slug' => 'shampoo'],
                                    ['name' => 'Hair Dryer', 'slug' => 'hair-dryer'],
                                ]
                            ],
                            [
                                'name' => 'Perfumes',
                                'slug' => 'perfumes',
                                'children' => [
                                    ['name' => 'Dior Sauvage', 'slug' => 'dior-sauvage'],
                                    ['name' => 'Chanel No.5', 'slug' => 'chanel-no-5'],
                                ]
                            ],
                            [
                                'name' => 'Oral Care',
                                'slug' => 'oral-care',
                                'children' => [
                                    ['name' => 'Electric Toothbrush', 'slug' => 'electric-toothbrush'],
                                ]
                            ],
                            [
                                'name' => 'Personal Care',
                                'slug' => 'personal-care',
                                'children' => [
                                    ['name' => 'Deodorant', 'slug' => 'deodorant'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== FASHION =====
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'icon_key' => 'fashion',
                'sort_order' => 7,
                'children' => [
                    [
                        'name' => 'Select Fashion',
                        'slug' => 'select-fashion',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Men\'s Clothing',
                                'slug' => 'mens-clothing',
                                'children' => [
                                    ['name' => 'Men\'s T-Shirt', 'slug' => 'mens-t-shirt'],
                                    ['name' => 'Men\'s Jeans', 'slug' => 'mens-jeans'],
                                    ['name' => 'Men\'s Suit', 'slug' => 'mens-suit'],
                                ]
                            ],
                            [
                                'name' => 'Women\'s Clothing',
                                'slug' => 'womens-clothing',
                                'children' => [
                                    ['name' => 'Women\'s Dress', 'slug' => 'womens-dress'],
                                    ['name' => 'Women\'s Jeans', 'slug' => 'womens-jeans'],
                                    ['name' => 'Manteau', 'slug' => 'manteau'],
                                ]
                            ],
                            [
                                'name' => 'Children\'s Clothing',
                                'slug' => 'childrens-clothing',
                                'children' => [
                                    ['name' => 'Baby Bodysuit', 'slug' => 'baby-bodysuit'],
                                ]
                            ],
                            [
                                'name' => 'Shoes',
                                'slug' => 'shoes',
                                'children' => [
                                    ['name' => 'Nike Air Max', 'slug' => 'nike-air-max'],
                                    ['name' => 'Adidas Ultraboost', 'slug' => 'adidas-ultraboost'],
                                ]
                            ],
                            [
                                'name' => 'Bags & Accessories',
                                'slug' => 'bags-accessories',
                                'children' => [
                                    ['name' => 'Men\'s Wallet', 'slug' => 'mens-wallet'],
                                    ['name' => 'Women\'s Handbag', 'slug' => 'womens-handbag'],
                                    ['name' => 'Rolex Watch', 'slug' => 'rolex-watch'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== GOLD & JEWELRY =====
            [
                'name' => 'Gold & Jewelry',
                'slug' => 'gold-jewelry',
                'icon_key' => 'gold-jewelry',
                'sort_order' => 8,
                'children' => [
                    [
                        'name' => 'Select Jewelry',
                        'slug' => 'select-jewelry',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Gold Jewelry',
                                'slug' => 'gold-jewelry-items',
                                'children' => [
                                    ['name' => 'Gold Necklace', 'slug' => 'gold-necklace'],
                                    ['name' => 'Gold Ring', 'slug' => 'gold-ring'],
                                    ['name' => 'Gold Earrings', 'slug' => 'gold-earrings'],
                                ]
                            ],
                            [
                                'name' => 'Silver Jewelry',
                                'slug' => 'silver-jewelry',
                                'children' => [
                                    ['name' => 'Silver Necklace', 'slug' => 'silver-necklace'],
                                ]
                            ],
                            [
                                'name' => 'Diamonds & Gems',
                                'slug' => 'diamonds-gems',
                                'children' => [
                                    ['name' => 'Diamond Ring', 'slug' => 'diamond-ring'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== VEHICLES =====
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'icon_key' => 'vehicles',
                'sort_order' => 9,
                'children' => [
                    [
                        'name' => 'Select Vehicle',
                        'slug' => 'select-vehicle',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Cars',
                                'slug' => 'cars',
                                'children' => [
                                    ['name' => 'BMW 5 Series', 'slug' => 'bmw-5-series'],
                                    ['name' => 'Mercedes E-Class', 'slug' => 'mercedes-e-class'],
                                    ['name' => 'Toyota Camry', 'slug' => 'toyota-camry'],
                                    ['name' => 'Honda Civic', 'slug' => 'honda-civic'],
                                ]
                            ],
                            [
                                'name' => 'Motorcycles',
                                'slug' => 'motorcycles',
                                'children' => [
                                    ['name' => 'Honda CBR 500R', 'slug' => 'honda-cbr-500r'],
                                ]
                            ],
                            [
                                'name' => 'Car Accessories',
                                'slug' => 'car-accessories',
                                'children' => [
                                    ['name' => 'Car Audio System', 'slug' => 'car-audio-system'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== HEALTH & MEDICAL =====
            [
                'name' => 'Health & Medical',
                'slug' => 'health-medical',
                'icon_key' => 'health-medical',
                'sort_order' => 10,
                'children' => [
                    [
                        'name' => 'Select Medical',
                        'slug' => 'select-medical',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Medical Equipment',
                                'slug' => 'medical-equipment',
                                'children' => [
                                    ['name' => 'Blood Pressure Monitor', 'slug' => 'blood-pressure-monitor'],
                                    ['name' => 'Digital Thermometer', 'slug' => 'digital-thermometer'],
                                ]
                            ],
                            [
                                'name' => 'Orthopedic',
                                'slug' => 'orthopedic',
                                'children' => [
                                    ['name' => 'Knee Brace', 'slug' => 'knee-brace'],
                                ]
                            ],
                            [
                                'name' => 'Supplements',
                                'slug' => 'supplements',
                                'children' => [
                                    ['name' => 'Vitamin C', 'slug' => 'vitamin-c'],
                                    ['name' => 'Omega-3', 'slug' => 'omega-3'],
                                ]
                            ],
                            [
                                'name' => 'Fitness Equipment',
                                'slug' => 'fitness-equipment',
                                'children' => [
                                    ['name' => 'Treadmill', 'slug' => 'treadmill'],
                                    ['name' => 'Yoga Mat', 'slug' => 'yoga-mat'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== TOOLS & EQUIPMENT =====
            [
                'name' => 'Tools & Equipment',
                'slug' => 'tools-equipment',
                'icon_key' => 'tools-equipment',
                'sort_order' => 11,
                'children' => [
                    [
                        'name' => 'Select Tool',
                        'slug' => 'select-tool',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Power Tools',
                                'slug' => 'power-tools',
                                'children' => [
                                    ['name' => 'Makita Drill', 'slug' => 'makita-drill'],
                                    ['name' => 'DeWalt Grinder', 'slug' => 'dewalt-grinder'],
                                ]
                            ],
                            [
                                'name' => 'Hand Tools',
                                'slug' => 'hand-tools',
                                'children' => [
                                    ['name' => 'Screwdriver Set', 'slug' => 'screwdriver-set'],
                                ]
                            ],
                            [
                                'name' => 'Gardening Tools',
                                'slug' => 'gardening-tools',
                                'children' => [
                                    ['name' => 'Lawn Mower', 'slug' => 'lawn-mower'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== BOOKS & ART =====
            [
                'name' => 'Books & Art',
                'slug' => 'books-art',
                'icon_key' => 'books-art',
                'sort_order' => 12,
                'children' => [
                    [
                        'name' => 'Select Book',
                        'slug' => 'select-book',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Books',
                                'slug' => 'books',
                                'children' => [
                                    ['name' => '1984 George Orwell', 'slug' => '1984-george-orwell'],
                                    ['name' => 'Atomic Habits', 'slug' => 'atomic-habits'],
                                ]
                            ],
                            [
                                'name' => 'Art & Painting',
                                'slug' => 'art-painting',
                                'children' => [
                                    ['name' => 'Oil Painting Canvas', 'slug' => 'oil-painting-canvas'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== SPORTS & TRAVEL =====
            [
                'name' => 'Sports & Travel',
                'slug' => 'sports-travel',
                'icon_key' => 'sports-travel',
                'sort_order' => 13,
                'children' => [
                    [
                        'name' => 'Select Sport',
                        'slug' => 'select-sport',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Sports Equipment',
                                'slug' => 'sports-equipment',
                                'children' => [
                                    ['name' => 'Boxing Punching Bag', 'slug' => 'boxing-punching-bag'],
                                ]
                            ],
                            [
                                'name' => 'Travel Equipment',
                                'slug' => 'travel-equipment',
                                'children' => [
                                    ['name' => 'Suitcase 4 Wheels', 'slug' => 'suitcase-4-wheels'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // ===== GIFT CARDS =====
            [
                'name' => 'Gift Cards',
                'slug' => 'gift-cards',
                'icon_key' => 'gift-cards',
                'sort_order' => 14,
                'children' => [
                    [
                        'name' => 'Select Gift',
                        'slug' => 'select-gift',
                        'sort_order' => 0,
                        'children' => [
                            [
                                'name' => 'Store Gift Cards',
                                'slug' => 'store-gift-cards',
                                'children' => [
                                    ['name' => 'Digikala Gift Card', 'slug' => 'digikala-gift-card'],
                                ]
                            ],
                            [
                                'name' => 'Digital Gift Cards',
                                'slug' => 'digital-gift-cards',
                                'children' => [
                                    ['name' => 'PlayStation Gift Card', 'slug' => 'playstation-gift-card'],
                                    ['name' => 'Google Play Gift Card', 'slug' => 'google-play-gift-card'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];

        // ================================================================
        // SAVE CATEGORIES
        // ================================================================
        $categoryIds = [];

        foreach ($categories as $mainCat) {
            $mainCategory = DB::table('categories')->where('slug', $mainCat['slug'])->first();
            
            if (!$mainCategory) {
                $mainId = DB::table('categories')->insertGetId([
                    'name' => $mainCat['name'],
                    'slug' => $mainCat['slug'],
                    'icon_key' => $mainCat['icon_key'] ?? null,
                    'sort_order' => $mainCat['sort_order'],
                    'is_active' => 1,
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->info('➕ Created: ' . $mainCat['name']);
            } else {
                $mainId = $mainCategory->id;
                $this->command->info('⏭️ Skipped: ' . $mainCat['name']);
            }
            
            $categoryIds[$mainCat['slug']] = $mainId;

            if (isset($mainCat['children']) && !empty($mainCat['children'])) {
                $this->saveChildren($mainCat['children'], $mainId);
            }
        }

        $this->command->info('✅ All categories created successfully!');

        // ================================================================
        // 2. CREATE BRANDS
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
            ['name' => 'Whirlpool', 'slug' => 'whirlpool'],
            ['name' => 'Philips', 'slug' => 'philips'],
            ['name' => 'Kenwood', 'slug' => 'kenwood'],
            ['name' => 'IKEA', 'slug' => 'ikea'],
            ['name' => 'Zara', 'slug' => 'zara'],
            ['name' => 'H&M', 'slug' => 'hm'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Puma', 'slug' => 'puma'],
            ['name' => 'Levis', 'slug' => 'levis'],
            ['name' => 'Rolex', 'slug' => 'rolex'],
            ['name' => 'Seiko', 'slug' => 'seiko'],
            ['name' => 'Tissot', 'slug' => 'tissot'],
            ['name' => 'Omega', 'slug' => 'omega'],
            ['name' => 'Citizen', 'slug' => 'citizen'],
            ['name' => 'BMW', 'slug' => 'bmw'],
            ['name' => 'Mercedes', 'slug' => 'mercedes'],
            ['name' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'Honda', 'slug' => 'honda'],
            ['name' => 'Hyundai', 'slug' => 'hyundai'],
            ['name' => 'Kia', 'slug' => 'kia'],
            ['name' => 'Canon', 'slug' => 'canon'],
            ['name' => 'Nikon', 'slug' => 'nikon'],
            ['name' => 'JBL', 'slug' => 'jbl'],
            ['name' => 'Bose', 'slug' => 'bose'],
            ['name' => 'Anker', 'slug' => 'anker'],
            ['name' => 'Makita', 'slug' => 'makita'],
            ['name' => 'DeWalt', 'slug' => 'dewalt'],
            ['name' => 'Stanley', 'slug' => 'stanley'],
            ['name' => 'Milwaukee', 'slug' => 'milwaukee'],
            ['name' => 'Loreal', 'slug' => 'loreal'],
            ['name' => 'Maybelline', 'slug' => 'maybelline'],
            ['name' => 'Nivea', 'slug' => 'nivea'],
            ['name' => 'Dior', 'slug' => 'dior'],
            ['name' => 'Chanel', 'slug' => 'chanel'],
            ['name' => 'Clinique', 'slug' => 'clinique'],
            ['name' => 'Nintendo', 'slug' => 'nintendo'],
            ['name' => 'PlayStation', 'slug' => 'playstation'],
            ['name' => 'Xbox', 'slug' => 'xbox'],
            ['name' => 'TP-Link', 'slug' => 'tp-link'],
            ['name' => 'D-Link', 'slug' => 'd-link'],
            ['name' => 'Beats', 'slug' => 'beats'],
            ['name' => 'Casper', 'slug' => 'casper'],
            ['name' => 'Snowa', 'slug' => 'snowa'],
            ['name' => 'Pakshoma', 'slug' => 'pakshoma'],
            ['name' => 'X-Vision', 'slug' => 'x-vision'],
            ['name' => 'Daewoo', 'slug' => 'daewoo'],
            ['name' => 'Janome', 'slug' => 'janome'],
            ['name' => 'Gree', 'slug' => 'gree'],
            ['name' => 'Toshiba', 'slug' => 'toshiba'],
            ['name' => 'Marshall', 'slug' => 'marshall'],
            ['name' => 'Garmin', 'slug' => 'garmin'],
            ['name' => 'Poco', 'slug' => 'poco'],
            ['name' => 'Honor', 'slug' => 'honor'],
            ['name' => 'TCL', 'slug' => 'tcl'],
            ['name' => 'Zara Home', 'slug' => 'zara-home'],
        ];

        foreach ($brands as $brand) {
            $exists = DB::table('brands')->where('slug', $brand['slug'])->exists();
            if (!$exists) {
                DB::table('brands')->insert([
                    'name' => $brand['name'],
                    'slug' => $brand['slug'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('✅ Brands created!');

        // ================================================================
        // 3. CONNECT BRANDS TO CATEGORIES
        // ================================================================
        $this->command->info('🔗 Connecting brands to categories...');

        DB::table('category_brand')->truncate();

        $brandCategoryMap = [
            'mobile' => ['Apple', 'Samsung', 'Xiaomi', 'Google', 'OnePlus', 'Huawei', 'Nokia', 'Sony', 'Motorola', 'Realme', 'Nothing'],
            'select-mobile' => ['Apple', 'Samsung', 'Xiaomi', 'Google', 'OnePlus', 'Huawei', 'Nokia', 'Sony', 'Motorola', 'Realme', 'Nothing'],
            'apple-phones' => ['Apple'],
            'samsung-phones' => ['Samsung'],
            'xiaomi-phones' => ['Xiaomi', 'Poco'],
            'other-brands' => ['Google', 'OnePlus', 'Huawei', 'Nokia', 'Sony', 'Motorola', 'Realme', 'Nothing'],
            'laptops' => ['Apple', 'Asus', 'Lenovo', 'Dell', 'HP', 'Acer', 'MSI', 'Razer'],
            'select-laptop' => ['Apple', 'Asus', 'Lenovo', 'Dell', 'HP', 'Acer', 'MSI', 'Razer'],
            'apple-macbooks' => ['Apple'],
            'asus-laptops' => ['Asus'],
            'lenovo-laptops' => ['Lenovo'],
            'gaming-laptops' => ['MSI', 'Razer', 'Acer', 'HP', 'Dell', 'Asus'],
            'business-laptops' => ['Dell', 'HP', 'Lenovo', 'Apple'],
            'student-laptops' => ['Acer', 'HP', 'Dell', 'Lenovo'],
            'digital-products' => ['Sony', 'Apple', 'Samsung', 'Xiaomi', 'JBL', 'Bose', 'Canon', 'Nikon', 'Anker', 'Nintendo', 'PlayStation', 'Xbox', 'Intel', 'NVIDIA', 'TP-Link', 'LG'],
            'select-digital' => ['Sony', 'Apple', 'Samsung', 'Xiaomi', 'JBL', 'Bose', 'Canon', 'Nikon', 'Anker', 'Nintendo', 'PlayStation', 'Xbox', 'Intel', 'NVIDIA', 'TP-Link', 'LG'],
            'gaming-consoles' => ['PlayStation', 'Xbox', 'Nintendo'],
            'headphones' => ['Sony', 'Apple', 'Samsung', 'JBL', 'Bose', 'Xiaomi', 'Beats'],
            'smartwatches' => ['Apple', 'Samsung', 'Xiaomi', 'Garmin'],
            'tablets' => ['Apple', 'Samsung', 'Xiaomi', 'Lenovo'],
            'speakers' => ['JBL', 'Sony', 'Bose', 'Xiaomi', 'Anker', 'Marshall'],
            'cameras' => ['Canon', 'Nikon', 'Sony'],
            'power-banks' => ['Anker', 'Xiaomi', 'Samsung'],
            'computer-components' => ['Intel', 'NVIDIA', 'AMD', 'Asus', 'MSI', 'Corsair', 'Samsung', 'Western Digital'],
            'smart-home' => ['Xiaomi', 'Google', 'Amazon', 'TP-Link'],
            'printers' => ['HP', 'Canon', 'Epson', 'Brother'],
            'storage-devices' => ['Samsung', 'Western Digital', 'Seagate', 'SanDisk', 'Kingston'],
            'networking' => ['TP-Link', 'Asus', 'D-Link', 'Anker'],
            'home-kitchen' => ['IKEA', 'Bosch', 'Philips', 'Kenwood', 'Zara Home'],
            'select-home' => ['IKEA', 'Bosch', 'Philips', 'Kenwood', 'Zara Home'],
            'cookware' => ['IKEA', 'Bosch', 'Kenwood', 'Philips'],
            'tea-coffee' => ['Philips', 'Kenwood', 'Bosch', 'IKEA'],
            'furniture' => ['IKEA', 'Zara Home', 'Casper'],
            'lighting' => ['IKEA', 'Philips', 'Zara Home'],
            'carpets-rugs' => ['IKEA', 'Zara Home'],
            'bedroom' => ['IKEA', 'Zara Home', 'Casper'],
            'home-appliances' => ['LG', 'Samsung', 'Bosch', 'Whirlpool', 'Snowa', 'Pakshoma', 'Sony', 'Toshiba', 'X-Vision', 'TCL', 'Daewoo'],
            'select-appliance' => ['LG', 'Samsung', 'Bosch', 'Whirlpool', 'Snowa', 'Pakshoma', 'Sony', 'Toshiba', 'X-Vision', 'TCL', 'Daewoo'],
            'refrigerators' => ['LG', 'Samsung', 'Bosch', 'Whirlpool', 'Snowa', 'Pakshoma'],
            'washing-machines' => ['LG', 'Samsung', 'Bosch', 'Whirlpool'],
            'dishwashers' => ['Bosch', 'LG', 'Samsung'],
            'vacuums' => ['LG', 'Samsung', 'Philips'],
            'cooking-appliances' => ['Philips', 'LG', 'Kenwood', 'Bosch', 'Toshiba'],
            'tvs' => ['Sony', 'Samsung', 'LG', 'X-Vision', 'TCL', 'Daewoo'],
            'beauty-health' => ['Loreal', 'Nivea', 'Chanel', 'Dior', 'Maybelline', 'Clinique', 'Philips'],
            'select-beauty' => ['Loreal', 'Nivea', 'Chanel', 'Dior', 'Maybelline', 'Clinique', 'Philips'],
            'skin-care' => ['Loreal', 'Nivea', 'Clinique', 'Chanel'],
            'makeup' => ['Maybelline', 'Loreal', 'Chanel', 'Clinique', 'Dior'],
            'hair-care' => ['Loreal', 'Philips', 'Nivea'],
            'perfumes' => ['Dior', 'Chanel', 'Versace', 'Gucci'],
            'oral-care' => ['Philips'],
            'personal-care' => ['Nivea', 'Loreal', 'Philips'],
            'fashion' => ['Nike', 'Adidas', 'Puma', 'Zara', 'H&M', 'Levis', 'Rolex', 'Seiko', 'Tissot'],
            'select-fashion' => ['Nike', 'Adidas', 'Puma', 'Zara', 'H&M', 'Levis', 'Rolex', 'Seiko', 'Tissot'],
            'mens-clothing' => ['Nike', 'Adidas', 'Puma', 'Zara', 'H&M', 'Levis'],
            'womens-clothing' => ['Zara', 'H&M', 'Nike', 'Adidas', 'Levis'],
            'childrens-clothing' => ['H&M', 'Zara', 'Nike', 'Adidas'],
            'shoes' => ['Nike', 'Adidas', 'Puma'],
            'bags-accessories' => ['Zara', 'H&M', 'Levis', 'Rolex', 'Seiko', 'Tissot'],
            'gold-jewelry' => ['Rolex', 'Omega', 'Seiko', 'Tissot', 'Citizen'],
            'select-jewelry' => ['Rolex', 'Omega', 'Seiko', 'Tissot', 'Citizen'],
            'gold-jewelry-items' => ['Rolex', 'Omega', 'Seiko', 'Tissot', 'Citizen'],
            'silver-jewelry' => ['Seiko', 'Citizen', 'Omega'],
            'diamonds-gems' => ['Rolex', 'Omega', 'Tissot'],
            'vehicles' => ['BMW', 'Mercedes', 'Toyota', 'Honda', 'Hyundai', 'Kia', 'Sony', 'Samsung', 'LG', 'Bosch'],
            'select-vehicle' => ['BMW', 'Mercedes', 'Toyota', 'Honda', 'Hyundai', 'Kia', 'Sony', 'Samsung', 'LG', 'Bosch'],
            'cars' => ['BMW', 'Mercedes', 'Toyota', 'Honda', 'Hyundai', 'Kia'],
            'motorcycles' => ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'BMW'],
            'car-accessories' => ['Sony', 'Samsung', 'LG', 'Bosch'],
            'health-medical' => ['Philips', 'Bosch', 'LG', 'Samsung', 'Nike', 'Adidas', 'Nivea'],
            'tools-equipment' => ['Makita', 'DeWalt', 'Bosch', 'Stanley', 'Milwaukee'],
            'books-art' => ['Apple', 'Samsung', 'Xiaomi', 'IKEA'],
            'sports-travel' => ['Nike', 'Adidas', 'Puma', 'Bosch', 'Makita', 'Stanley'],
            'gift-cards' => ['Apple', 'Samsung', 'Xiaomi', 'Sony', 'Google', 'PlayStation', 'Xbox', 'Nintendo'],
            'select-gift' => ['Apple', 'Samsung', 'Xiaomi', 'Sony', 'Google', 'PlayStation', 'Xbox', 'Nintendo'],
            'store-gift-cards' => ['Apple', 'Samsung', 'Xiaomi', 'Sony', 'Nintendo', 'PlayStation', 'Xbox'],
            'digital-gift-cards' => ['Apple', 'Google', 'Samsung', 'Sony', 'Nintendo', 'PlayStation', 'Xbox'],
        ];

        $totalConnected = 0;

        foreach ($brandCategoryMap as $slug => $brandNames) {
            $category = DB::table('categories')->where('slug', $slug)->first();
            
            if (!$category) {
                $this->command->warn("⚠️ Category not found: {$slug}");
                continue;
            }
            
            $brandIds = DB::table('brands')->whereIn('name', $brandNames)->pluck('id')->toArray();
            
            if (empty($brandIds)) {
                $this->command->warn("⚠️ No brands found for: " . implode(', ', $brandNames));
                continue;
            }
            
            foreach ($brandIds as $brandId) {
                DB::table('category_brand')->insert([
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $totalConnected++;
            }
            
            $this->command->line("  ✅ {$category->name}: " . count($brandIds) . " brand(s)");
        }

        $this->command->info("✅ {$totalConnected} brand-category connections created!");

        // ================================================================
        // 4. CREATE PRODUCTS (دقیقاً مشخص شده)
        // ================================================================
        $this->command->info('🔄 Creating products with exact categories...');

        // ✅ لیست دقیق محصولات با دسته‌بندی مشخص
        $productList = [
            // ===== آیفون =====
            ['title' => 'iPhone 16', 'slug' => 'iphone-16', 'brand' => 'Apple'],
            ['title' => 'iPhone 16 Pro', 'slug' => 'iphone-16-pro', 'brand' => 'Apple'],
            ['title' => 'iPhone 16 Pro Max', 'slug' => 'iphone-16-pro-max', 'brand' => 'Apple'],
            ['title' => 'iPhone 15', 'slug' => 'iphone-15', 'brand' => 'Apple'],
            ['title' => 'iPhone 15 Pro', 'slug' => 'iphone-15-pro', 'brand' => 'Apple'],
            ['title' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max', 'brand' => 'Apple'],
            ['title' => 'iPhone 14', 'slug' => 'iphone-14', 'brand' => 'Apple'],
            ['title' => 'iPhone SE', 'slug' => 'iphone-se', 'brand' => 'Apple'],
            
            // ===== سامسونگ =====
            ['title' => 'Galaxy S24 Ultra', 'slug' => 'galaxy-s24-ultra', 'brand' => 'Samsung'],
            ['title' => 'Galaxy S24 Plus', 'slug' => 'galaxy-s24-plus', 'brand' => 'Samsung'],
            ['title' => 'Galaxy S24', 'slug' => 'galaxy-s24', 'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Fold 6', 'slug' => 'galaxy-z-fold-6', 'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Flip 6', 'slug' => 'galaxy-z-flip-6', 'brand' => 'Samsung'],
            ['title' => 'Galaxy A55', 'slug' => 'galaxy-a55', 'brand' => 'Samsung'],
            ['title' => 'Galaxy A35', 'slug' => 'galaxy-a35', 'brand' => 'Samsung'],
            
            // ===== شیائومی =====
            ['title' => 'Xiaomi 14 Ultra', 'slug' => 'xiaomi-14-ultra', 'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14 Pro', 'slug' => 'xiaomi-14-pro', 'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14', 'slug' => 'xiaomi-14', 'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13 Pro', 'slug' => 'redmi-note-13-pro', 'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13', 'slug' => 'redmi-note-13', 'brand' => 'Xiaomi'],
            ['title' => 'Poco X7 Pro', 'slug' => 'poco-x7-pro', 'brand' => 'Poco'],
            
            // ===== سایر برندها =====
            ['title' => 'Google Pixel 8 Pro', 'slug' => 'google-pixel', 'brand' => 'Google'],
            ['title' => 'OnePlus 12', 'slug' => 'oneplus', 'brand' => 'OnePlus'],
            ['title' => 'Huawei P60 Pro', 'slug' => 'huawei', 'brand' => 'Huawei'],
            ['title' => 'Nokia X30', 'slug' => 'nokia', 'brand' => 'Nokia'],
            ['title' => 'Sony Xperia 1 V', 'slug' => 'sony-xperia', 'brand' => 'Sony'],
            ['title' => 'Motorola Edge 40', 'slug' => 'motorola', 'brand' => 'Motorola'],
            ['title' => 'Nothing Phone 2', 'slug' => 'nothing-phone', 'brand' => 'Nothing'],
            ['title' => 'Realme GT 3', 'slug' => 'realme', 'brand' => 'Realme'],
            
            // ===== لپ‌تاپ‌ها =====
            ['title' => 'MacBook Pro M3', 'slug' => 'macbook-pro-m3', 'brand' => 'Apple'],
            ['title' => 'MacBook Pro M4', 'slug' => 'macbook-pro-m4', 'brand' => 'Apple'],
            ['title' => 'MacBook Air M3', 'slug' => 'macbook-air-m3', 'brand' => 'Apple'],
            ['title' => 'MacBook Air M2', 'slug' => 'macbook-air-m2', 'brand' => 'Apple'],
            ['title' => 'ASUS ROG Zephyrus', 'slug' => 'asus-rog-zephyrus', 'brand' => 'Asus'],
            ['title' => 'ASUS TUF Gaming', 'slug' => 'asus-tuf-gaming', 'brand' => 'Asus'],
            ['title' => 'Lenovo ThinkPad X1', 'slug' => 'lenovo-thinkpad-x1', 'brand' => 'Lenovo'],
            ['title' => 'Lenovo Legion Pro', 'slug' => 'lenovo-legion-pro', 'brand' => 'Lenovo'],
            ['title' => 'MSI Titan GT77', 'slug' => 'msi-titan-gt77', 'brand' => 'MSI'],
            ['title' => 'Razer Blade 16', 'slug' => 'razer-blade-16', 'brand' => 'Razer'],
            ['title' => 'Dell XPS 16', 'slug' => 'dell-xps-16', 'brand' => 'Dell'],
            ['title' => 'Dell Alienware', 'slug' => 'dell-alienware', 'brand' => 'Dell'],
            ['title' => 'HP Spectre x360', 'slug' => 'hp-spectre-x360', 'brand' => 'HP'],
            ['title' => 'HP Omen', 'slug' => 'hp-omen', 'brand' => 'HP'],
            ['title' => 'Acer Aspire 5', 'slug' => 'acer-aspire-5', 'brand' => 'Acer'],
            ['title' => 'LG Gram', 'slug' => 'lg-gram', 'brand' => 'LG'],
            
            // ===== دیجیتال =====
            ['title' => 'PS5', 'slug' => 'ps5', 'brand' => 'PlayStation'],
            ['title' => 'PS5 Slim', 'slug' => 'ps5-slim', 'brand' => 'PlayStation'],
            ['title' => 'Xbox Series X', 'slug' => 'xbox-series-x', 'brand' => 'Xbox'],
            ['title' => 'Xbox Series S', 'slug' => 'xbox-series-s', 'brand' => 'Xbox'],
            ['title' => 'Nintendo Switch OLED', 'slug' => 'nintendo-switch-oled', 'brand' => 'Nintendo'],
            ['title' => 'Nintendo Switch Lite', 'slug' => 'nintendo-switch-lite', 'brand' => 'Nintendo'],
            ['title' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'brand' => 'Sony'],
            ['title' => 'Apple AirPods Pro 2', 'slug' => 'apple-airpods-pro-2', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Buds 2 Pro', 'slug' => 'samsung-buds-2-pro', 'brand' => 'Samsung'],
            ['title' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Watch 6', 'slug' => 'samsung-watch-6', 'brand' => 'Samsung'],
            ['title' => 'iPad Pro M4', 'slug' => 'ipad-pro-m4', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Tab S9', 'slug' => 'samsung-tab-s9', 'brand' => 'Samsung'],
            ['title' => 'JBL Charge 5', 'slug' => 'jbl-charge-5', 'brand' => 'JBL'],
            ['title' => 'Bose QC45', 'slug' => 'bose-qc45', 'brand' => 'Bose'],
            ['title' => 'Canon EOS R5', 'slug' => 'canon-eos-r5', 'brand' => 'Canon'],
            ['title' => 'Nikon Z8', 'slug' => 'nikon-z8', 'brand' => 'Nikon'],
            ['title' => 'Anker 20000mAh', 'slug' => 'anker-20000mah', 'brand' => 'Anker'],
            ['title' => 'Intel Core i9-14900K', 'slug' => 'intel-core-i9-14900k', 'brand' => 'Intel'],
            ['title' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090', 'brand' => 'NVIDIA'],
            ['title' => 'Xiaomi Smart Hub', 'slug' => 'xiaomi-smart-hub', 'brand' => 'Xiaomi'],
            ['title' => 'Google Nest Hub 2', 'slug' => 'google-nest-hub-2', 'brand' => 'Google'],
            ['title' => 'HP LaserJet Pro MFP', 'slug' => 'hp-laserjet-pro-mfp', 'brand' => 'HP'],
            ['title' => 'Samsung 1TB SSD', 'slug' => 'samsung-1tb-ssd', 'brand' => 'Samsung'],
            ['title' => 'TP-Link Router', 'slug' => 'tplink-router', 'brand' => 'TP-Link'],
            
            // ===== خانه و آشپزخانه =====
            ['title' => 'Non-Stick Frying Pan', 'slug' => 'non-stick-frying-pan', 'brand' => 'IKEA'],
            ['title' => 'Pressure Cooker', 'slug' => 'pressure-cooker', 'brand' => 'IKEA'],
            ['title' => 'Coffee Maker', 'slug' => 'coffee-maker', 'brand' => 'Philips'],
            ['title' => 'Electric Kettle', 'slug' => 'electric-kettle', 'brand' => 'Philips'],
            ['title' => 'Sofa Set', 'slug' => 'sofa-set', 'brand' => 'IKEA'],
            ['title' => 'Chandelier', 'slug' => 'chandelier', 'brand' => 'IKEA'],
            
            // ===== لوازم خانگی =====
            ['title' => 'LG Refrigerator', 'slug' => 'lg-refrigerator', 'brand' => 'LG'],
            ['title' => 'Samsung Refrigerator', 'slug' => 'samsung-refrigerator', 'brand' => 'Samsung'],
            ['title' => 'LG Washing Machine', 'slug' => 'lg-washing-machine', 'brand' => 'LG'],
            ['title' => 'Bosch Dishwasher', 'slug' => 'bosch-dishwasher', 'brand' => 'Bosch'],
            ['title' => 'Robot Vacuum', 'slug' => 'robot-vacuum', 'brand' => 'LG'],
            ['title' => 'Air Fryer', 'slug' => 'air-fryer', 'brand' => 'Philips'],
            ['title' => 'Microwave Oven', 'slug' => 'microwave-oven', 'brand' => 'LG'],
            ['title' => 'Sony OLED TV', 'slug' => 'sony-oled-tv', 'brand' => 'Sony'],
            ['title' => 'Samsung QLED TV', 'slug' => 'samsung-qled-tv', 'brand' => 'Samsung'],
            ['title' => 'LG OLED TV', 'slug' => 'lg-oled-tv', 'brand' => 'LG'],
            
            // ===== زیبایی =====
            ['title' => 'Moisturizer Cream', 'slug' => 'moisturizer-cream', 'brand' => 'Loreal'],
            ['title' => 'Sunscreen SPF 50', 'slug' => 'sunscreen-spf-50', 'brand' => 'Nivea'],
            ['title' => 'Lipstick', 'slug' => 'lipstick', 'brand' => 'Maybelline'],
            ['title' => 'Shampoo', 'slug' => 'shampoo', 'brand' => 'Loreal'],
            ['title' => 'Hair Dryer', 'slug' => 'hair-dryer', 'brand' => 'Philips'],
            ['title' => 'Dior Sauvage', 'slug' => 'dior-sauvage', 'brand' => 'Dior'],
            ['title' => 'Chanel No.5', 'slug' => 'chanel-no-5', 'brand' => 'Chanel'],
            ['title' => 'Electric Toothbrush', 'slug' => 'electric-toothbrush', 'brand' => 'Philips'],
            
            // ===== مد =====
            ['title' => 'Men\'s T-Shirt', 'slug' => 'mens-t-shirt', 'brand' => 'Nike'],
            ['title' => 'Men\'s Jeans', 'slug' => 'mens-jeans', 'brand' => 'Levis'],
            ['title' => 'Women\'s Dress', 'slug' => 'womens-dress', 'brand' => 'Zara'],
            ['title' => 'Manteau', 'slug' => 'manteau', 'brand' => 'Zara'],
            ['title' => 'Nike Air Max', 'slug' => 'nike-air-max', 'brand' => 'Nike'],
            ['title' => 'Adidas Ultraboost', 'slug' => 'adidas-ultraboost', 'brand' => 'Adidas'],
            ['title' => 'Rolex Watch', 'slug' => 'rolex-watch', 'brand' => 'Rolex'],
            
            // ===== طلا و جواهر =====
            ['title' => 'Gold Necklace', 'slug' => 'gold-necklace', 'brand' => 'Rolex'],
            ['title' => 'Gold Ring', 'slug' => 'gold-ring', 'brand' => 'Rolex'],
            ['title' => 'Diamond Ring', 'slug' => 'diamond-ring', 'brand' => 'Rolex'],
            ['title' => 'Silver Necklace', 'slug' => 'silver-necklace', 'brand' => 'Seiko'],
            
            // ===== وسایل نقلیه =====
            ['title' => 'BMW 5 Series', 'slug' => 'bmw-5-series', 'brand' => 'BMW'],
            ['title' => 'Mercedes E-Class', 'slug' => 'mercedes-e-class', 'brand' => 'Mercedes'],
            ['title' => 'Toyota Camry', 'slug' => 'toyota-camry', 'brand' => 'Toyota'],
            ['title' => 'Honda Civic', 'slug' => 'honda-civic', 'brand' => 'Honda'],
            ['title' => 'Honda CBR 500R', 'slug' => 'honda-cbr-500r', 'brand' => 'Honda'],
            
            // ===== سلامت =====
            ['title' => 'Blood Pressure Monitor', 'slug' => 'blood-pressure-monitor', 'brand' => 'Philips'],
            ['title' => 'Vitamin C', 'slug' => 'vitamin-c', 'brand' => 'Nivea'],
            ['title' => 'Omega-3', 'slug' => 'omega-3', 'brand' => 'Nivea'],
            ['title' => 'Treadmill', 'slug' => 'treadmill', 'brand' => 'Nike'],
            ['title' => 'Yoga Mat', 'slug' => 'yoga-mat', 'brand' => 'Nike'],
            
            // ===== ابزار =====
            ['title' => 'Makita Drill', 'slug' => 'makita-drill', 'brand' => 'Makita'],
            ['title' => 'DeWalt Grinder', 'slug' => 'dewalt-grinder', 'brand' => 'DeWalt'],
            ['title' => 'Screwdriver Set', 'slug' => 'screwdriver-set', 'brand' => 'Stanley'],
            
            // ===== کتاب و هنر =====
            ['title' => '1984 George Orwell', 'slug' => '1984-george-orwell', 'brand' => 'Apple'],
            ['title' => 'Atomic Habits', 'slug' => 'atomic-habits', 'brand' => 'Apple'],
            ['title' => 'Oil Painting Canvas', 'slug' => 'oil-painting-canvas', 'brand' => 'Apple'],
            
            // ===== ورزش و سفر =====
            ['title' => 'Boxing Punching Bag', 'slug' => 'boxing-punching-bag', 'brand' => 'Adidas'],
            ['title' => 'Suitcase 4 Wheels', 'slug' => 'suitcase-4-wheels', 'brand' => 'Adidas'],
            
            // ===== کارت هدیه =====
            ['title' => 'Digikala Gift Card', 'slug' => 'digikala-gift-card', 'brand' => 'Apple'],
            ['title' => 'PlayStation Gift Card', 'slug' => 'playstation-gift-card', 'brand' => 'PlayStation'],
            ['title' => 'Google Play Gift Card', 'slug' => 'google-play-gift-card', 'brand' => 'Google'],
        ];

        $productCount = 0;
        $variantCount = 0;
        $colors = ['Black', 'White', 'Silver', 'Gold', 'Blue', 'Red', 'Green', 'Purple', 'Pink', 'Space Gray'];
        $storages = ['64GB', '128GB', '256GB', '512GB', '1TB'];

        foreach ($productList as $productData) {
            // پیدا کردن دسته‌بندی دقیق
            $category = DB::table('categories')->where('slug', $productData['slug'])->first();
            
            if (!$category) {
                $this->command->warn("⚠️ Category not found: " . $productData['slug']);
                continue;
            }
            
            // پیدا کردن برند
            $brand = DB::table('brands')->where('name', $productData['brand'])->first();
            if (!$brand) {
                $this->command->warn("⚠️ Brand not found: " . $productData['brand']);
                continue;
            }

            $title = $productData['title'];
            $slug = Str::slug($title) . '-' . Str::random(6);
            $price = rand(100, 5000);
            $salePrice = $price * rand(7, 9) / 10;
            $salePrice = round($salePrice / 100) * 100;

            $description = "Premium " . $title . " with high-quality features. Perfect for everyday use.";

            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
                'slug' => $slug,
                'short_description' => Str::limit($description, 150),
                'description' => '<p>' . $description . '</p>',
                'status' => 'active',
                'meta_title' => $title . ' | Buy with best price',
                'meta_keywords' => $title . ', buy, shop, best price, ' . $productData['brand'],
                'meta_description' => 'Buy ' . $title . ' with best price.',
                'view_count' => rand(100, 50000),
                'rating' => rand(30, 50) / 10,
                'sort_order' => $productCount,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            // ✅ اضافه کردن عکس به محصول
$imageUrl = 'https://picsum.photos/seed/' . Str::slug($title) . '/300/300';

DB::table('product_images')->insert([
    'product_id' => $productId,
    'url' => $imageUrl,
    'sort_order' => 1,
    'is_main' => true,
    'created_at' => now(),
    'updated_at' => now(),
]);

// اضافه کردن ۱ تا ۲ عکس اضافی
$extraImages = rand(0, 2);
for ($i = 0; $i < $extraImages; $i++) {
    DB::table('product_images')->insert([
        'product_id' => $productId,
        'image_path' => 'https://picsum.photos/seed/' . Str::slug($title) . '-' . ($i + 2) . '/300/300',
        'alt' => $title,
        'sort_order' => $i + 2,
        'is_main' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

         // ================================================================
// ✅ اتصال محصول به دسته‌بندی‌ها
// ================================================================

// 1. اتصال به دسته خودش
DB::table('category_product')->insert([
    'category_id' => $category->id,
    'product_id' => $productId,
    'created_at' => now(),
    'updated_at' => now()
]);

// 2. پیدا کردن دسته والد (Select ...)
$parentCategory = DB::table('categories')->where('id', $category->parent_id)->first();

if ($parentCategory && str_starts_with($parentCategory->name, 'Select')) {
    // اتصال به دسته والد (Select ...)
    $exists = DB::table('category_product')
        ->where('category_id', $parentCategory->id)
        ->where('product_id', $productId)
        ->exists();
    
    if (!$exists) {
        DB::table('category_product')->insert([
            'category_id' => $parentCategory->id,
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

// 3. پیدا کردن دسته اصلی (Mobile, Laptops, ...)
$grandParentCategory = DB::table('categories')->where('id', $parentCategory->parent_id ?? null)->first();

if ($grandParentCategory) {
    // اتصال به دسته اصلی
    $exists = DB::table('category_product')
        ->where('category_id', $grandParentCategory->id)
        ->where('product_id', $productId)
        ->exists();
    
    if (!$exists) {
        DB::table('category_product')->insert([
            'category_id' => $grandParentCategory->id,
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
            // ایجاد تنوع‌ها
            $variantCountPerProduct = rand(3, 5);
            $isDefault = true;
            $usedColors = [];

            for ($v = 1; $v <= $variantCountPerProduct; $v++) {
                $availableColors = array_diff($colors, $usedColors);
                if (empty($availableColors)) break;
                $color = $availableColors[array_rand($availableColors)];
                $usedColors[] = $color;

                $storage = $storages[array_rand($storages)];

                $priceModifier = rand(85, 115) / 100;
                $variantPrice = round($price * $priceModifier, 2);
                $variantSalePrice = round($variantPrice * rand(7, 9) / 10, 2);

                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(4),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'price' => $variantPrice,
                    'sale_price' => $variantSalePrice,
                    'stock' => rand(5, 50),
                    'weight' => rand(100, 1000),
                    'is_active' => 1,
                    'is_default' => $isDefault,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // اضافه کردن ویژگی‌ها
                $this->attachVariantAttributes($variantId, $color, $storage);

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
        $this->command->info('📊 Total categories: ' . DB::table('categories')->count());
        $this->command->info('🏷️ Total brands: ' . DB::table('brands')->count());
        $this->command->info('📦 Total products: ' . DB::table('products')->count());
    }

    private function attachVariantAttributes($variantId, $color, $storage)
    {
        $colorAttr = DB::table('attributes')->where('slug', 'color')->first();
        if ($colorAttr) {
            $colorValue = DB::table('attribute_values')
                ->where('attribute_id', $colorAttr->id)
                ->where('value', $color)
                ->first();
            
            if ($colorValue) {
                DB::table('product_variant_attribute_values')->insert([
                    'product_variant_id' => $variantId,
                    'attribute_value_id' => $colorValue->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $storageAttr = DB::table('attributes')->where('slug', 'storage')->first();
        if ($storageAttr) {
            $storageValue = DB::table('attribute_values')
                ->where('attribute_id', $storageAttr->id)
                ->where('value', $storage)
                ->first();
            
            if ($storageValue) {
                DB::table('product_variant_attribute_values')->insert([
                    'product_variant_id' => $variantId,
                    'attribute_value_id' => $storageValue->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $extraAttrs = DB::table('attributes')
            ->whereIn('slug', ['ram', 'processor', 'battery', 'material', 'size'])
            ->pluck('id')
            ->toArray();

        if (!empty($extraAttrs)) {
            $selectedCount = min(rand(1, 2), count($extraAttrs));
            if ($selectedCount > 0) {
                $randomKeys = array_rand($extraAttrs, $selectedCount);
                $selectedAttrs = is_array($randomKeys) 
                    ? array_intersect_key($extraAttrs, array_flip($randomKeys))
                    : [$extraAttrs[$randomKeys]];

                foreach ($selectedAttrs as $attrId) {
                    $possibleValues = DB::table('attribute_values')
                        ->where('attribute_id', $attrId)
                        ->inRandomOrder()
                        ->first();
                    
                    if ($possibleValues) {
                        $exists = DB::table('product_variant_attribute_values')
                            ->where('product_variant_id', $variantId)
                            ->where('attribute_value_id', $possibleValues->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('product_variant_attribute_values')->insert([
                                'product_variant_id' => $variantId,
                                'attribute_value_id' => $possibleValues->id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }
        }
    }
}