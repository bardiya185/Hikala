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
            
            // بررسی وجود اسلاگ
            $existing = DB::table('categories')->where('slug', $baseSlug)->first();
            
            if ($existing) {
                // گرفتن نام دسته‌بندی والد
                $parent = DB::table('categories')->where('id', $parentId)->first();
                $parentSlug = $parent ? $parent->slug : 'sub';
                
                // اسلاگ جدید با نام والد
                $finalSlug = $baseSlug . '-' . $parentSlug;
                
                // اگر باز هم تکراری بود، عدد اضافه کن
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
        // 1. CREATE CATEGORIES
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
                            [
                                'name' => 'By Price',
                                'slug' => 'by-price',
                                'children' => [
                                    ['name' => 'Under 10 Million', 'slug' => 'under-10-million'],
                                    ['name' => '10-20 Million', 'slug' => '10-20-million'],
                                    ['name' => '20-30 Million', 'slug' => '20-30-million'],
                                    ['name' => '30-50 Million', 'slug' => '30-50-million'],
                                    ['name' => 'Above 50 Million', 'slug' => 'above-50-million'],
                                ]
                            ],
                            [
                                'name' => 'By Performance',
                                'slug' => 'by-performance',
                                'children' => [
                                    ['name' => 'Gaming Phones', 'slug' => 'gaming-phones'],
                                    ['name' => '5G Phones', 'slug' => '5g-phones'],
                                    ['name' => 'Waterproof Phones', 'slug' => 'waterproof-phones'],
                                    ['name' => 'Photography Phones', 'slug' => 'photography-phones'],
                                    ['name' => 'Flagship Phones', 'slug' => 'flagship-phones'],
                                ]
                            ],
                            [
                                'name' => 'By Storage',
                                'slug' => 'by-storage',
                                'children' => [
                                    ['name' => '128GB', 'slug' => '128gb-storage'],
                                    ['name' => '256GB', 'slug' => '256gb-storage'],
                                    ['name' => '512GB', 'slug' => '512gb-storage'],
                                    ['name' => '1TB', 'slug' => '1tb-storage'],
                                ]
                            ],
                            [
                                'name' => 'Mobile Accessories',
                                'slug' => 'mobile-accessories',
                                'children' => [
                                    ['name' => 'Phone Cases', 'slug' => 'phone-cases'],
                                    ['name' => 'Screen Protectors', 'slug' => 'screen-protectors'],
                                    ['name' => 'Chargers & Cables', 'slug' => 'chargers-cables'],
                                    ['name' => 'Power Banks', 'slug' => 'mobile-power-banks'],
                                    ['name' => 'Headphones', 'slug' => 'headphones-accessories'],
                                    ['name' => 'Smartwatches', 'slug' => 'smartwatches-accessories'],
                                ]
                            ],
                            [
                                'name' => 'Trending',
                                'slug' => 'trending-phones',
                                'children' => [
                                    ['name' => 'iPhone 17', 'slug' => 'iphone-17'],
                                    ['name' => 'Galaxy S25', 'slug' => 'galaxy-s25'],
                                    ['name' => 'Xiaomi 15', 'slug' => 'xiaomi-15'],
                                    ['name' => 'Poco X7 Pro', 'slug' => 'poco-x7-pro-trending'],
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
                            [
                                'name' => 'By Processor',
                                'slug' => 'by-processor',
                                'children' => [
                                    ['name' => 'Intel Core i9', 'slug' => 'intel-core-i9'],
                                    ['name' => 'Intel Core i7', 'slug' => 'intel-core-i7'],
                                    ['name' => 'Intel Core i5', 'slug' => 'intel-core-i5'],
                                    ['name' => 'AMD Ryzen 9', 'slug' => 'amd-ryzen-9'],
                                    ['name' => 'AMD Ryzen 7', 'slug' => 'amd-ryzen-7'],
                                ]
                            ],
                            [
                                'name' => 'By RAM',
                                'slug' => 'by-ram',
                                'children' => [
                                    ['name' => '64GB RAM', 'slug' => '64gb-ram'],
                                    ['name' => '32GB RAM', 'slug' => '32gb-ram'],
                                    ['name' => '16GB RAM', 'slug' => '16gb-ram'],
                                    ['name' => '8GB RAM', 'slug' => '8gb-ram'],
                                ]
                            ],
                            [
                                'name' => 'Laptop Accessories',
                                'slug' => 'laptop-accessories',
                                'children' => [
                                    ['name' => 'Laptop Bags', 'slug' => 'laptop-bags'],
                                    ['name' => 'Cooling Pads', 'slug' => 'cooling-pads'],
                                    ['name' => 'Laptop Chargers', 'slug' => 'laptop-chargers'],
                                    ['name' => 'Mouse & Mouse Pads', 'slug' => 'mouse-mousepads'],
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
                        'name' => 'Gaming Accessories',
                        'slug' => 'gaming-accessories',
                        'children' => [
                            ['name' => 'Game Controllers', 'slug' => 'game-controllers'],
                            ['name' => 'Gaming Headsets', 'slug' => 'gaming-headsets'],
                            ['name' => 'Gaming Keyboards', 'slug' => 'gaming-keyboards'],
                            ['name' => 'Gaming Mice', 'slug' => 'gaming-mice'],
                            ['name' => 'Racing Wheels', 'slug' => 'racing-wheels'],
                            ['name' => 'Gaming Chairs', 'slug' => 'gaming-chairs'],
                        ]
                    ],
                    [
                        'name' => 'Gaming Games',
                        'slug' => 'gaming-games',
                        'children' => [
                            ['name' => 'PS5 Games', 'slug' => 'ps5-games'],
                            ['name' => 'PS4 Games', 'slug' => 'ps4-games'],
                            ['name' => 'Xbox Games', 'slug' => 'xbox-games'],
                            ['name' => 'PC Games', 'slug' => 'pc-games'],
                            ['name' => 'Nintendo Games', 'slug' => 'nintendo-games'],
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
                            ['name' => 'Anker Soundcore Q45', 'slug' => 'anker-soundcore-q45'],
                            ['name' => 'Razer Barracuda Pro', 'slug' => 'razer-barracuda-pro'],
                        ]
                    ],
                    [
                        'name' => 'Smartwatches',
                        'slug' => 'smartwatches',
                        'children' => [
                            ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2'],
                            ['name' => 'Apple Watch Series 9', 'slug' => 'apple-watch-series-9'],
                            ['name' => 'Apple Watch SE 2', 'slug' => 'apple-watch-se-2'],
                            ['name' => 'Samsung Galaxy Watch 6', 'slug' => 'samsung-watch-6'],
                            ['name' => 'Samsung Galaxy Watch 6 Classic', 'slug' => 'samsung-watch-6-classic'],
                            ['name' => 'Xiaomi Watch S3', 'slug' => 'xiaomi-watch-s3'],
                            ['name' => 'Xiaomi Watch 2', 'slug' => 'xiaomi-watch-2'],
                            ['name' => 'Garmin Fenix 7', 'slug' => 'garmin-fenix-7'],
                        ]
                    ],
                    [
                        'name' => 'Tablets',
                        'slug' => 'tablets',
                        'children' => [
                            ['name' => 'iPad Pro M4', 'slug' => 'ipad-pro-m4'],
                            ['name' => 'iPad Air M2', 'slug' => 'ipad-air-m2'],
                            ['name' => 'iPad 10th Gen', 'slug' => 'ipad-10th-gen'],
                            ['name' => 'Samsung Galaxy Tab S9', 'slug' => 'samsung-tab-s9'],
                            ['name' => 'Samsung Galaxy Tab S9 Ultra', 'slug' => 'samsung-tab-s9-ultra'],
                            ['name' => 'Xiaomi Pad 6', 'slug' => 'xiaomi-pad-6'],
                            ['name' => 'Xiaomi Pad 6 Pro', 'slug' => 'xiaomi-pad-6-pro'],
                            ['name' => 'Lenovo Tab P12', 'slug' => 'lenovo-tab-p12'],
                        ]
                    ],
                    [
                        'name' => 'Speakers',
                        'slug' => 'speakers',
                        'children' => [
                            ['name' => 'JBL Charge 5', 'slug' => 'jbl-charge-5'],
                            ['name' => 'JBL Flip 6', 'slug' => 'jbl-flip-6'],
                            ['name' => 'Sony SRS-XG300', 'slug' => 'sony-srs-xg300'],
                            ['name' => 'Bose SoundLink Max', 'slug' => 'bose-soundlink-max'],
                            ['name' => 'Marshall Middleton', 'slug' => 'marshall-middleton'],
                            ['name' => 'Anker Soundcore Motion+', 'slug' => 'anker-soundcore-motion-plus'],
                        ]
                    ],
                    [
                        'name' => 'Cameras',
                        'slug' => 'cameras',
                        'children' => [
                            ['name' => 'Canon EOS R5', 'slug' => 'canon-eos-r5'],
                            ['name' => 'Canon EOS R6 Mark II', 'slug' => 'canon-eos-r6-mark-ii'],
                            ['name' => 'Sony Alpha A7 IV', 'slug' => 'sony-alpha-a7-iv'],
                            ['name' => 'Sony Alpha A7R V', 'slug' => 'sony-alpha-a7r-v'],
                            ['name' => 'Nikon Z8', 'slug' => 'nikon-z8'],
                            ['name' => 'Nikon Z9', 'slug' => 'nikon-z9'],
                            ['name' => 'DJI Pocket 3', 'slug' => 'dji-pocket-3'],
                            ['name' => 'Instax Mini 12', 'slug' => 'instax-mini-12'],
                        ]
                    ],
                    [
                        'name' => 'Power Banks',
                        'slug' => 'power-banks',
                        'children' => [
                            ['name' => 'Anker 20000mAh', 'slug' => 'anker-20000mah'],
                            ['name' => 'Anker 10000mAh', 'slug' => 'anker-10000mah'],
                            ['name' => 'Xiaomi 30000mAh', 'slug' => 'xiaomi-30000mah'],
                            ['name' => 'Xiaomi 20000mAh', 'slug' => 'xiaomi-20000mah'],
                            ['name' => 'Samsung 10000mAh', 'slug' => 'samsung-10000mah'],
                            ['name' => 'Apple MagSafe Battery', 'slug' => 'apple-magsafe-battery'],
                        ]
                    ],
                    [
                        'name' => 'Computer Components',
                        'slug' => 'computer-components',
                        'children' => [
                            ['name' => 'Intel Core i9-14900K', 'slug' => 'intel-core-i9-14900k'],
                            ['name' => 'Intel Core i7-14700K', 'slug' => 'intel-core-i7-14700k'],
                            ['name' => 'AMD Ryzen 9 7950X', 'slug' => 'amd-ryzen-9-7950x'],
                            ['name' => 'AMD Ryzen 7 7800X3D', 'slug' => 'amd-ryzen-7-7800x3d'],
                            ['name' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090'],
                            ['name' => 'NVIDIA RTX 4080 Super', 'slug' => 'nvidia-rtx-4080-super'],
                            ['name' => 'NVIDIA RTX 4070 Ti', 'slug' => 'nvidia-rtx-4070-ti'],
                            ['name' => 'ASUS ROG Motherboard Z790', 'slug' => 'asus-rog-z790'],
                            ['name' => 'MSI Motherboard B760', 'slug' => 'msi-b760'],
                            ['name' => 'Corsair Vengeance 32GB RAM', 'slug' => 'corsair-vengeance-32gb'],
                            ['name' => 'G.Skill Trident 16GB RAM', 'slug' => 'gskill-trident-16gb'],
                            ['name' => 'Samsung 990 Pro SSD', 'slug' => 'samsung-990-pro-ssd'],
                            ['name' => 'Western Digital Black SSD', 'slug' => 'wd-black-ssd'],
                        ]
                    ],
                    [
                        'name' => 'Smart Home',
                        'slug' => 'smart-home',
                        'children' => [
                            ['name' => 'Xiaomi Smart Hub', 'slug' => 'xiaomi-smart-hub'],
                            ['name' => 'Xiaomi Smart Light Bulb', 'slug' => 'xiaomi-smart-light'],
                            ['name' => 'Xiaomi Smart Plug', 'slug' => 'xiaomi-smart-plug'],
                            ['name' => 'Google Nest Hub 2', 'slug' => 'google-nest-hub-2'],
                            ['name' => 'Google Nest Mini', 'slug' => 'google-nest-mini'],
                            ['name' => 'Amazon Echo Show 8', 'slug' => 'amazon-echo-show-8'],
                            ['name' => 'Amazon Echo Dot', 'slug' => 'amazon-echo-dot'],
                            ['name' => 'TP-Link Smart Switch', 'slug' => 'tplink-smart-switch'],
                        ]
                    ],
                    [
                        'name' => 'Printers',
                        'slug' => 'printers',
                        'children' => [
                            ['name' => 'HP LaserJet Pro MFP', 'slug' => 'hp-laserjet-pro-mfp'],
                            ['name' => 'HP Deskjet 2755', 'slug' => 'hp-deskjet-2755'],
                            ['name' => 'Creality Ender 3 V2', 'slug' => 'creality-ender-3-v2'],
                            ['name' => 'Epson EcoTank L3110', 'slug' => 'epson-ecotank-l3110'],
                            ['name' => 'Brother Thermal Printer', 'slug' => 'brother-thermal-printer'],
                            ['name' => 'Canon ImageClass MF', 'slug' => 'canon-imageclass-mf'],
                        ]
                    ],
                    [
                        'name' => 'Storage Devices',
                        'slug' => 'storage-devices',
                        'children' => [
                            ['name' => 'Samsung 1TB SSD', 'slug' => 'samsung-1tb-ssd'],
                            ['name' => 'Western Digital 2TB HDD', 'slug' => 'wd-2tb-hdd'],
                            ['name' => 'Seagate 4TB External HDD', 'slug' => 'seagate-4tb-external'],
                            ['name' => 'SanDisk 128GB Flash', 'slug' => 'sandisk-128gb-flash'],
                            ['name' => 'SanDisk 64GB Flash', 'slug' => 'sandisk-64gb-flash'],
                            ['name' => 'Kingston 32GB Flash', 'slug' => 'kingston-32gb-flash'],
                        ]
                    ],
                    [
                        'name' => 'Networking',
                        'slug' => 'networking',
                        'children' => [
                            ['name' => 'TP-Link Router', 'slug' => 'tplink-router'],
                            ['name' => 'Asus Router', 'slug' => 'asus-router'],
                            ['name' => 'D-Link Switch', 'slug' => 'dlink-switch'],
                            ['name' => 'TP-Link USB Hub', 'slug' => 'tplink-usb-hub'],
                            ['name' => 'Anker USB Hub', 'slug' => 'anker-usb-hub'],
                            ['name' => 'CAT6 Ethernet Cable', 'slug' => 'cat6-ethernet-cable'],
                        ]
                    ],
                    [
                        'name' => 'Top Brands',
                        'slug' => 'digital-top-brands',
                        'children' => [
                            ['name' => 'Xiaomi', 'slug' => 'xiaomi-digital'],
                            ['name' => 'Samsung', 'slug' => 'samsung-digital'],
                            ['name' => 'Apple', 'slug' => 'apple-digital'],
                            ['name' => 'Sony', 'slug' => 'sony-digital'],
                            ['name' => 'JBL', 'slug' => 'jbl-digital'],
                            ['name' => 'Bose', 'slug' => 'bose-digital'],
                            ['name' => 'Anker', 'slug' => 'anker-digital'],
                            ['name' => 'TP-Link', 'slug' => 'tplink-digital'],
                            ['name' => 'Canon', 'slug' => 'canon-digital'],
                            ['name' => 'Nikon', 'slug' => 'nikon-digital'],
                            ['name' => 'Nintendo', 'slug' => 'nintendo-digital'],
                            ['name' => 'PlayStation', 'slug' => 'playstation-digital'],
                            ['name' => 'Xbox', 'slug' => 'xbox-digital'],
                        ]
                    ],
                    [
                        'name' => 'Trending',
                        'slug' => 'digital-trending',
                        'children' => [
                            ['name' => 'PS5 Bundle', 'slug' => 'ps5-bundle'],
                            ['name' => 'Nintendo Switch OLED', 'slug' => 'nintendo-switch-oled-trend'],
                            ['name' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5-trend'],
                            ['name' => 'Apple AirPods Pro 2', 'slug' => 'airpods-pro-2-trend'],
                            ['name' => 'Samsung Galaxy Watch 6', 'slug' => 'galaxy-watch-6-trend'],
                            ['name' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090-trend'],
                            ['name' => 'iPad Pro M4', 'slug' => 'ipad-pro-m4-trend'],
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
                        'name' => 'Cookware',
                        'slug' => 'cookware',
                        'children' => [
                            ['name' => 'Pots & Pans Set', 'slug' => 'pots-pans-set'],
                            ['name' => 'Non-Stick Frying Pan', 'slug' => 'non-stick-frying-pan'],
                            ['name' => 'Stainless Steel Pot', 'slug' => 'stainless-steel-pot'],
                            ['name' => 'Pressure Cooker', 'slug' => 'pressure-cooker'],
                            ['name' => 'Knife Set', 'slug' => 'knife-set'],
                            ['name' => 'Cutting Board Set', 'slug' => 'cutting-board-set'],
                            ['name' => 'Kitchen Scale', 'slug' => 'kitchen-scale'],
                        ]
                    ],
                    [
                        'name' => 'Tea & Coffee',
                        'slug' => 'tea-coffee',
                        'children' => [
                            ['name' => 'Coffee Maker', 'slug' => 'coffee-maker'],
                            ['name' => 'Espresso Machine', 'slug' => 'espresso-machine'],
                            ['name' => 'Electric Kettle', 'slug' => 'electric-kettle'],
                            ['name' => 'Tea Pot', 'slug' => 'tea-pot'],
                            ['name' => 'Samovar', 'slug' => 'samovar'],
                            ['name' => 'Coffee Grinder', 'slug' => 'coffee-grinder'],
                        ]
                    ],
                    [
                        'name' => 'Kitchenware',
                        'slug' => 'kitchenware',
                        'children' => [
                            ['name' => 'Glass Food Container', 'slug' => 'glass-food-container'],
                            ['name' => 'Spice Rack', 'slug' => 'spice-rack'],
                            ['name' => 'Kitchen Organizer', 'slug' => 'kitchen-organizer'],
                            ['name' => 'Colander', 'slug' => 'colander'],
                            ['name' => 'Mixing Bowl Set', 'slug' => 'mixing-bowl-set'],
                        ]
                    ],
                    [
                        'name' => 'Dining & Serving',
                        'slug' => 'dining-serving',
                        'children' => [
                            ['name' => 'Dinnerware Set', 'slug' => 'dinnerware-set'],
                            ['name' => 'Cutlery Set', 'slug' => 'cutlery-set'],
                            ['name' => 'Glass Set', 'slug' => 'glass-set'],
                            ['name' => 'Serving Tray', 'slug' => 'serving-tray'],
                            ['name' => 'Tablecloth', 'slug' => 'tablecloth'],
                            ['name' => 'Placemat Set', 'slug' => 'placemat-set'],
                        ]
                    ],
                    [
                        'name' => 'Furniture',
                        'slug' => 'furniture',
                        'children' => [
                            ['name' => 'Sofa Set', 'slug' => 'sofa-set'],
                            ['name' => 'Sofa Bed', 'slug' => 'sofa-bed'],
                            ['name' => 'Dining Table', 'slug' => 'dining-table'],
                            ['name' => 'Dining Chair Set', 'slug' => 'dining-chair-set'],
                            ['name' => 'TV Stand', 'slug' => 'tv-stand'],
                            ['name' => 'Bookshelf', 'slug' => 'bookshelf'],
                            ['name' => 'Office Chair', 'slug' => 'office-chair'],
                            ['name' => 'Office Desk', 'slug' => 'office-desk'],
                        ]
                    ],
                    [
                        'name' => 'Lighting',
                        'slug' => 'lighting',
                        'children' => [
                            ['name' => 'Chandelier', 'slug' => 'chandelier'],
                            ['name' => 'Table Lamp', 'slug' => 'table-lamp'],
                            ['name' => 'Floor Lamp', 'slug' => 'floor-lamp'],
                            ['name' => 'LED Light Strip', 'slug' => 'led-light-strip'],
                            ['name' => 'Ceiling Light', 'slug' => 'ceiling-light'],
                        ]
                    ],
                    [
                        'name' => 'Carpets & Rugs',
                        'slug' => 'carpets-rugs',
                        'children' => [
                            ['name' => 'Persian Carpet', 'slug' => 'persian-carpet'],
                            ['name' => 'Modern Rug', 'slug' => 'modern-rug'],
                            ['name' => 'Door Mat', 'slug' => 'door-mat'],
                            ['name' => 'Carpet Tapestry', 'slug' => 'carpet-tapestry'],
                        ]
                    ],
                    [
                        'name' => 'Home Decor',
                        'slug' => 'home-decor',
                        'children' => [
                            ['name' => 'Wall Mirror', 'slug' => 'wall-mirror'],
                            ['name' => 'Photo Frame', 'slug' => 'photo-frame'],
                            ['name' => 'Wall Clock', 'slug' => 'wall-clock'],
                            ['name' => 'Decorative Vase', 'slug' => 'decorative-vase'],
                            ['name' => 'Candle Holder', 'slug' => 'candle-holder'],
                            ['name' => 'Artificial Flower', 'slug' => 'artificial-flower'],
                        ]
                    ],
                    [
                        'name' => 'Bedroom',
                        'slug' => 'bedroom',
                        'children' => [
                            ['name' => 'King Size Bed', 'slug' => 'king-size-bed'],
                            ['name' => 'Queen Size Bed', 'slug' => 'queen-size-bed'],
                            ['name' => 'Mattress', 'slug' => 'mattress'],
                            ['name' => 'Pillow', 'slug' => 'pillow'],
                            ['name' => 'Bed Sheet Set', 'slug' => 'bed-sheet-set'],
                            ['name' => 'Duvet Cover', 'slug' => 'duvet-cover'],
                            ['name' => 'Comforter', 'slug' => 'comforter'],
                            ['name' => 'Wardrobe', 'slug' => 'wardrobe'],
                        ]
                    ],
                    [
                        'name' => 'Bathroom',
                        'slug' => 'bathroom',
                        'children' => [
                            ['name' => 'Towel Set', 'slug' => 'towel-set'],
                            ['name' => 'Bathroom Mirror', 'slug' => 'bathroom-mirror'],
                            ['name' => 'Shower Curtain', 'slug' => 'shower-curtain'],
                            ['name' => 'Toilet Seat', 'slug' => 'toilet-seat'],
                            ['name' => 'Toothbrush Holder', 'slug' => 'toothbrush-holder'],
                            ['name' => 'Soap Dispenser', 'slug' => 'soap-dispenser'],
                        ]
                    ],
                    [
                        'name' => 'Cleaning',
                        'slug' => 'cleaning',
                        'children' => [
                            ['name' => 'Mop & Bucket Set', 'slug' => 'mop-bucket-set'],
                            ['name' => 'Broom & Dustpan', 'slug' => 'broom-dustpan'],
                            ['name' => 'Lint Roller', 'slug' => 'lint-roller'],
                            ['name' => 'Laundry Basket', 'slug' => 'laundry-basket'],
                            ['name' => 'Ironing Board', 'slug' => 'ironing-board'],
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
                        'name' => 'Refrigerators',
                        'slug' => 'refrigerators',
                        'children' => [
                            ['name' => 'Side by Side Refrigerator', 'slug' => 'side-by-side-refrigerator'],
                            ['name' => 'French Door Refrigerator', 'slug' => 'french-door-refrigerator'],
                            ['name' => 'Top Freezer Refrigerator', 'slug' => 'top-freezer-refrigerator'],
                            ['name' => 'Mini Refrigerator', 'slug' => 'mini-refrigerator'],
                            ['name' => 'LG Refrigerator', 'slug' => 'lg-refrigerator'],
                            ['name' => 'Samsung Refrigerator', 'slug' => 'samsung-refrigerator'],
                            ['name' => 'Bosch Refrigerator', 'slug' => 'bosch-refrigerator'],
                        ]
                    ],
                    [
                        'name' => 'Washing Machines',
                        'slug' => 'washing-machines',
                        'children' => [
                            ['name' => 'Front Load Washer', 'slug' => 'front-load-washer'],
                            ['name' => 'Top Load Washer', 'slug' => 'top-load-washer'],
                            ['name' => 'Washer Dryer Combo', 'slug' => 'washer-dryer-combo'],
                            ['name' => 'LG Washing Machine', 'slug' => 'lg-washing-machine'],
                            ['name' => 'Samsung Washing Machine', 'slug' => 'samsung-washing-machine'],
                            ['name' => 'Bosch Washing Machine', 'slug' => 'bosch-washing-machine'],
                            ['name' => 'Whirlpool Washing Machine', 'slug' => 'whirlpool-washing-machine'],
                        ]
                    ],
                    [
                        'name' => 'Dishwashers',
                        'slug' => 'dishwashers',
                        'children' => [
                            ['name' => 'Bosch Dishwasher', 'slug' => 'bosch-dishwasher'],
                            ['name' => 'LG Dishwasher', 'slug' => 'lg-dishwasher'],
                            ['name' => 'Samsung Dishwasher', 'slug' => 'samsung-dishwasher'],
                            ['name' => 'Whirlpool Dishwasher', 'slug' => 'whirlpool-dishwasher'],
                            ['name' => 'Portable Dishwasher', 'slug' => 'portable-dishwasher'],
                        ]
                    ],
                    [
                        'name' => 'Vacuums',
                        'slug' => 'vacuums',
                        'children' => [
                            ['name' => 'Robot Vacuum', 'slug' => 'robot-vacuum'],
                            ['name' => 'Cordless Vacuum', 'slug' => 'cordless-vacuum'],
                            ['name' => 'Canister Vacuum', 'slug' => 'canister-vacuum'],
                            ['name' => 'Handheld Vacuum', 'slug' => 'handheld-vacuum'],
                            ['name' => 'LG Robot Vacuum', 'slug' => 'lg-robot-vacuum'],
                            ['name' => 'Samsung Robot Vacuum', 'slug' => 'samsung-robot-vacuum'],
                        ]
                    ],
                    [
                        'name' => 'Cooking Appliances',
                        'slug' => 'cooking-appliances',
                        'children' => [
                            ['name' => 'Air Fryer', 'slug' => 'air-fryer'],
                            ['name' => 'Microwave Oven', 'slug' => 'microwave-oven'],
                            ['name' => 'Toaster Oven', 'slug' => 'toaster-oven'],
                            ['name' => 'Rice Cooker', 'slug' => 'rice-cooker'],
                            ['name' => 'Electric Kettle', 'slug' => 'electric-kettle-appliances'],
                            ['name' => 'Coffee Maker', 'slug' => 'coffee-maker-appliances'],
                            ['name' => 'Espresso Machine', 'slug' => 'espresso-machine-appliances'],
                            ['name' => 'Blender', 'slug' => 'blender'],
                            ['name' => 'Food Processor', 'slug' => 'food-processor'],
                            ['name' => 'Juicer', 'slug' => 'juicer'],
                        ]
                    ],
                    [
                        'name' => 'Air Conditioners',
                        'slug' => 'air-conditioners',
                        'children' => [
                            ['name' => 'Split AC', 'slug' => 'split-ac'],
                            ['name' => 'Window AC', 'slug' => 'window-ac'],
                            ['name' => 'Portable AC', 'slug' => 'portable-ac'],
                            ['name' => 'Inverter AC', 'slug' => 'inverter-ac'],
                            ['name' => 'LG AC', 'slug' => 'lg-ac'],
                            ['name' => 'Samsung AC', 'slug' => 'samsung-ac'],
                            ['name' => 'Gree AC', 'slug' => 'gree-ac'],
                        ]
                    ],
                    [
                        'name' => 'Heaters & Fans',
                        'slug' => 'heaters-fans',
                        'children' => [
                            ['name' => 'Electric Heater', 'slug' => 'electric-heater'],
                            ['name' => 'Space Heater', 'slug' => 'space-heater'],
                            ['name' => 'Stand Fan', 'slug' => 'stand-fan'],
                            ['name' => 'Ceiling Fan', 'slug' => 'ceiling-fan'],
                            ['name' => 'Tower Fan', 'slug' => 'tower-fan'],
                            ['name' => 'Rechargeable Fan', 'slug' => 'rechargeable-fan'],
                        ]
                    ],
                    [
                        'name' => 'TVs',
                        'slug' => 'tvs',
                        'children' => [
                            ['name' => 'Sony OLED TV', 'slug' => 'sony-oled-tv'],
                            ['name' => 'Samsung QLED TV', 'slug' => 'samsung-qled-tv'],
                            ['name' => 'LG OLED TV', 'slug' => 'lg-oled-tv'],
                            ['name' => 'TCL TV', 'slug' => 'tcl-tv'],
                            ['name' => 'X-Vision TV', 'slug' => 'x-vision-tv'],
                            ['name' => '55 Inch TV', 'slug' => '55-inch-tv'],
                            ['name' => '65 Inch TV', 'slug' => '65-inch-tv'],
                            ['name' => '75 Inch TV', 'slug' => '75-inch-tv'],
                            ['name' => '85 Inch TV', 'slug' => '85-inch-tv'],
                            ['name' => '4K TV', 'slug' => '4k-tv'],
                            ['name' => 'OLED TV', 'slug' => 'oled-tv'],
                            ['name' => 'QLED TV', 'slug' => 'qled-tv'],
                        ]
                    ],
                    [
                        'name' => 'Audio & Video',
                        'slug' => 'audio-video',
                        'children' => [
                            ['name' => 'Soundbar', 'slug' => 'soundbar'],
                            ['name' => 'Home Theater', 'slug' => 'home-theater'],
                            ['name' => 'Bluetooth Speaker', 'slug' => 'bluetooth-speaker'],
                            ['name' => 'Projector', 'slug' => 'projector'],
                            ['name' => 'Android TV Box', 'slug' => 'android-tv-box'],
                            ['name' => 'Remote Control', 'slug' => 'remote-control'],
                        ]
                    ],
                    [
                        'name' => 'Sewing Machines',
                        'slug' => 'sewing-machines',
                        'children' => [
                            ['name' => 'Janome Sewing Machine', 'slug' => 'janome-sewing-machine'],
                            ['name' => 'Brother Sewing Machine', 'slug' => 'brother-sewing-machine'],
                            ['name' => 'Industrial Sewing Machine', 'slug' => 'industrial-sewing-machine'],
                            ['name' => 'Mini Sewing Machine', 'slug' => 'mini-sewing-machine'],
                        ]
                    ],
                    [
                        'name' => 'Water Purifiers',
                        'slug' => 'water-purifiers',
                        'children' => [
                            ['name' => 'Reverse Osmosis System', 'slug' => 'reverse-osmosis-system'],
                            ['name' => 'Water Filter Pitcher', 'slug' => 'water-filter-pitcher'],
                            ['name' => 'Under Sink Water Filter', 'slug' => 'under-sink-water-filter'],
                            ['name' => 'Countertop Water Filter', 'slug' => 'countertop-water-filter'],
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
                        'name' => 'Skin Care',
                        'slug' => 'skin-care',
                        'children' => [
                            ['name' => 'Moisturizer Cream', 'slug' => 'moisturizer-cream'],
                            ['name' => 'Sunscreen SPF 50', 'slug' => 'sunscreen-spf-50'],
                            ['name' => 'Face Serum', 'slug' => 'face-serum'],
                            ['name' => 'Face Mask', 'slug' => 'face-mask'],
                            ['name' => 'Eye Cream', 'slug' => 'eye-cream'],
                            ['name' => 'Face Cleanser', 'slug' => 'face-cleanser'],
                            ['name' => 'Toner', 'slug' => 'toner'],
                            ['name' => 'Exfoliating Scrub', 'slug' => 'exfoliating-scrub'],
                        ]
                    ],
                    [
                        'name' => 'Makeup',
                        'slug' => 'makeup',
                        'children' => [
                            ['name' => 'Foundation', 'slug' => 'foundation'],
                            ['name' => 'Concealer', 'slug' => 'concealer'],
                            ['name' => 'Blush', 'slug' => 'blush'],
                            ['name' => 'Mascara', 'slug' => 'mascara'],
                            ['name' => 'Eyeliner', 'slug' => 'eyeliner'],
                            ['name' => 'Lipstick', 'slug' => 'lipstick'],
                            ['name' => 'Lip Gloss', 'slug' => 'lip-gloss'],
                            ['name' => 'Makeup Brush Set', 'slug' => 'makeup-brush-set'],
                            ['name' => 'Eyeshadow Palette', 'slug' => 'eyeshadow-palette'],
                            ['name' => 'Setting Spray', 'slug' => 'setting-spray'],
                        ]
                    ],
                    [
                        'name' => 'Hair Care',
                        'slug' => 'hair-care',
                        'children' => [
                            ['name' => 'Shampoo', 'slug' => 'shampoo'],
                            ['name' => 'Conditioner', 'slug' => 'conditioner'],
                            ['name' => 'Hair Mask', 'slug' => 'hair-mask'],
                            ['name' => 'Hair Oil', 'slug' => 'hair-oil'],
                            ['name' => 'Hair Spray', 'slug' => 'hair-spray'],
                            ['name' => 'Hair Dryer', 'slug' => 'hair-dryer'],
                            ['name' => 'Hair Straightener', 'slug' => 'hair-straightener'],
                            ['name' => 'Hair Curler', 'slug' => 'hair-curler'],
                        ]
                    ],
                    [
                        'name' => 'Perfumes',
                        'slug' => 'perfumes',
                        'children' => [
                            ['name' => 'Dior Sauvage', 'slug' => 'dior-sauvage'],
                            ['name' => 'Chanel No.5', 'slug' => 'chanel-no-5'],
                            ['name' => 'YSL La Nuit', 'slug' => 'ysl-la-nuit'],
                            ['name' => 'Versace Eros', 'slug' => 'versace-eros'],
                            ['name' => 'Gucci Bloom', 'slug' => 'gucci-bloom'],
                            ['name' => 'Paco Rabanne Invictus', 'slug' => 'paco-rabanne-invictus'],
                            ['name' => 'Women\'s Perfume', 'slug' => 'womens-perfume'],
                            ['name' => 'Men\'s Perfume', 'slug' => 'mens-perfume'],
                            ['name' => 'Pocket Perfume', 'slug' => 'pocket-perfume'],
                            ['name' => 'Body Spray', 'slug' => 'body-spray'],
                        ]
                    ],
                    [
                        'name' => 'Oral Care',
                        'slug' => 'oral-care',
                        'children' => [
                            ['name' => 'Electric Toothbrush', 'slug' => 'electric-toothbrush'],
                            ['name' => 'Toothbrush', 'slug' => 'toothbrush'],
                            ['name' => 'Toothpaste', 'slug' => 'toothpaste'],
                            ['name' => 'Dental Floss', 'slug' => 'dental-floss'],
                            ['name' => 'Mouthwash', 'slug' => 'mouthwash'],
                            ['name' => 'Teeth Whitening Kit', 'slug' => 'teeth-whitening-kit'],
                        ]
                    ],
                    [
                        'name' => 'Personal Care',
                        'slug' => 'personal-care',
                        'children' => [
                            ['name' => 'Deodorant', 'slug' => 'deodorant'],
                            ['name' => 'Body Wash', 'slug' => 'body-wash'],
                            ['name' => 'Men\'s Body Wash', 'slug' => 'mens-body-wash'],
                            ['name' => 'Women\'s Body Wash', 'slug' => 'womens-body-wash'],
                            ['name' => 'Shaving Kit', 'slug' => 'shaving-kit'],
                            ['name' => 'Razor', 'slug' => 'razor'],
                            ['name' => 'Hair Removal Cream', 'slug' => 'hair-removal-cream'],
                        ]
                    ],
                    [
                        'name' => 'Health Supplements',
                        'slug' => 'health-supplements',
                        'children' => [
                            ['name' => 'Vitamin C', 'slug' => 'vitamin-c'],
                            ['name' => 'Vitamin D3', 'slug' => 'vitamin-d3'],
                            ['name' => 'Omega-3', 'slug' => 'omega-3'],
                            ['name' => 'Magnesium', 'slug' => 'magnesium'],
                            ['name' => 'Zinc', 'slug' => 'zinc'],
                            ['name' => 'B-Complex', 'slug' => 'b-complex'],
                            ['name' => 'Protein Powder', 'slug' => 'protein-powder'],
                            ['name' => 'Collagen', 'slug' => 'collagen'],
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
                        'name' => 'Men\'s Clothing',
                        'slug' => 'mens-clothing',
                        'children' => [
                            ['name' => 'Men\'s T-Shirt', 'slug' => 'mens-t-shirt'],
                            ['name' => 'Men\'s Shirt', 'slug' => 'mens-shirt'],
                            ['name' => 'Men\'s Jeans', 'slug' => 'mens-jeans'],
                            ['name' => 'Men\'s Suit', 'slug' => 'mens-suit'],
                            ['name' => 'Men\'s Jacket', 'slug' => 'mens-jacket'],
                            ['name' => 'Men\'s Coat', 'slug' => 'mens-coat'],
                            ['name' => 'Men\'s Hoodie', 'slug' => 'mens-hoodie'],
                            ['name' => 'Men\'s Sweatshirt', 'slug' => 'mens-sweatshirt'],
                            ['name' => 'Men\'s Pants', 'slug' => 'mens-pants'],
                            ['name' => 'Men\'s Shorts', 'slug' => 'mens-shorts'],
                            ['name' => 'Men\'s Underwear', 'slug' => 'mens-underwear'],
                            ['name' => 'Men\'s Socks', 'slug' => 'mens-socks'],
                            ['name' => 'Men\'s Belt', 'slug' => 'mens-belt'],
                            ['name' => 'Men\'s Tie', 'slug' => 'mens-tie'],
                        ]
                    ],
                    [
                        'name' => 'Women\'s Clothing',
                        'slug' => 'womens-clothing',
                        'children' => [
                            ['name' => 'Women\'s Dress', 'slug' => 'womens-dress'],
                            ['name' => 'Women\'s Blouse', 'slug' => 'womens-blouse'],
                            ['name' => 'Women\'s Jeans', 'slug' => 'womens-jeans'],
                            ['name' => 'Women\'s Skirt', 'slug' => 'womens-skirt'],
                            ['name' => 'Women\'s Jacket', 'slug' => 'womens-jacket'],
                            ['name' => 'Women\'s Coat', 'slug' => 'womens-coat'],
                            ['name' => 'Women\'s T-Shirt', 'slug' => 'womens-t-shirt'],
                            ['name' => 'Women\'s Hoodie', 'slug' => 'womens-hoodie'],
                            ['name' => 'Women\'s Pants', 'slug' => 'womens-pants'],
                            ['name' => 'Women\'s Leggings', 'slug' => 'womens-leggings'],
                            ['name' => 'Women\'s Underwear', 'slug' => 'womens-underwear'],
                            ['name' => 'Women\'s Bra', 'slug' => 'womens-bra'],
                            ['name' => 'Manteau', 'slug' => 'manteau'],
                            ['name' => 'Long Manteau', 'slug' => 'long-manteau'],
                            ['name' => 'Short Manteau', 'slug' => 'short-manteau'],
                            ['name' => 'Open Manteau', 'slug' => 'open-manteau'],
                            ['name' => 'Closed Manteau', 'slug' => 'closed-manteau'],
                            ['name' => 'Formal Manteau', 'slug' => 'formal-manteau'],
                            ['name' => 'Headscarf', 'slug' => 'headscarf'],
                            ['name' => 'Women\'s Belt', 'slug' => 'womens-belt'],
                        ]
                    ],
                    [
                        'name' => 'Children\'s Clothing',
                        'slug' => 'childrens-clothing',
                        'children' => [
                            ['name' => 'Baby Bodysuit', 'slug' => 'baby-bodysuit'],
                            ['name' => 'Baby Set', 'slug' => 'baby-set'],
                            ['name' => 'Toddler T-Shirt', 'slug' => 'toddler-t-shirt'],
                            ['name' => 'Toddler Pants', 'slug' => 'toddler-pants'],
                            ['name' => 'Girls Dress', 'slug' => 'girls-dress'],
                            ['name' => 'Boys T-Shirt', 'slug' => 'boys-t-shirt'],
                            ['name' => 'Kids Jacket', 'slug' => 'kids-jacket'],
                            ['name' => 'Kids Jeans', 'slug' => 'kids-jeans'],
                            ['name' => 'Kids Sneakers', 'slug' => 'kids-sneakers'],
                            ['name' => 'Kids Sandals', 'slug' => 'kids-sandals'],
                        ]
                    ],
                    [
                        'name' => 'Shoes',
                        'slug' => 'shoes',
                        'children' => [
                            ['name' => 'Nike Air Max', 'slug' => 'nike-air-max'],
                            ['name' => 'Nike Air Force 1', 'slug' => 'nike-air-force-1'],
                            ['name' => 'Adidas Ultraboost', 'slug' => 'adidas-ultraboost'],
                            ['name' => 'Adidas Stan Smith', 'slug' => 'adidas-stan-smith'],
                            ['name' => 'Puma RS-X', 'slug' => 'puma-rs-x'],
                            ['name' => 'Men\'s Sneakers', 'slug' => 'mens-sneakers'],
                            ['name' => 'Women\'s Sneakers', 'slug' => 'womens-sneakers'],
                            ['name' => 'Men\'s Boots', 'slug' => 'mens-boots'],
                            ['name' => 'Women\'s Boots', 'slug' => 'womens-boots'],
                            ['name' => 'Men\'s Sandals', 'slug' => 'mens-sandals'],
                            ['name' => 'Women\'s Sandals', 'slug' => 'womens-sandals'],
                            ['name' => 'Men\'s Slippers', 'slug' => 'mens-slippers'],
                            ['name' => 'Women\'s Slippers', 'slug' => 'womens-slippers'],
                            ['name' => 'Leather Shoes', 'slug' => 'leather-shoes'],
                            ['name' => 'Sports Shoes', 'slug' => 'sports-shoes'],
                            ['name' => 'College Shoes', 'slug' => 'college-shoes'],
                            ['name' => 'Giveh', 'slug' => 'giveh'],
                        ]
                    ],
                    [
                        'name' => 'Bags & Accessories',
                        'slug' => 'bags-accessories',
                        'children' => [
                            ['name' => 'Men\'s Wallet', 'slug' => 'mens-wallet'],
                            ['name' => 'Women\'s Handbag', 'slug' => 'womens-handbag'],
                            ['name' => 'Backpack', 'slug' => 'backpack'],
                            ['name' => 'Leather Bag', 'slug' => 'leather-bag'],
                            ['name' => 'Belt', 'slug' => 'belt'],
                            ['name' => 'Hat', 'slug' => 'hat'],
                            ['name' => 'Scarf', 'slug' => 'scarf'],
                            ['name' => 'Sunglasses', 'slug' => 'sunglasses'],
                            ['name' => 'Watch', 'slug' => 'watch'],
                            ['name' => 'Men\'s Watch', 'slug' => 'mens-watch'],
                            ['name' => 'Women\'s Watch', 'slug' => 'womens-watch'],
                            ['name' => 'Casio Watch', 'slug' => 'casio-watch'],
                            ['name' => 'Seiko Watch', 'slug' => 'seiko-watch'],
                            ['name' => 'Rolex Watch', 'slug' => 'rolex-watch'],
                            ['name' => 'Tissot Watch', 'slug' => 'tissot-watch'],
                            ['name' => 'Citizen Watch', 'slug' => 'citizen-watch'],
                        ]
                    ],
                    [
                        'name' => 'Sportswear',
                        'slug' => 'fashion-sportswear',
                        'children' => [
                            ['name' => 'Sports T-Shirt', 'slug' => 'fashion-sports-t-shirt'],
                            ['name' => 'Sports Shorts', 'slug' => 'fashion-sports-shorts'],
                            ['name' => 'Track Suit', 'slug' => 'fashion-track-suit'],
                            ['name' => 'Yoga Pants', 'slug' => 'fashion-yoga-pants'],
                            ['name' => 'Sports Bra', 'slug' => 'fashion-sports-bra'],
                            ['name' => 'Compression Wear', 'slug' => 'fashion-compression-wear'],
                            ['name' => 'Sweatband', 'slug' => 'fashion-sweatband'],
                            ['name' => 'Sport Socks', 'slug' => 'fashion-sport-socks'],
                        ]
                    ],
                    [
                        'name' => 'Fashion Brands',
                        'slug' => 'fashion-brands',
                        'children' => [
                            ['name' => 'Zara', 'slug' => 'zara'],
                            ['name' => 'H&M', 'slug' => 'hm'],
                            ['name' => 'Levi\'s', 'slug' => 'levis'],
                            ['name' => 'Nike', 'slug' => 'nike'],
                            ['name' => 'Adidas', 'slug' => 'adidas'],
                            ['name' => 'Puma', 'slug' => 'puma'],
                            ['name' => 'Novin Charm', 'slug' => 'novin-charm'],
                            ['name' => 'Charm Mashhad', 'slug' => 'charm-mashhad'],
                            ['name' => 'Asmara', 'slug' => 'asmara'],
                            ['name' => 'Serjeh', 'slug' => 'serjeh'],
                            ['name' => 'Gordieh', 'slug' => 'gordieh'],
                            ['name' => 'Charm Ataroud', 'slug' => 'charm-ataroud'],
                            ['name' => 'Tolika', 'slug' => 'tolika'],
                            ['name' => 'Pama', 'slug' => 'pama'],
                            ['name' => 'I-Tech', 'slug' => 'i-tech'],
                        ]
                    ],
                    [
                        'name' => 'Trending Fashion',
                        'slug' => 'trending-fashion',
                        'children' => [
                            ['name' => 'Mom Fit Jeans', 'slug' => 'mom-fit-jeans'],
                            ['name' => 'Oversized T-Shirt', 'slug' => 'oversized-t-shirt'],
                            ['name' => 'Cargo Pants', 'slug' => 'cargo-pants'],
                            ['name' => 'Puffer Jacket', 'slug' => 'puffer-jacket'],
                            ['name' => 'Leather Jacket', 'slug' => 'leather-jacket'],
                            ['name' => 'Knit Sweater', 'slug' => 'knit-sweater'],
                            ['name' => 'Hoodie', 'slug' => 'hoodie'],
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
                        'name' => 'Gold Jewelry',
                        'slug' => 'gold-jewelry-items',
                        'children' => [
                            ['name' => 'Gold Necklace', 'slug' => 'gold-necklace'],
                            ['name' => 'Gold Ring', 'slug' => 'gold-ring'],
                            ['name' => 'Gold Earrings', 'slug' => 'gold-earrings'],
                            ['name' => 'Gold Bracelet', 'slug' => 'gold-bracelet'],
                            ['name' => 'Gold Anklet', 'slug' => 'gold-anklet'],
                            ['name' => 'Gold Chain', 'slug' => 'gold-chain'],
                            ['name' => 'Gold Pendant', 'slug' => 'gold-pendant'],
                            ['name' => 'Gold Set', 'slug' => 'gold-set'],
                            ['name' => '22K Gold', 'slug' => '22k-gold'],
                            ['name' => '24K Gold', 'slug' => '24k-gold'],
                            ['name' => 'Gold Under 10M', 'slug' => 'gold-under-10m'],
                            ['name' => 'Gold Under 15M', 'slug' => 'gold-under-15m'],
                            ['name' => 'Gold Under 20M', 'slug' => 'gold-under-20m'],
                        ]
                    ],
                    [
                        'name' => 'Silver Jewelry',
                        'slug' => 'silver-jewelry',
                        'children' => [
                            ['name' => 'Silver Necklace', 'slug' => 'silver-necklace'],
                            ['name' => 'Silver Ring', 'slug' => 'silver-ring'],
                            ['name' => 'Silver Earrings', 'slug' => 'silver-earrings'],
                            ['name' => 'Silver Bracelet', 'slug' => 'silver-bracelet'],
                            ['name' => 'Silver Chain', 'slug' => 'silver-chain'],
                            ['name' => 'Silver Anklet', 'slug' => 'silver-anklet'],
                            ['name' => '925 Silver', 'slug' => '925-silver'],
                        ]
                    ],
                    [
                        'name' => 'Diamonds & Gems',
                        'slug' => 'diamonds-gems',
                        'children' => [
                            ['name' => 'Diamond Ring', 'slug' => 'diamond-ring'],
                            ['name' => 'Diamond Necklace', 'slug' => 'diamond-necklace'],
                            ['name' => 'Diamond Earrings', 'slug' => 'diamond-earrings'],
                            ['name' => 'Ruby Jewelry', 'slug' => 'ruby-jewelry'],
                            ['name' => 'Sapphire Jewelry', 'slug' => 'sapphire-jewelry'],
                            ['name' => 'Emerald Jewelry', 'slug' => 'emerald-jewelry'],
                            ['name' => 'Pearl Jewelry', 'slug' => 'pearl-jewelry'],
                            ['name' => 'Gemstone Ring', 'slug' => 'gemstone-ring'],
                        ]
                    ],
                    [
                        'name' => 'Gold Coins & Bars',
                        'slug' => 'gold-coins-bars',
                        'children' => [
                            ['name' => 'Gold Bar', 'slug' => 'gold-bar'],
                            ['name' => 'Gold Coin', 'slug' => 'gold-coin'],
                            ['name' => 'Quarter Coin', 'slug' => 'quarter-coin'],
                            ['name' => 'Half Coin', 'slug' => 'half-coin'],
                            ['name' => 'Full Coin', 'slug' => 'full-coin'],
                            ['name' => 'Parsian Coin', 'slug' => 'parsian-coin'],
                            ['name' => 'Melted Gold', 'slug' => 'melted-gold'],
                        ]
                    ],
                    [
                        'name' => 'Gold Galleries',
                        'slug' => 'gold-galleries',
                        'children' => [
                            ['name' => 'Ruby Art', 'slug' => 'ruby-art'],
                            ['name' => 'Eli Gallery', 'slug' => 'eli-gallery'],
                            ['name' => 'Mavi Gallery', 'slug' => 'mavi-gallery'],
                            ['name' => 'Mostajabi', 'slug' => 'mostajabi'],
                            ['name' => 'Taj', 'slug' => 'taj'],
                            ['name' => 'Daris', 'slug' => 'daris'],
                            ['name' => 'Mio Gold', 'slug' => 'mio-gold'],
                            ['name' => 'Parasteh Gallery', 'slug' => 'parasteh-gallery'],
                            ['name' => 'Sheida Majd', 'slug' => 'sheida-majd'],
                            ['name' => 'Kia Gallery', 'slug' => 'kia-gallery'],
                            ['name' => 'Naria', 'slug' => 'naria'],
                            ['name' => 'Maya Mahak', 'slug' => 'maya-mahak'],
                            ['name' => 'Gol Dam', 'slug' => 'gol-dam'],
                            ['name' => 'Hor Gold Gallery', 'slug' => 'hor-gold-gallery'],
                            ['name' => 'Parsis Gold', 'slug' => 'parsis-gold'],
                            ['name' => 'Tokeniko', 'slug' => 'tokeniko'],
                            ['name' => 'ZIOTO', 'slug' => 'zioto'],
                            ['name' => 'Brillian Gold', 'slug' => 'brillian-gold'],
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
                        'name' => 'Cars',
                        'slug' => 'cars',
                        'children' => [
                            ['name' => 'BMW 5 Series', 'slug' => 'bmw-5-series'],
                            ['name' => 'Mercedes E-Class', 'slug' => 'mercedes-e-class'],
                            ['name' => 'Toyota Camry', 'slug' => 'toyota-camry'],
                            ['name' => 'Honda Civic', 'slug' => 'honda-civic'],
                            ['name' => 'Hyundai Sonata', 'slug' => 'hyundai-sonata'],
                            ['name' => 'Kia Sportage', 'slug' => 'kia-sportage'],
                            ['name' => 'Peugeot 206', 'slug' => 'peugeot-206'],
                            ['name' => 'Peugeot 207', 'slug' => 'peugeot-207'],
                            ['name' => 'Peugeot 405', 'slug' => 'peugeot-405'],
                            ['name' => 'Renault Sandero', 'slug' => 'renault-sandero'],
                            ['name' => 'Pride', 'slug' => 'pride'],
                            ['name' => 'Tiba', 'slug' => 'tiba'],
                            ['name' => 'Samand', 'slug' => 'samand'],
                            ['name' => 'Dena', 'slug' => 'dena'],
                            ['name' => 'Quick', 'slug' => 'quick'],
                            ['name' => 'Saina', 'slug' => 'saina'],
                            ['name' => 'MVM 315', 'slug' => 'mvm-315'],
                            ['name' => 'MVM 530', 'slug' => 'mvm-530'],
                            ['name' => 'Lifan 620', 'slug' => 'lifan-620'],
                            ['name' => 'Lifan X50', 'slug' => 'lifan-x50'],
                        ]
                    ],
                    [
                        'name' => 'Motorcycles',
                        'slug' => 'motorcycles',
                        'children' => [
                            ['name' => 'Honda CBR 500R', 'slug' => 'honda-cbr-500r'],
                            ['name' => 'Honda CB 650R', 'slug' => 'honda-cb-650r'],
                            ['name' => 'BMW R 1250 GS', 'slug' => 'bmw-r-1250-gs'],
                            ['name' => 'Yamaha MT-07', 'slug' => 'yamaha-mt-07'],
                            ['name' => 'Suzuki GSX-R750', 'slug' => 'suzuki-gsx-r750'],
                            ['name' => 'Kawasaki Ninja 400', 'slug' => 'kawasaki-ninja-400'],
                            ['name' => 'Dirt Bike', 'slug' => 'dirt-bike'],
                            ['name' => 'Electric Scooter', 'slug' => 'electric-scooter'],
                        ]
                    ],
                    [
                        'name' => 'Car Accessories',
                        'slug' => 'car-accessories',
                        'children' => [
                            ['name' => 'Car Alloy Rims', 'slug' => 'car-alloy-rims'],
                            ['name' => 'Car Audio System', 'slug' => 'car-audio-system'],
                            ['name' => 'Car Speakers', 'slug' => 'car-speakers'],
                            ['name' => 'Car Amplifier', 'slug' => 'car-amplifier'],
                            ['name' => 'Dashcam', 'slug' => 'dashcam'],
                            ['name' => 'GPS Navigation', 'slug' => 'gps-navigation'],
                            ['name' => 'Car Seat Covers', 'slug' => 'car-seat-covers'],
                            ['name' => 'Car Floor Mats', 'slug' => 'car-floor-mats'],
                            ['name' => 'Car Tinting', 'slug' => 'car-tinting'],
                            ['name' => 'Car Sunshade', 'slug' => 'car-sunshade'],
                            ['name' => 'Car Cover', 'slug' => 'car-cover'],
                            ['name' => 'Car Phone Holder', 'slug' => 'car-phone-holder'],
                            ['name' => 'Car Air Freshener', 'slug' => 'car-air-freshener'],
                            ['name' => 'Car Roof Rack', 'slug' => 'car-roof-rack'],
                            ['name' => 'Car Tire Chains', 'slug' => 'car-tire-chains'],
                            ['name' => 'Car Jack', 'slug' => 'car-jack'],
                            ['name' => 'Car Emergency Kit', 'slug' => 'car-emergency-kit'],
                        ]
                    ],
                    [
                        'name' => 'Motorcycle Accessories',
                        'slug' => 'motorcycle-accessories',
                        'children' => [
                            ['name' => 'Motorcycle Helmet', 'slug' => 'motorcycle-helmet'],
                            ['name' => 'Motorcycle Jacket', 'slug' => 'motorcycle-jacket'],
                            ['name' => 'Motorcycle Gloves', 'slug' => 'motorcycle-gloves'],
                            ['name' => 'Motorcycle Boots', 'slug' => 'motorcycle-boots'],
                            ['name' => 'Motorcycle Cover', 'slug' => 'motorcycle-cover'],
                            ['name' => 'Motorcycle Phone Mount', 'slug' => 'motorcycle-phone-mount'],
                            ['name' => 'Motorcycle Saddlebag', 'slug' => 'motorcycle-saddlebag'],
                        ]
                    ],
                    [
                        'name' => 'Car Consumables',
                        'slug' => 'car-consumables',
                        'children' => [
                            ['name' => 'Engine Oil', 'slug' => 'engine-oil'],
                            ['name' => 'Transmission Oil', 'slug' => 'transmission-oil'],
                            ['name' => 'Brake Fluid', 'slug' => 'brake-fluid'],
                            ['name' => 'Antifreeze', 'slug' => 'antifreeze'],
                            ['name' => 'Car Battery', 'slug' => 'car-battery'],
                            ['name' => 'Oil Filter', 'slug' => 'oil-filter'],
                            ['name' => 'Air Filter', 'slug' => 'air-filter'],
                            ['name' => 'Brake Pads', 'slug' => 'brake-pads'],
                            ['name' => 'Spark Plug', 'slug' => 'spark-plug'],
                            ['name' => 'Wiper Blade', 'slug' => 'wiper-blade'],
                            ['name' => 'Car Tire', 'slug' => 'car-tire'],
                        ]
                    ],
                    [
                        'name' => 'By Car Model',
                        'slug' => 'by-car-model',
                        'children' => [
                            ['name' => 'Peugeot 206-207', 'slug' => 'peugeot-206-207'],
                            ['name' => 'Peugeot 405-Parsia', 'slug' => 'peugeot-405-parsia'],
                            ['name' => 'Pride-Tiba', 'slug' => 'pride-tiba'],
                            ['name' => 'Samand-Dena', 'slug' => 'samand-dena'],
                            ['name' => 'Quick-Saina', 'slug' => 'quick-saina'],
                            ['name' => 'Renault Sandero', 'slug' => 'renault-sandero-car'],
                            ['name' => 'MVM-Phoenix', 'slug' => 'mvm-phoenix'],
                            ['name' => 'Hyundai-Kia', 'slug' => 'hyundai-kia'],
                            ['name' => 'Toyota-Renault', 'slug' => 'toyota-renault'],
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
                        'name' => 'Medical Equipment',
                        'slug' => 'medical-equipment',
                        'children' => [
                            ['name' => 'Blood Pressure Monitor', 'slug' => 'blood-pressure-monitor'],
                            ['name' => 'Digital Thermometer', 'slug' => 'digital-thermometer'],
                            ['name' => 'Glucose Meter', 'slug' => 'glucose-meter'],
                            ['name' => 'Nebulizer', 'slug' => 'nebulizer'],
                            ['name' => 'Pulse Oximeter', 'slug' => 'pulse-oximeter'],
                            ['name' => 'Stethoscope', 'slug' => 'stethoscope'],
                            ['name' => 'Hearing Aid', 'slug' => 'hearing-aid'],
                            ['name' => 'Medical Mask', 'slug' => 'medical-mask'],
                            ['name' => 'Medical Gloves', 'slug' => 'medical-gloves'],
                        ]
                    ],
                    [
                        'name' => 'Orthopedic',
                        'slug' => 'orthopedic',
                        'children' => [
                            ['name' => 'Back Brace', 'slug' => 'back-brace'],
                            ['name' => 'Knee Brace', 'slug' => 'knee-brace'],
                            ['name' => 'Wrist Brace', 'slug' => 'wrist-brace'],
                            ['name' => 'Ankle Support', 'slug' => 'ankle-support'],
                            ['name' => 'Neck Brace', 'slug' => 'neck-brace'],
                            ['name' => 'Orthopedic Shoes', 'slug' => 'orthopedic-shoes'],
                            ['name' => 'Orthopedic Insole', 'slug' => 'orthopedic-insole'],
                            ['name' => 'Compression Socks', 'slug' => 'compression-socks'],
                        ]
                    ],
                    [
                        'name' => 'Supplements',
                        'slug' => 'supplements',
                        'children' => [
                            ['name' => 'Vitamin C', 'slug' => 'vitamin-c-supplement'],
                            ['name' => 'Vitamin D3', 'slug' => 'vitamin-d3-supplement'],
                            ['name' => 'Omega-3', 'slug' => 'omega-3-supplement'],
                            ['name' => 'Magnesium', 'slug' => 'magnesium-supplement'],
                            ['name' => 'Zinc', 'slug' => 'zinc-supplement'],
                            ['name' => 'B-Complex', 'slug' => 'b-complex-supplement'],
                            ['name' => 'Protein Powder', 'slug' => 'protein-powder-supplement'],
                            ['name' => 'Collagen', 'slug' => 'collagen-supplement'],
                            ['name' => 'Probiotic', 'slug' => 'probiotic-supplement'],
                            ['name' => 'Multivitamin', 'slug' => 'multivitamin-supplement'],
                            ['name' => 'Calcium', 'slug' => 'calcium-supplement'],
                            ['name' => 'Iron Supplement', 'slug' => 'iron-supplement-item'],
                        ]
                    ],
                    [
                        'name' => 'Dental Care',
                        'slug' => 'dental-care',
                        'children' => [
                            ['name' => 'Electric Toothbrush', 'slug' => 'electric-toothbrush'],
                            ['name' => 'Toothbrush', 'slug' => 'toothbrush'],
                            ['name' => 'Toothpaste', 'slug' => 'toothpaste'],
                            ['name' => 'Dental Floss', 'slug' => 'dental-floss'],
                            ['name' => 'Mouthwash', 'slug' => 'mouthwash'],
                            ['name' => 'Teeth Whitening Kit', 'slug' => 'teeth-whitening-kit'],
                            ['name' => 'Water Flosser', 'slug' => 'water-flosser'],
                            ['name' => 'Tongue Cleaner', 'slug' => 'tongue-cleaner'],
                        ]
                    ],
                    [
                        'name' => 'First Aid',
                        'slug' => 'first-aid',
                        'children' => [
                            ['name' => 'First Aid Kit', 'slug' => 'first-aid-kit'],
                            ['name' => 'Bandage', 'slug' => 'bandage'],
                            ['name' => 'Gauze', 'slug' => 'gauze'],
                            ['name' => 'Medical Tape', 'slug' => 'medical-tape'],
                            ['name' => 'Antiseptic Cream', 'slug' => 'antiseptic-cream'],
                            ['name' => 'Pain Reliever', 'slug' => 'pain-reliever'],
                            ['name' => 'Cold Pack', 'slug' => 'cold-pack'],
                            ['name' => 'Hot Pack', 'slug' => 'hot-pack'],
                            ['name' => 'Band-aid', 'slug' => 'band-aid'],
                        ]
                    ],
                    [
                        'name' => 'Fitness Equipment',
                        'slug' => 'fitness-equipment',
                        'children' => [
                            ['name' => 'Treadmill', 'slug' => 'treadmill'],
                            ['name' => 'Exercise Bike', 'slug' => 'exercise-bike'],
                            ['name' => 'Dumbbell Set', 'slug' => 'dumbbell-set'],
                            ['name' => 'Yoga Mat', 'slug' => 'yoga-mat'],
                            ['name' => 'Resistance Band', 'slug' => 'resistance-band'],
                            ['name' => 'Kettlebell', 'slug' => 'kettlebell'],
                            ['name' => 'Pull Up Bar', 'slug' => 'pull-up-bar'],
                            ['name' => 'Ab Wheel', 'slug' => 'ab-wheel'],
                            ['name' => 'Jump Rope', 'slug' => 'jump-rope'],
                            ['name' => 'Fitness Ball', 'slug' => 'fitness-ball'],
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
                        'name' => 'Power Tools',
                        'slug' => 'power-tools',
                        'children' => [
                            ['name' => 'Electric Drill', 'slug' => 'electric-drill'],
                            ['name' => 'Angle Grinder', 'slug' => 'angle-grinder'],
                            ['name' => 'Circular Saw', 'slug' => 'circular-saw'],
                            ['name' => 'Jigsaw', 'slug' => 'jigsaw'],
                            ['name' => 'Impact Wrench', 'slug' => 'impact-wrench'],
                            ['name' => 'Sander', 'slug' => 'sander'],
                            ['name' => 'Router', 'slug' => 'router-tool'],
                            ['name' => 'Planer', 'slug' => 'planer'],
                            ['name' => 'Makita Drill', 'slug' => 'makita-drill'],
                            ['name' => 'DeWalt Grinder', 'slug' => 'dewalt-grinder'],
                            ['name' => 'Bosch Saw', 'slug' => 'bosch-saw'],
                        ]
                    ],
                    [
                        'name' => 'Hand Tools',
                        'slug' => 'hand-tools',
                        'children' => [
                            ['name' => 'Screwdriver Set', 'slug' => 'screwdriver-set'],
                            ['name' => 'Wrench Set', 'slug' => 'wrench-set'],
                            ['name' => 'Pliers', 'slug' => 'pliers'],
                            ['name' => 'Hammer', 'slug' => 'hammer'],
                            ['name' => 'Tape Measure', 'slug' => 'tape-measure'],
                            ['name' => 'Level', 'slug' => 'level'],
                            ['name' => 'Utility Knife', 'slug' => 'utility-knife'],
                            ['name' => 'Stanley Tool Set', 'slug' => 'stanley-tool-set'],
                            ['name' => 'Milwaukee Tool Set', 'slug' => 'milwaukee-tool-set'],
                        ]
                    ],
                    [
                        'name' => 'Gardening Tools',
                        'slug' => 'gardening-tools',
                        'children' => [
                            ['name' => 'Lawn Mower', 'slug' => 'lawn-mower'],
                            ['name' => 'Hedge Trimmer', 'slug' => 'hedge-trimmer'],
                            ['name' => 'Garden Shears', 'slug' => 'garden-shears'],
                            ['name' => 'Shovel', 'slug' => 'shovel'],
                            ['name' => 'Rake', 'slug' => 'rake'],
                            ['name' => 'Hoe', 'slug' => 'hoe'],
                            ['name' => 'Watering Can', 'slug' => 'watering-can'],
                            ['name' => 'Garden Hose', 'slug' => 'garden-hose'],
                            ['name' => 'Pruning Saw', 'slug' => 'pruning-saw'],
                        ]
                    ],
                    [
                        'name' => 'Safety Equipment',
                        'slug' => 'safety-equipment',
                        'children' => [
                            ['name' => 'Safety Helmet', 'slug' => 'safety-helmet'],
                            ['name' => 'Safety Glasses', 'slug' => 'safety-glasses'],
                            ['name' => 'Work Gloves', 'slug' => 'work-gloves'],
                            ['name' => 'Ear Protection', 'slug' => 'ear-protection'],
                            ['name' => 'Safety Boots', 'slug' => 'safety-boots'],
                            ['name' => 'High Visibility Vest', 'slug' => 'high-visibility-vest'],
                            ['name' => 'Knee Pads', 'slug' => 'knee-pads'],
                            ['name' => 'Dust Mask', 'slug' => 'dust-mask'],
                        ]
                    ],
                    [
                        'name' => 'Measuring Tools',
                        'slug' => 'measuring-tools',
                        'children' => [
                            ['name' => 'Laser Measure', 'slug' => 'laser-measure'],
                            ['name' => 'Spirit Level', 'slug' => 'spirit-level'],
                            ['name' => 'Caliper', 'slug' => 'caliper'],
                            ['name' => 'Micrometer', 'slug' => 'micrometer'],
                            ['name' => 'Angle Finder', 'slug' => 'angle-finder'],
                            ['name' => 'Tape Measure 50m', 'slug' => 'tape-measure-50m'],
                        ]
                    ],
                    [
                        'name' => 'Tool Sets',
                        'slug' => 'tool-sets',
                        'children' => [
                            ['name' => 'Mechanic Tool Set', 'slug' => 'mechanic-tool-set'],
                            ['name' => 'Home Tool Set', 'slug' => 'home-tool-set'],
                            ['name' => 'Electrician Tool Set', 'slug' => 'electrician-tool-set'],
                            ['name' => 'Automotive Tool Set', 'slug' => 'automotive-tool-set'],
                            ['name' => 'Precision Tool Set', 'slug' => 'precision-tool-set'],
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
                        'name' => 'Books',
                        'slug' => 'books',
                        'children' => [
                            ['name' => '1984 George Orwell', 'slug' => '1984-george-orwell'],
                            ['name' => 'Animal Farm', 'slug' => 'animal-farm'],
                            ['name' => 'Metamorphosis Kafka', 'slug' => 'metamorphosis-kafka'],
                            ['name' => 'The Trial', 'slug' => 'the-trial'],
                            ['name' => 'Blindness', 'slug' => 'blindness'],
                            ['name' => 'The Alchemist', 'slug' => 'the-alchemist'],
                            ['name' => 'Atomic Habits', 'slug' => 'atomic-habits'],
                            ['name' => 'Rich Dad Poor Dad', 'slug' => 'rich-dad-poor-dad'],
                            ['name' => 'Think and Grow Rich', 'slug' => 'think-and-grow-rich'],
                            ['name' => 'Psychology of Money', 'slug' => 'psychology-of-money'],
                            ['name' => 'Deep Work', 'slug' => 'deep-work'],
                            ['name' => 'The Power of Habit', 'slug' => 'the-power-of-habit'],
                            ['name' => 'Sapiens', 'slug' => 'sapiens'],
                            ['name' => 'Homo Deus', 'slug' => 'homo-deus'],
                            ['name' => '21 Lessons for 21st Century', 'slug' => '21-lessons'],
                            ['name' => 'Persian Poetry', 'slug' => 'persian-poetry'],
                            ['name' => 'Rumi Poetry', 'slug' => 'rumi-poetry'],
                            ['name' => 'Hafez Poetry', 'slug' => 'hafez-poetry'],
                            ['name' => 'Saadi Poetry', 'slug' => 'saadi-poetry'],
                        ]
                    ],
                    [
                        'name' => 'Art & Painting',
                        'slug' => 'art-painting',
                        'children' => [
                            ['name' => 'Oil Painting Canvas', 'slug' => 'oil-painting-canvas'],
                            ['name' => 'Watercolor Set', 'slug' => 'watercolor-set'],
                            ['name' => 'Calligraphy Set', 'slug' => 'calligraphy-set'],
                            ['name' => 'Art Brush Set', 'slug' => 'art-brush-set'],
                            ['name' => 'Acrylic Paint', 'slug' => 'acrylic-paint'],
                            ['name' => 'Sketchbook', 'slug' => 'sketchbook'],
                            ['name' => 'Drawing Pencil Set', 'slug' => 'drawing-pencil-set'],
                            ['name' => 'Canvas Board', 'slug' => 'canvas-board'],
                            ['name' => 'Easel', 'slug' => 'easel'],
                            ['name' => 'Palette', 'slug' => 'palette'],
                            ['name' => 'Art Print', 'slug' => 'art-print'],
                            ['name' => 'Framed Painting', 'slug' => 'framed-painting'],
                        ]
                    ],
                    [
                        'name' => 'Handicrafts',
                        'slug' => 'handicrafts',
                        'children' => [
                            ['name' => 'Pottery Vase', 'slug' => 'pottery-vase'],
                            ['name' => 'Ceramic Bowl', 'slug' => 'ceramic-bowl'],
                            ['name' => 'Wood Carving', 'slug' => 'wood-carving'],
                            ['name' => 'Metal Sculpture', 'slug' => 'metal-sculpture'],
                            ['name' => 'Handmade Rug', 'slug' => 'handmade-rug'],
                            ['name' => 'Handwoven Basket', 'slug' => 'handwoven-basket'],
                            ['name' => 'Glass Art', 'slug' => 'glass-art'],
                            ['name' => 'Handmade Jewelry', 'slug' => 'handmade-jewelry'],
                            ['name' => 'Embroidered Textile', 'slug' => 'embroidered-textile'],
                            ['name' => 'Traditional Pottery', 'slug' => 'traditional-pottery'],
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
                        'name' => 'Sports Equipment',
                        'slug' => 'sports-equipment',
                        'children' => [
                            ['name' => 'Boxing Punching Bag', 'slug' => 'boxing-punching-bag'],
                            ['name' => 'Boxing Gloves', 'slug' => 'boxing-gloves'],
                            ['name' => 'Basketball', 'slug' => 'basketball'],
                            ['name' => 'Football', 'slug' => 'football-sport'],
                            ['name' => 'Volleyball', 'slug' => 'volleyball'],
                            ['name' => 'Tennis Racket', 'slug' => 'tennis-racket'],
                            ['name' => 'Tennis Ball Set', 'slug' => 'tennis-ball-set'],
                            ['name' => 'Badminton Set', 'slug' => 'badminton-set'],
                            ['name' => 'Table Tennis Set', 'slug' => 'table-tennis-set'],
                            ['name' => 'Yoga Mat', 'slug' => 'yoga-mat'],
                            ['name' => 'Yoga Block', 'slug' => 'yoga-block'],
                            ['name' => 'Yoga Strap', 'slug' => 'yoga-strap'],
                            ['name' => 'Resistance Band Set', 'slug' => 'resistance-band-set'],
                            ['name' => 'Dumbbell Set', 'slug' => 'dumbbell-set'],
                            ['name' => 'Kettlebell', 'slug' => 'kettlebell'],
                            ['name' => 'Pull Up Bar', 'slug' => 'pull-up-bar'],
                            ['name' => 'Ab Wheel', 'slug' => 'ab-wheel'],
                            ['name' => 'Jump Rope', 'slug' => 'jump-rope'],
                        ]
                    ],
                    [
                        'name' => 'Sportswear',
                        'slug' => 'travel-sportswear',
                        'children' => [
                            ['name' => 'Sports T-Shirt', 'slug' => 'travel-sports-t-shirt'],
                            ['name' => 'Sports Shorts', 'slug' => 'travel-sports-shorts'],
                            ['name' => 'Track Suit', 'slug' => 'travel-track-suit'],
                            ['name' => 'Compression Shirt', 'slug' => 'travel-compression-shirt'],
                            ['name' => 'Compression Pants', 'slug' => 'travel-compression-pants'],
                            ['name' => 'Sports Bra', 'slug' => 'travel-sports-bra'],
                            ['name' => 'Sports Socks', 'slug' => 'travel-sports-socks'],
                            ['name' => 'Sweatband', 'slug' => 'travel-sweatband'],
                            ['name' => 'Headband', 'slug' => 'travel-headband'],
                            ['name' => 'Wristband', 'slug' => 'travel-wristband'],
                            ['name' => 'Sports Jacket', 'slug' => 'travel-sports-jacket'],
                            ['name' => 'Sports Pants', 'slug' => 'travel-sports-pants'],
                            ['name' => 'Nike Sportswear', 'slug' => 'travel-nike-sportswear'],
                            ['name' => 'Adidas Sportswear', 'slug' => 'travel-adidas-sportswear'],
                            ['name' => 'Puma Sportswear', 'slug' => 'travel-puma-sportswear'],
                        ]
                    ],
                    [
                        'name' => 'Travel Equipment',
                        'slug' => 'travel-equipment',
                        'children' => [
                            ['name' => 'Suitcase 4 Wheels', 'slug' => 'suitcase-4-wheels'],
                            ['name' => 'Travel Backpack', 'slug' => 'travel-backpack'],
                            ['name' => 'Travel Bag', 'slug' => 'travel-bag'],
                            ['name' => 'Travel Pillow', 'slug' => 'travel-pillow'],
                            ['name' => 'Luggage Tag', 'slug' => 'luggage-tag'],
                            ['name' => 'Travel Adapter', 'slug' => 'travel-adapter'],
                            ['name' => 'Travel Wallet', 'slug' => 'travel-wallet'],
                            ['name' => 'Travel Toiletry Bag', 'slug' => 'travel-toiletry-bag'],
                            ['name' => 'Travel Scale', 'slug' => 'travel-scale'],
                            ['name' => 'Travel Umbrella', 'slug' => 'travel-umbrella'],
                            ['name' => 'Neck Wallet', 'slug' => 'neck-wallet'],
                        ]
                    ],
                    [
                        'name' => 'Camping Gear',
                        'slug' => 'camping-gear',
                        'children' => [
                            ['name' => 'Camping Tent', 'slug' => 'camping-tent'],
                            ['name' => 'Sleeping Bag', 'slug' => 'sleeping-bag'],
                            ['name' => 'Camping Chair', 'slug' => 'camping-chair'],
                            ['name' => 'Camping Table', 'slug' => 'camping-table'],
                            ['name' => 'Camping Stove', 'slug' => 'camping-stove'],
                            ['name' => 'Camping Lantern', 'slug' => 'camping-lantern'],
                            ['name' => 'Camping Cookware Set', 'slug' => 'camping-cookware-set'],
                            ['name' => 'Camping Water Bottle', 'slug' => 'camping-water-bottle'],
                            ['name' => 'Camping Knife', 'slug' => 'camping-knife'],
                            ['name' => 'Camping First Aid Kit', 'slug' => 'camping-first-aid-kit'],
                            ['name' => 'Camping Sleeping Pad', 'slug' => 'camping-sleeping-pad'],
                            ['name' => 'Camping Tarp', 'slug' => 'camping-tarp'],
                            ['name' => 'Camping Rope', 'slug' => 'camping-rope'],
                        ]
                    ],
                    [
                        'name' => 'Outdoor Sports',
                        'slug' => 'outdoor-sports',
                        'children' => [
                            ['name' => 'Hiking Boots', 'slug' => 'hiking-boots'],
                            ['name' => 'Hiking Backpack', 'slug' => 'hiking-backpack'],
                            ['name' => 'Trekking Pole', 'slug' => 'trekking-pole'],
                            ['name' => 'Climbing Rope', 'slug' => 'climbing-rope'],
                            ['name' => 'Climbing Carabiner', 'slug' => 'climbing-carabiner'],
                            ['name' => 'Fishing Rod', 'slug' => 'fishing-rod'],
                            ['name' => 'Fishing Reel', 'slug' => 'fishing-reel'],
                            ['name' => 'Fishing Lure Set', 'slug' => 'fishing-lure-set'],
                            ['name' => 'Ski Gear', 'slug' => 'ski-gear'],
                            ['name' => 'Snowboard', 'slug' => 'snowboard'],
                        ]
                    ],
                    [
                        'name' => 'Cycling',
                        'slug' => 'cycling',
                        'children' => [
                            ['name' => 'Mountain Bike', 'slug' => 'mountain-bike'],
                            ['name' => 'City Bike', 'slug' => 'city-bike'],
                            ['name' => 'Electric Bike', 'slug' => 'electric-bike'],
                            ['name' => 'Cycling Helmet', 'slug' => 'cycling-helmet'],
                            ['name' => 'Cycling Gloves', 'slug' => 'cycling-gloves'],
                            ['name' => 'Bike Light Set', 'slug' => 'bike-light-set'],
                            ['name' => 'Bike Lock', 'slug' => 'bike-lock'],
                            ['name' => 'Bike Pump', 'slug' => 'bike-pump'],
                            ['name' => 'Bike Repair Kit', 'slug' => 'bike-repair-kit'],
                            ['name' => 'Bike Water Bottle', 'slug' => 'bike-water-bottle'],
                            ['name' => 'Bike Phone Mount', 'slug' => 'bike-phone-mount'],
                            ['name' => 'Bike Seat', 'slug' => 'bike-seat'],
                            ['name' => 'Bike Tires', 'slug' => 'bike-tires'],
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
                        'name' => 'Store Gift Cards',
                        'slug' => 'store-gift-cards',
                        'children' => [
                            ['name' => 'Digikala Gift Card', 'slug' => 'digikala-gift-card'],
                            ['name' => 'Snapp Gift Card', 'slug' => 'snapp-gift-card'],
                            ['name' => 'Alibaba Gift Card', 'slug' => 'alibaba-gift-card'],
                            ['name' => 'Bamilo Gift Card', 'slug' => 'bamilo-gift-card'],
                            ['name' => 'Hyperstar Gift Card', 'slug' => 'hyperstar-gift-card'],
                            ['name' => 'Refah Gift Card', 'slug' => 'refah-gift-card'],
                            ['name' => 'Shahrvand Gift Card', 'slug' => 'shahrvand-gift-card'],
                            ['name' => 'Ofogh Koorosh Gift Card', 'slug' => 'ofogh-koorosh-gift-card'],
                        ]
                    ],
                    [
                        'name' => 'Digital Gift Cards',
                        'slug' => 'digital-gift-cards',
                        'children' => [
                            ['name' => 'E-Gift Card', 'slug' => 'e-gift-card'],
                            ['name' => 'Virtual Gift Card', 'slug' => 'virtual-gift-card'],
                            ['name' => 'PlayStation Gift Card', 'slug' => 'playstation-gift-card'],
                            ['name' => 'Xbox Gift Card', 'slug' => 'xbox-gift-card'],
                            ['name' => 'Nintendo Gift Card', 'slug' => 'nintendo-gift-card'],
                            ['name' => 'Steam Gift Card', 'slug' => 'steam-gift-card'],
                            ['name' => 'Google Play Gift Card', 'slug' => 'google-play-gift-card'],
                            ['name' => 'App Store Gift Card', 'slug' => 'app-store-gift-card'],
                            ['name' => 'Netflix Gift Card', 'slug' => 'netflix-gift-card'],
                            ['name' => 'Spotify Gift Card', 'slug' => 'spotify-gift-card'],
                            ['name' => 'Amazon Gift Card', 'slug' => 'amazon-gift-card'],
                        ]
                    ],
                    [
                        'name' => 'Custom Gift Cards',
                        'slug' => 'custom-gift-cards',
                        'children' => [
                            ['name' => 'Personalized Gift Card', 'slug' => 'personalized-gift-card'],
                            ['name' => 'Birthday Gift Card', 'slug' => 'birthday-gift-card'],
                            ['name' => 'Wedding Gift Card', 'slug' => 'wedding-gift-card'],
                            ['name' => 'Holiday Gift Card', 'slug' => 'holiday-gift-card'],
                            ['name' => 'Corporate Gift Card', 'slug' => 'corporate-gift-card'],
                            ['name' => 'Anniversary Gift Card', 'slug' => 'anniversary-gift-card'],
                            ['name' => 'Thank You Gift Card', 'slug' => 'thank-you-gift-card'],
                            ['name' => 'Congratulations Gift Card', 'slug' => 'congratulations-gift-card'],
                            ['name' => 'New Year Gift Card', 'slug' => 'new-year-gift-card'],
                            ['name' => 'Nowruz Gift Card', 'slug' => 'nowruz-gift-card'],
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
            ['name' => 'Kachiran', 'slug' => 'kachiran'],
            ['name' => 'Gree', 'slug' => 'gree'],
            ['name' => 'Toshiba', 'slug' => 'toshiba'],
            ['name' => 'Techno', 'slug' => 'techno'],
            ['name' => 'Mobin Net', 'slug' => 'mobin-net'],
            ['name' => 'Irancel', 'slug' => 'irancel'],
            ['name' => 'Hamrahe Aval', 'slug' => 'hamrahe-aval'],
            ['name' => 'Novin Charm', 'slug' => 'novin-charm'],
            ['name' => 'Charm Mashhad', 'slug' => 'charm-mashhad'],
            ['name' => 'Asmara', 'slug' => 'asmara'],
            ['name' => 'Serjeh', 'slug' => 'serjeh'],
            ['name' => 'Gordieh', 'slug' => 'gordieh'],
            ['name' => 'Charm Ataroud', 'slug' => 'charm-ataroud'],
            ['name' => 'Tolika', 'slug' => 'tolika'],
            ['name' => 'Pama', 'slug' => 'pama'],
            ['name' => 'I-Tech', 'slug' => 'i-tech'],
            ['name' => 'Zara Home', 'slug' => 'zara-home'],
            ['name' => 'Alcatel', 'slug' => 'alcatel'],
            ['name' => 'Doogee', 'slug' => 'doogee'],
            ['name' => 'HMD', 'slug' => 'hmd'],
            ['name' => 'Vekal', 'slug' => 'vekal'],
            ['name' => 'TCL', 'slug' => 'tcl'],
            ['name' => 'Redton', 'slug' => 'redton'],
            ['name' => 'Cypher', 'slug' => 'cypher'],
            ['name' => 'Honor', 'slug' => 'honor'],
            ['name' => 'Poco', 'slug' => 'poco'],
            ['name' => 'General Luxe', 'slug' => 'general-luxe'],
            ['name' => 'Daria', 'slug' => 'daria'],
            ['name' => 'GLX', 'slug' => 'glx'],
            ['name' => 'TCH', 'slug' => 'tch'],
            ['name' => 'QCY', 'slug' => 'qcy'],
            ['name' => 'One More', 'slug' => 'one-more'],
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
        // 3. CREATE PRODUCTS WITH VARIANTS
        // ================================================================
        $this->command->info('🔄 Creating products with variants...');

        $productsData = [
            // ===== MOBILE =====
            ['title' => 'iPhone 16 Pro', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'iPhone 16 Pro Max', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'iPhone 15 Pro', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'iPhone 15', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'iPhone 14', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'iPhone SE', 'category' => 'Apple Phones', 'brand' => 'Apple'],
            ['title' => 'Galaxy S24 Ultra', 'category' => 'Samsung Phones', 'brand' => 'Samsung'],
            ['title' => 'Galaxy S24 Plus', 'category' => 'Samsung Phones', 'brand' => 'Samsung'],
            ['title' => 'Galaxy S24', 'category' => 'Samsung Phones', 'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Fold 6', 'category' => 'Samsung Phones', 'brand' => 'Samsung'],
            ['title' => 'Galaxy Z Flip 6', 'category' => 'Samsung Phones', 'brand' => 'Samsung'],
            ['title' => 'Xiaomi 14 Ultra', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14 Pro', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi'],
            ['title' => 'Xiaomi 14', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13 Pro', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi'],
            ['title' => 'Redmi Note 13', 'category' => 'Xiaomi Phones', 'brand' => 'Xiaomi'],
            ['title' => 'Poco X7 Pro', 'category' => 'Xiaomi Phones', 'brand' => 'Poco'],
            ['title' => 'Google Pixel 8 Pro', 'category' => 'Other Brands', 'brand' => 'Google'],
            ['title' => 'Google Pixel 8', 'category' => 'Other Brands', 'brand' => 'Google'],
            ['title' => 'Nothing Phone 2', 'category' => 'Other Brands', 'brand' => 'Nothing'],
            ['title' => 'OnePlus 12', 'category' => 'Other Brands', 'brand' => 'OnePlus'],
            ['title' => 'Huawei P60 Pro', 'category' => 'Other Brands', 'brand' => 'Huawei'],
            ['title' => 'Nokia X30', 'category' => 'Other Brands', 'brand' => 'Nokia'],
            ['title' => 'Sony Xperia 1 V', 'category' => 'Other Brands', 'brand' => 'Sony'],
            ['title' => 'Motorola Edge 40', 'category' => 'Other Brands', 'brand' => 'Motorola'],
            ['title' => 'Realme GT 3', 'category' => 'Other Brands', 'brand' => 'Realme'],
            
            // ===== LAPTOPS =====
            ['title' => 'MacBook Pro M3', 'category' => 'Apple MacBooks', 'brand' => 'Apple'],
            ['title' => 'MacBook Air M3', 'category' => 'Apple MacBooks', 'brand' => 'Apple'],
            ['title' => 'MacBook Pro M4', 'category' => 'Apple MacBooks', 'brand' => 'Apple'],
            ['title' => 'ASUS ROG Zephyrus', 'category' => 'ASUS Laptops', 'brand' => 'Asus'],
            ['title' => 'ASUS TUF Gaming', 'category' => 'ASUS Laptops', 'brand' => 'Asus'],
            ['title' => 'Lenovo ThinkPad X1', 'category' => 'Lenovo Laptops', 'brand' => 'Lenovo'],
            ['title' => 'Lenovo Legion Pro', 'category' => 'Lenovo Laptops', 'brand' => 'Lenovo'],
            ['title' => 'MSI Titan GT77', 'category' => 'Gaming Laptops', 'brand' => 'MSI'],
            ['title' => 'Razer Blade 16', 'category' => 'Gaming Laptops', 'brand' => 'Razer'],
            ['title' => 'Dell XPS 16', 'category' => 'Business Laptops', 'brand' => 'Dell'],
            ['title' => 'HP Spectre x360', 'category' => 'Business Laptops', 'brand' => 'HP'],
            ['title' => 'Acer Aspire 5', 'category' => 'Student Laptops', 'brand' => 'Acer'],
            ['title' => 'HP Pavilion 15', 'category' => 'Student Laptops', 'brand' => 'HP'],
            
            // ===== DIGITAL PRODUCTS =====
            ['title' => 'PS5', 'category' => 'Gaming Consoles', 'brand' => 'PlayStation'],
            ['title' => 'PS5 Slim', 'category' => 'Gaming Consoles', 'brand' => 'PlayStation'],
            ['title' => 'Xbox Series X', 'category' => 'Gaming Consoles', 'brand' => 'Xbox'],
            ['title' => 'Nintendo Switch OLED', 'category' => 'Gaming Consoles', 'brand' => 'Nintendo'],
            ['title' => 'Sony WH-1000XM5', 'category' => 'Headphones', 'brand' => 'Sony'],
            ['title' => 'Apple AirPods Pro 2', 'category' => 'Headphones', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Buds 2 Pro', 'category' => 'Headphones', 'brand' => 'Samsung'],
            ['title' => 'Apple Watch Ultra 2', 'category' => 'Smartwatches', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Watch 6', 'category' => 'Smartwatches', 'brand' => 'Samsung'],
            ['title' => 'iPad Pro M4', 'category' => 'Tablets', 'brand' => 'Apple'],
            ['title' => 'Samsung Galaxy Tab S9', 'category' => 'Tablets', 'brand' => 'Samsung'],
            ['title' => 'JBL Charge 5', 'category' => 'Speakers', 'brand' => 'JBL'],
            ['title' => 'Canon EOS R5', 'category' => 'Cameras', 'brand' => 'Canon'],
            ['title' => 'Sony Alpha A7 IV', 'category' => 'Cameras', 'brand' => 'Sony'],
            ['title' => 'Anker 20000mAh', 'category' => 'Power Banks', 'brand' => 'Anker'],
            ['title' => 'Xiaomi 30000mAh', 'category' => 'Power Banks', 'brand' => 'Xiaomi'],
            ['title' => 'Intel Core i9-14900K', 'category' => 'Computer Components', 'brand' => 'Intel'],
            ['title' => 'NVIDIA RTX 4090', 'category' => 'Computer Components', 'brand' => 'NVIDIA'],
            ['title' => 'Xiaomi Smart Hub', 'category' => 'Smart Home', 'brand' => 'Xiaomi'],
            ['title' => 'Google Nest Hub 2', 'category' => 'Smart Home', 'brand' => 'Google'],
            ['title' => 'HP LaserJet Pro MFP', 'category' => 'Printers', 'brand' => 'HP'],
            ['title' => 'Samsung 1TB SSD', 'category' => 'Storage Devices', 'brand' => 'Samsung'],
            ['title' => 'TP-Link Router', 'category' => 'Networking', 'brand' => 'TP-Link'],
            
            // ===== HOME & KITCHEN =====
            ['title' => 'Non-Stick Frying Pan', 'category' => 'Cookware', 'brand' => 'IKEA'],
            ['title' => 'Pressure Cooker', 'category' => 'Cookware', 'brand' => 'Bosch'],
            ['title' => 'Coffee Maker', 'category' => 'Tea & Coffee', 'brand' => 'Philips'],
            ['title' => 'Electric Kettle', 'category' => 'Tea & Coffee', 'brand' => 'Kenwood'],
            ['title' => 'Sofa Set', 'category' => 'Furniture', 'brand' => 'IKEA'],
            ['title' => 'Dining Table', 'category' => 'Furniture', 'brand' => 'IKEA'],
            ['title' => 'Chandelier', 'category' => 'Lighting', 'brand' => 'IKEA'],
            ['title' => 'King Size Bed', 'category' => 'Bedroom', 'brand' => 'IKEA'],
            ['title' => 'Persian Carpet', 'category' => 'Carpets & Rugs', 'brand' => 'IKEA'],
            
            // ===== HOME APPLIANCES =====
            ['title' => 'LG Refrigerator', 'category' => 'Refrigerators', 'brand' => 'LG'],
            ['title' => 'Samsung Refrigerator', 'category' => 'Refrigerators', 'brand' => 'Samsung'],
            ['title' => 'LG Washing Machine', 'category' => 'Washing Machines', 'brand' => 'LG'],
            ['title' => 'Bosch Dishwasher', 'category' => 'Dishwashers', 'brand' => 'Bosch'],
            ['title' => 'Robot Vacuum', 'category' => 'Vacuums', 'brand' => 'LG'],
            ['title' => 'Air Fryer', 'category' => 'Cooking Appliances', 'brand' => 'Philips'],
            ['title' => 'Microwave Oven', 'category' => 'Cooking Appliances', 'brand' => 'LG'],
            ['title' => 'Sony OLED TV', 'category' => 'TVs', 'brand' => 'Sony'],
            ['title' => 'Samsung QLED TV', 'category' => 'TVs', 'brand' => 'Samsung'],
            ['title' => 'LG OLED TV', 'category' => 'TVs', 'brand' => 'LG'],
            
            // ===== BEAUTY & HEALTH =====
            ['title' => 'Moisturizer Cream', 'category' => 'Skin Care', 'brand' => 'Loreal'],
            ['title' => 'Sunscreen SPF 50', 'category' => 'Skin Care', 'brand' => 'Nivea'],
            ['title' => 'Foundation', 'category' => 'Makeup', 'brand' => 'Maybelline'],
            ['title' => 'Lipstick', 'category' => 'Makeup', 'brand' => 'Maybelline'],
            ['title' => 'Shampoo', 'category' => 'Hair Care', 'brand' => 'Loreal'],
            ['title' => 'Hair Dryer', 'category' => 'Hair Care', 'brand' => 'Philips'],
            ['title' => 'Dior Sauvage', 'category' => 'Perfumes', 'brand' => 'Dior'],
            ['title' => 'Chanel No.5', 'category' => 'Perfumes', 'brand' => 'Chanel'],
            ['title' => 'Electric Toothbrush', 'category' => 'Oral Care', 'brand' => 'Philips'],
            ['title' => 'Deodorant', 'category' => 'Personal Care', 'brand' => 'Nivea'],
            
            // ===== FASHION =====
            ['title' => 'Men\'s T-Shirt', 'category' => 'Men\'s Clothing', 'brand' => 'Nike'],
            ['title' => 'Men\'s Jeans', 'category' => 'Men\'s Clothing', 'brand' => 'Levis'],
            ['title' => 'Men\'s Suit', 'category' => 'Men\'s Clothing', 'brand' => 'Zara'],
            ['title' => 'Women\'s Dress', 'category' => 'Women\'s Clothing', 'brand' => 'Zara'],
            ['title' => 'Women\'s Jeans', 'category' => 'Women\'s Clothing', 'brand' => 'H&M'],
            ['title' => 'Manteau', 'category' => 'Women\'s Clothing', 'brand' => 'Zara'],
            ['title' => 'Baby Bodysuit', 'category' => 'Children\'s Clothing', 'brand' => 'H&M'],
            ['title' => 'Nike Air Max', 'category' => 'Shoes', 'brand' => 'Nike'],
            ['title' => 'Adidas Ultraboost', 'category' => 'Shoes', 'brand' => 'Adidas'],
            ['title' => 'Men\'s Wallet', 'category' => 'Bags & Accessories', 'brand' => 'Levis'],
            ['title' => 'Women\'s Handbag', 'category' => 'Bags & Accessories', 'brand' => 'Zara'],
            ['title' => 'Rolex Watch', 'category' => 'Bags & Accessories', 'brand' => 'Rolex'],
            
            // ===== GOLD & JEWELRY =====
            ['title' => 'Gold Necklace', 'category' => 'Gold Jewelry', 'brand' => 'Rolex'],
            ['title' => 'Gold Ring', 'category' => 'Gold Jewelry', 'brand' => 'Rolex'],
            ['title' => 'Gold Earrings', 'category' => 'Gold Jewelry', 'brand' => 'Rolex'],
            ['title' => 'Silver Necklace', 'category' => 'Silver Jewelry', 'brand' => 'Seiko'],
            ['title' => 'Diamond Ring', 'category' => 'Diamonds & Gems', 'brand' => 'Rolex'],
            
            // ===== VEHICLES =====
            ['title' => 'BMW 5 Series', 'category' => 'Cars', 'brand' => 'BMW'],
            ['title' => 'Mercedes E-Class', 'category' => 'Cars', 'brand' => 'Mercedes'],
            ['title' => 'Toyota Camry', 'category' => 'Cars', 'brand' => 'Toyota'],
            ['title' => 'Honda Civic', 'category' => 'Cars', 'brand' => 'Honda'],
            ['title' => 'Honda CBR 500R', 'category' => 'Motorcycles', 'brand' => 'Honda'],
            ['title' => 'Car Audio System', 'category' => 'Car Accessories', 'brand' => 'Sony'],
            
            // ===== HEALTH & MEDICAL =====
            ['title' => 'Blood Pressure Monitor', 'category' => 'Medical Equipment', 'brand' => 'Philips'],
            ['title' => 'Digital Thermometer', 'category' => 'Medical Equipment', 'brand' => 'Philips'],
            ['title' => 'Knee Brace', 'category' => 'Orthopedic', 'brand' => 'Nivea'],
            ['title' => 'Vitamin C', 'category' => 'Supplements', 'brand' => 'Nivea'],
            ['title' => 'Omega-3', 'category' => 'Supplements', 'brand' => 'Nivea'],
            ['title' => 'Electric Toothbrush', 'category' => 'Dental Care', 'brand' => 'Philips'],
            ['title' => 'Treadmill', 'category' => 'Fitness Equipment', 'brand' => 'Nike'],
            
            // ===== TOOLS & EQUIPMENT =====
            ['title' => 'Makita Drill', 'category' => 'Power Tools', 'brand' => 'Makita'],
            ['title' => 'DeWalt Grinder', 'category' => 'Power Tools', 'brand' => 'DeWalt'],
            ['title' => 'Screwdriver Set', 'category' => 'Hand Tools', 'brand' => 'Stanley'],
            ['title' => 'Lawn Mower', 'category' => 'Gardening Tools', 'brand' => 'Bosch'],
            
            // ===== BOOKS & ART =====
            ['title' => '1984 George Orwell', 'category' => 'Books', 'brand' => 'Apple'],
            ['title' => 'Atomic Habits', 'category' => 'Books', 'brand' => 'Apple'],
            ['title' => 'Oil Painting Canvas', 'category' => 'Art & Painting', 'brand' => 'Apple'],
            
            // ===== SPORTS & TRAVEL =====
            ['title' => 'Boxing Punching Bag', 'category' => 'Sports Equipment', 'brand' => 'Adidas'],
            ['title' => 'Yoga Mat', 'category' => 'Sports Equipment', 'brand' => 'Nike'],
            ['title' => 'Sports T-Shirt', 'category' => 'Sportswear', 'brand' => 'Nike'],
            ['title' => 'Suitcase 4 Wheels', 'category' => 'Travel Equipment', 'brand' => 'Adidas'],
            
            // ===== GIFT CARDS =====
            ['title' => 'Digikala Gift Card', 'category' => 'Store Gift Cards', 'brand' => 'Apple'],
            ['title' => 'PlayStation Gift Card', 'category' => 'Digital Gift Cards', 'brand' => 'PlayStation'],
        ];

        $productCount = 0;
        $variantCount = 0;

        foreach ($productsData as $productData) {
            $category = DB::table('categories')->where('name', $productData['category'])->first();
            
            if (!$category) {
                $this->command->warn('⚠️ Category not found: ' . $productData['category']);
                continue;
            }
            
            $brand = DB::table('brands')->where('name', $productData['brand'])->first();
            if (!$brand) {
                $brand = DB::table('brands')->inRandomOrder()->first();
            }
            $slug = Str::slug($title) . '-' . uniqid();
            $title = $productData['title'] . ' ' . Str::random(4);
            $price = rand(100000, 50000000);
            $price = round($price / 1000) * 1000;
            $salePrice = $price * rand(7, 9) / 10;
            $salePrice = round($salePrice / 1000) * 1000;

            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
               'slug' => $slug,
                'short_description' => 'Short description for ' . $title,
                'description' => '<p>Full description for ' . $title . '</p>',
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

            $variantCountPerProduct = rand(2, 4);
            for ($v = 1; $v <= $variantCountPerProduct; $v++) {
                $variantPrice = $price * rand(8, 13) / 10;
                $variantPrice = round($variantPrice / 1000) * 1000;
                
                $variantSalePrice = $variantPrice * rand(6, 9) / 10;
                $variantSalePrice = round($variantSalePrice / 1000) * 1000;

                DB::table('product_variants')->insert([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(3),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'price' => $variantPrice,
                    'sale_price' => $variantSalePrice,
                    'stock' => rand(5, 50),
                    'weight' => rand(100, 1000),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $variantCount++;
            }
            $productCount++;
        }

        $this->command->info('✅ ' . $productCount . ' products created!');
        $this->command->info('✅ ' . $variantCount . ' variants created!');
        $this->command->info('📊 Total categories: ' . DB::table('categories')->count());
        $this->command->info('🏷️ Total brands: ' . DB::table('brands')->count());
    }
}