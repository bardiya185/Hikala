<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting complete product seeding with categories like Digikala...');

        // ================================================================
        // 1. CREATE CATEGORIES (LIKE DIGIKALA)
        // ================================================================
        $categories = [
            // ===== MOBILE (موبایل) =====
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
                                    ['name' => 'iPhone 17', 'slug' => 'iphone-17'],
                                ]
                            ],
                            [
                                'name' => 'Mobile Brands',
                                'slug' => 'mobile-brands',
                                'children' => [
                                    ['name' => 'Samsung Phones', 'slug' => 'samsung-phones'],
                                    ['name' => 'Xiaomi Phones', 'slug' => 'xiaomi-phones'],
                                    ['name' => 'Nokia Phones', 'slug' => 'nokia-phones'],
                                    ['name' => 'Realme Phones', 'slug' => 'realme-phones'],
                                    ['name' => 'Cypher Phones', 'slug' => 'cypher-phones'],
                                    ['name' => 'Honor Phones', 'slug' => 'honor-phones'],
                                    ['name' => 'Nothing Phone', 'slug' => 'nothing-phone'],
                                    ['name' => 'Vekal Phones', 'slug' => 'vekal-phones'],
                                    ['name' => 'Motorola Phones', 'slug' => 'motorola-phones'],
                                    ['name' => 'TCL Phones', 'slug' => 'tcl-phones'],
                                    ['name' => 'Huawei Phones', 'slug' => 'huawei-phones'],
                                    ['name' => 'Daria Phones', 'slug' => 'daria-phones'],
                                    ['name' => 'Google Pixel', 'slug' => 'google-pixel'],
                                    ['name' => 'TCH Phones', 'slug' => 'tch-phones'],
                                    ['name' => 'GLX Phones', 'slug' => 'glx-phones'],
                                    ['name' => 'Alcatel Phones', 'slug' => 'alcatel-phones'],
                                    ['name' => 'Redton Phones', 'slug' => 'redton-phones'],
                                    ['name' => 'OnePlus Phones', 'slug' => 'oneplus-phones'],
                                    ['name' => 'General Luxe', 'slug' => 'general-luxe'],
                                    ['name' => 'Doogee Phones', 'slug' => 'doogee-phones'],
                                    ['name' => 'HMD Phones', 'slug' => 'hmd-phones'],
                                    ['name' => 'Poco Phones', 'slug' => 'poco-phones'],
                                ]
                            ],
                            [
                                'name' => 'Top Brands',
                                'slug' => 'top-brands',
                                'children' => [
                                    ['name' => 'Samsung', 'slug' => 'samsung'],
                                    ['name' => 'Apple', 'slug' => 'apple'],
                                    ['name' => 'Honor', 'slug' => 'honor'],
                                    ['name' => 'Huawei', 'slug' => 'huawei'],
                                    ['name' => 'Realme', 'slug' => 'realme'],
                                    ['name' => 'Cypher', 'slug' => 'cypher'],
                                    ['name' => 'TCH', 'slug' => 'tch'],
                                    ['name' => 'Redton', 'slug' => 'redton'],
                                ]
                            ],
                            [
                                'name' => 'By Price',
                                'slug' => 'by-price',
                                'children' => [
                                    ['name' => 'Budget Phones', 'slug' => 'budget-phones'],
                                    ['name' => 'Installment Phones', 'slug' => 'installment-phones'],
                                    ['name' => 'Under 2 Million', 'slug' => 'under-2-million'],
                                    ['name' => 'Under 5 Million', 'slug' => 'under-5-million'],
                                    ['name' => 'Under 7 Million', 'slug' => 'under-7-million'],
                                    ['name' => 'Under 10 Million', 'slug' => 'under-10-million'],
                                    ['name' => 'Under 12 Million', 'slug' => 'under-12-million'],
                                    ['name' => 'Under 15 Million', 'slug' => 'under-15-million'],
                                    ['name' => 'Under 20 Million', 'slug' => 'under-20-million'],
                                    ['name' => 'Under 25 Million', 'slug' => 'under-25-million'],
                                    ['name' => 'Under 30 Million', 'slug' => 'under-30-million'],
                                    ['name' => 'Under 40 Million', 'slug' => 'under-40-million'],
                                    ['name' => 'Under 50 Million', 'slug' => 'under-50-million'],
                                    ['name' => 'Under 70 Million', 'slug' => 'under-70-million'],
                                ]
                            ],
                            [
                                'name' => 'By Performance',
                                'slug' => 'by-performance',
                                'children' => [
                                    ['name' => 'Gaming Phones', 'slug' => 'gaming-phones'],
                                    ['name' => '5G Phones', 'slug' => '5g-phones'],
                                    ['name' => 'Button Phones', 'slug' => 'button-phones'],
                                    ['name' => 'Waterproof Phones', 'slug' => 'waterproof-phones'],
                                    ['name' => 'Photography Phones', 'slug' => 'photography-phones'],
                                    ['name' => 'Mid-Range Phones', 'slug' => 'mid-range-phones'],
                                    ['name' => 'Student Phones', 'slug' => 'student-phones'],
                                    ['name' => 'Flagship Phones', 'slug' => 'flagship-phones'],
                                    ['name' => 'Dual SIM Phones', 'slug' => 'dual-sim-phones'],
                                ]
                            ],
                            [
                                'name' => 'By Storage',
                                'slug' => 'by-storage',
                                'children' => [
                                    ['name' => '64GB Phones', 'slug' => '64gb-phones'],
                                    ['name' => '128GB Phones', 'slug' => '128gb-phones'],
                                    ['name' => '256GB Phones', 'slug' => '256gb-phones'],
                                    ['name' => '512GB Phones', 'slug' => '512gb-phones'],
                                    ['name' => '1TB Phones', 'slug' => '1tb-phones'],
                                ]
                            ],
                            [
                                'name' => 'By Camera',
                                'slug' => 'by-camera',
                                'children' => [
                                    ['name' => '48MP Camera', 'slug' => '48mp-camera'],
                                    ['name' => '50MP Camera', 'slug' => '50mp-camera'],
                                    ['name' => '64MP Camera', 'slug' => '64mp-camera'],
                                    ['name' => '108MP Camera', 'slug' => '108mp-camera'],
                                ]
                            ],
                            [
                                'name' => 'Mobile Accessories',
                                'slug' => 'mobile-accessories',
                                'children' => [
                                    ['name' => 'Phone Chargers', 'slug' => 'phone-chargers'],
                                    ['name' => 'Wireless Chargers', 'slug' => 'wireless-chargers'],
                                    ['name' => 'Type-C Chargers', 'slug' => 'type-c-chargers'],
                                    ['name' => 'Type-C Cables', 'slug' => 'type-c-cables'],
                                    ['name' => 'Phone Cases', 'slug' => 'phone-cases'],
                                    ['name' => 'Screen Protectors', 'slug' => 'screen-protectors'],
                                    ['name' => 'Car Chargers', 'slug' => 'car-chargers'],
                                    ['name' => 'Phone Holders', 'slug' => 'phone-holders'],
                                    ['name' => 'Cables & Adapters', 'slug' => 'cables-adapters'],
                                    ['name' => 'Power Banks', 'slug' => 'power-banks'],
                                    ['name' => 'Power Stations', 'slug' => 'power-stations'],
                                    ['name' => 'Monopods', 'slug' => 'monopods'],
                                    ['name' => 'AirPods Cases', 'slug' => 'airpods-cases'],
                                    ['name' => 'Neck Holders', 'slug' => 'neck-holders'],
                                    ['name' => 'Phone Coolers', 'slug' => 'phone-coolers'],
                                    ['name' => 'Mobile Game Controllers', 'slug' => 'mobile-game-controllers'],
                                ]
                            ],
                            [
                                'name' => 'Phone Cases',
                                'slug' => 'phone-cases',
                                'children' => [
                                    ['name' => 'Samsung Cases', 'slug' => 'samsung-cases'],
                                    ['name' => 'iPhone Cases', 'slug' => 'iphone-cases'],
                                    ['name' => 'Xiaomi Cases', 'slug' => 'xiaomi-cases'],
                                    ['name' => 'Realme Cases', 'slug' => 'realme-cases'],
                                    ['name' => 'Poco Cases', 'slug' => 'poco-cases'],
                                    ['name' => 'Vekal Cases', 'slug' => 'vekal-cases'],
                                ]
                            ],
                            [
                                'name' => 'Trending',
                                'slug' => 'trending-phones',
                                'children' => [
                                    ['name' => 'Samsung S26', 'slug' => 'samsung-s26'],
                                    ['name' => 'iPhone 17', 'slug' => 'iphone-17'],
                                    ['name' => 'Samsung S25', 'slug' => 'samsung-s25'],
                                    ['name' => 'Xiaomi Note 15', 'slug' => 'xiaomi-note-15'],
                                    ['name' => 'Xiaomi Note 14', 'slug' => 'xiaomi-note-14'],
                                    ['name' => 'Redmi Note 14 Pro', 'slug' => 'redmi-note-14-pro'],
                                    ['name' => 'Redmi Note 14 Pro Plus', 'slug' => 'redmi-note-14-pro-plus'],
                                    ['name' => 'Samsung S Series', 'slug' => 'samsung-s-series'],
                                    ['name' => 'Samsung A Series', 'slug' => 'samsung-a-series'],
                                    ['name' => 'Samsung M Series', 'slug' => 'samsung-m-series'],
                                    ['name' => 'Galaxy A57', 'slug' => 'galaxy-a57'],
                                    ['name' => 'Xiaomi 15T', 'slug' => 'xiaomi-15t'],
                                    ['name' => 'Poco X7 Pro', 'slug' => 'poco-x7-pro'],
                                    ['name' => 'Galaxy A26', 'slug' => 'galaxy-a26'],
                                    ['name' => 'Galaxy A56', 'slug' => 'galaxy-a56'],
                                    ['name' => 'iPhone 17 Charger', 'slug' => 'iphone-17-charger'],
                                    ['name' => 'Galaxy S25 FE', 'slug' => 'galaxy-s25-fe'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            // ===== LAPTOPS (لپ‌تاپ) =====
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
                                'name' => 'ASUS Laptops',
                                'slug' => 'asus-laptops',
                                'children' => [
                                    ['name' => 'Vivobook', 'slug' => 'vivobook'],
                                    ['name' => 'Zenbook', 'slug' => 'zenbook'],
                                    ['name' => 'TUF Gaming', 'slug' => 'tuf-gaming'],
                                    ['name' => 'ROG', 'slug' => 'rog'],
                                ]
                            ],
                            [
                                'name' => 'Lenovo Laptops',
                                'slug' => 'lenovo-laptops',
                                'children' => [
                                    ['name' => 'V15', 'slug' => 'v15'],
                                    ['name' => 'IdeaPad 1', 'slug' => 'ideapad-1'],
                                    ['name' => 'IdeaPad Slim 3', 'slug' => 'ideapad-slim-3'],
                                    ['name' => 'ThinkPad', 'slug' => 'thinkpad'],
                                    ['name' => 'LOQ', 'slug' => 'loq'],
                                ]
                            ],
                            [
                                'name' => 'MacBooks',
                                'slug' => 'macbooks',
                                'children' => [
                                    ['name' => 'MacBook Neo', 'slug' => 'macbook-neo'],
                                    ['name' => 'MacBook Air M2', 'slug' => 'macbook-air-m2'],
                                    ['name' => 'MacBook Air M3', 'slug' => 'macbook-air-m3'],
                                    ['name' => 'MacBook Air M4', 'slug' => 'macbook-air-m4'],
                                    ['name' => 'MacBook Air M5', 'slug' => 'macbook-air-m5'],
                                    ['name' => 'MacBook Pro M3', 'slug' => 'macbook-pro-m3'],
                                    ['name' => 'MacBook Pro M4', 'slug' => 'macbook-pro-m4'],
                                    ['name' => 'MacBook Pro M5', 'slug' => 'macbook-pro-m5'],
                                ]
                            ],
                            [
                                'name' => 'Surface Laptops',
                                'slug' => 'surface-laptops',
                                'children' => [
                                    ['name' => 'Surface Laptop 5', 'slug' => 'surface-laptop-5'],
                                    ['name' => 'Surface Laptop 6', 'slug' => 'surface-laptop-6'],
                                    ['name' => 'Surface Laptop 7', 'slug' => 'surface-laptop-7'],
                                    ['name' => 'Surface Laptop Studio 2', 'slug' => 'surface-laptop-studio-2'],
                                ]
                            ],
                            [
                                'name' => 'Other Laptop Brands',
                                'slug' => 'other-laptop-brands',
                                'children' => [
                                    ['name' => 'HP Laptops', 'slug' => 'hp-laptops'],
                                    ['name' => 'Dell Laptops', 'slug' => 'dell-laptops'],
                                    ['name' => 'Acer Laptops', 'slug' => 'acer-laptops'],
                                    ['name' => 'MSI Laptops', 'slug' => 'msi-laptops'],
                                ]
                            ],
                            [
                                'name' => 'By Processor Brand',
                                'slug' => 'by-processor-brand',
                                'children' => [
                                    ['name' => 'Intel Processors', 'slug' => 'intel-processors'],
                                    ['name' => 'AMD Processors', 'slug' => 'amd-processors'],
                                    ['name' => 'Snapdragon Processors', 'slug' => 'snapdragon-processors'],
                                    ['name' => 'Celeron Processors', 'slug' => 'celeron-processors'],
                                ]
                            ],
                            [
                                'name' => 'By Intel Series',
                                'slug' => 'by-intel-series',
                                'children' => [
                                    ['name' => 'Core Ultra 9', 'slug' => 'core-ultra-9'],
                                    ['name' => 'Core Ultra 7', 'slug' => 'core-ultra-7'],
                                    ['name' => 'Core Ultra 5', 'slug' => 'core-ultra-5'],
                                    ['name' => 'Core i9', 'slug' => 'core-i9'],
                                    ['name' => 'Core i7', 'slug' => 'core-i7'],
                                    ['name' => 'Core i5', 'slug' => 'core-i5'],
                                    ['name' => 'Core i3', 'slug' => 'core-i3'],
                                ]
                            ],
                            [
                                'name' => 'By Intel Generation',
                                'slug' => 'by-intel-generation',
                                'children' => [
                                    ['name' => '14th Gen', 'slug' => '14th-gen'],
                                    ['name' => '13th Gen', 'slug' => '13th-gen'],
                                    ['name' => '12th Gen', 'slug' => '12th-gen'],
                                ]
                            ],
                            [
                                'name' => 'By RAM Capacity',
                                'slug' => 'by-ram-capacity',
                                'children' => [
                                    ['name' => '64GB RAM', 'slug' => '64gb-ram'],
                                    ['name' => '32GB RAM', 'slug' => '32gb-ram'],
                                    ['name' => '16GB RAM', 'slug' => '16gb-ram'],
                                    ['name' => '12GB RAM', 'slug' => '12gb-ram'],
                                    ['name' => '8GB RAM', 'slug' => '8gb-ram'],
                                ]
                            ],
                            [
                                'name' => 'By RAM Type',
                                'slug' => 'by-ram-type',
                                'children' => [
                                    ['name' => 'DDR5 RAM', 'slug' => 'ddr5-ram'],
                                    ['name' => 'DDR4 RAM', 'slug' => 'ddr4-ram'],
                                ]
                            ],
                            [
                                'name' => 'Gaming Laptops',
                                'slug' => 'gaming-laptops',
                                'children' => [
                                    ['name' => 'ASUS Gaming', 'slug' => 'asus-gaming'],
                                    ['name' => 'Lenovo Gaming', 'slug' => 'lenovo-gaming'],
                                    ['name' => 'Acer Gaming', 'slug' => 'acer-gaming'],
                                    ['name' => 'HP Gaming', 'slug' => 'hp-gaming'],
                                    ['name' => 'MSI Gaming', 'slug' => 'msi-gaming'],
                                ]
                            ],
                            [
                                'name' => 'By Graphics Card',
                                'slug' => 'by-graphics-card',
                                'children' => [
                                    ['name' => 'RTX 2050', 'slug' => 'rtx-2050'],
                                    ['name' => 'RTX 3050', 'slug' => 'rtx-3050'],
                                    ['name' => 'RTX 4050', 'slug' => 'rtx-4050'],
                                    ['name' => 'RTX 4060', 'slug' => 'rtx-4060'],
                                    ['name' => 'RTX 4070', 'slug' => 'rtx-4070'],
                                    ['name' => 'RTX 5050', 'slug' => 'rtx-5050'],
                                    ['name' => 'RTX 5060', 'slug' => 'rtx-5060'],
                                    ['name' => 'RTX 5070', 'slug' => 'rtx-5070'],
                                    ['name' => 'RTX 5080', 'slug' => 'rtx-5080'],
                                    ['name' => 'RTX 5090', 'slug' => 'rtx-5090'],
                                ]
                            ],
                            [
                                'name' => 'By Graphics Memory',
                                'slug' => 'by-graphics-memory',
                                'children' => [
                                    ['name' => '2GB Graphics', 'slug' => '2gb-graphics'],
                                    ['name' => '4GB Graphics', 'slug' => '4gb-graphics'],
                                    ['name' => '6GB Graphics', 'slug' => '6gb-graphics'],
                                    ['name' => '8GB Graphics', 'slug' => '8gb-graphics'],
                                    ['name' => '12GB Graphics', 'slug' => '12gb-graphics'],
                                    ['name' => '16GB Graphics', 'slug' => '16gb-graphics'],
                                ]
                            ],
                            [
                                'name' => 'By Refresh Rate',
                                'slug' => 'by-refresh-rate',
                                'children' => [
                                    ['name' => '120Hz', 'slug' => '120hz'],
                                    ['name' => '144Hz', 'slug' => '144hz'],
                                    ['name' => '165Hz', 'slug' => '165hz'],
                                    ['name' => '240Hz', 'slug' => '240hz'],
                                ]
                            ],
                            [
                                'name' => 'Laptop Price',
                                'slug' => 'laptop-price',
                                'children' => [
                                    ['name' => 'Under 50 Million', 'slug' => 'under-50-million-laptop'],
                                    ['name' => 'Under 60 Million', 'slug' => 'under-60-million-laptop'],
                                    ['name' => 'Under 70 Million', 'slug' => 'under-70-million-laptop'],
                                    ['name' => 'Under 80 Million', 'slug' => 'under-80-million-laptop'],
                                    ['name' => 'Under 90 Million', 'slug' => 'under-90-million-laptop'],
                                    ['name' => 'Under 100 Million', 'slug' => 'under-100-million-laptop'],
                                ]
                            ],
                            [
                                'name' => 'By Usage',
                                'slug' => 'by-usage',
                                'children' => [
                                    ['name' => 'Student Laptops', 'slug' => 'student-laptops'],
                                    ['name' => 'Gaming Laptops', 'slug' => 'gaming-laptops-usage'],
                                    ['name' => 'Multimedia Laptops', 'slug' => 'multimedia-laptops'],
                                ]
                            ],
                            [
                                'name' => 'Laptop Accessories',
                                'slug' => 'laptop-accessories',
                                'children' => [
                                    ['name' => 'Cooling Pads', 'slug' => 'cooling-pads'],
                                    ['name' => 'Laptop Bags', 'slug' => 'laptop-bags'],
                                    ['name' => 'Laptop Chargers', 'slug' => 'laptop-chargers'],
                                    ['name' => 'Mice', 'slug' => 'mice'],
                                    ['name' => 'Mouse Pads', 'slug' => 'mouse-pads'],
                                    ['name' => 'Laptop Stickers', 'slug' => 'laptop-stickers'],
                                    ['name' => 'Laptop Skins', 'slug' => 'laptop-skins'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            // ===== DIGITAL PRODUCTS (کالای دیجیتال) =====
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
                            ['name' => 'PS4', 'slug' => 'ps4'],
                            ['name' => 'Xbox', 'slug' => 'xbox'],
                            ['name' => 'GameStick', 'slug' => 'gamestick'],
                            ['name' => 'Nintendo', 'slug' => 'nintendo'],
                        ]
                    ],
                    [
                        'name' => 'Gaming',
                        'slug' => 'gaming',
                        'children' => [
                            ['name' => 'Gaming Sets', 'slug' => 'gaming-sets'],
                            ['name' => 'Game Controllers', 'slug' => 'game-controllers'],
                            ['name' => 'PS5 Games', 'slug' => 'ps5-games'],
                            ['name' => 'PS4 Games', 'slug' => 'ps4-games'],
                            ['name' => 'Xbox 360 Games', 'slug' => 'xbox-360-games'],
                            ['name' => 'PC Games', 'slug' => 'pc-games'],
                            ['name' => 'Game Discs', 'slug' => 'game-discs'],
                            ['name' => 'Racing Wheels', 'slug' => 'racing-wheels'],
                            ['name' => 'Gaming Chairs', 'slug' => 'gaming-chairs'],
                        ]
                    ],
                    [
                        'name' => 'Tablets',
                        'slug' => 'tablets',
                        'children' => [
                            ['name' => 'Windows Tablets', 'slug' => 'windows-tablets'],
                            ['name' => 'Samsung Tablets', 'slug' => 'samsung-tablets'],
                            ['name' => 'Xiaomi Tablets', 'slug' => 'xiaomi-tablets'],
                            ['name' => 'Narte Tablets', 'slug' => 'narte-tablets'],
                        ]
                    ],
                    [
                        'name' => 'Surface',
                        'slug' => 'surface',
                        'children' => [
                            ['name' => 'Surface Pro 8', 'slug' => 'surface-pro-8'],
                            ['name' => 'Surface Pro 9', 'slug' => 'surface-pro-9'],
                            ['name' => 'Surface Pro 10', 'slug' => 'surface-pro-10'],
                            ['name' => 'Surface Pro 11', 'slug' => 'surface-pro-11'],
                        ]
                    ],
                    [
                        'name' => 'iPad',
                        'slug' => 'ipad',
                        'children' => [
                            ['name' => 'iPad Air', 'slug' => 'ipad-air'],
                            ['name' => 'iPad Pro', 'slug' => 'ipad-pro'],
                        ]
                    ],
                    [
                        'name' => 'Computers',
                        'slug' => 'computers',
                        'children' => [
                            ['name' => 'All in One', 'slug' => 'all-in-one'],
                            ['name' => 'Full Computers', 'slug' => 'full-computers'],
                            ['name' => 'Gaming PCs', 'slug' => 'gaming-pcs'],
                            ['name' => 'Mini PCs', 'slug' => 'mini-pcs'],
                        ]
                    ],
                    [
                        'name' => 'Monitors',
                        'slug' => 'monitors',
                        'children' => [
                            ['name' => 'OLED Monitors', 'slug' => 'oled-monitors'],
                            ['name' => 'Monitor Price', 'slug' => 'monitor-price'],
                        ]
                    ],
                    [
                        'name' => 'Speakers',
                        'slug' => 'speakers',
                        'children' => [
                            ['name' => 'Bluetooth Speakers', 'slug' => 'bluetooth-speakers'],
                            ['name' => 'JBL Speakers', 'slug' => 'jbl-speakers'],
                            ['name' => 'Harman Kardon', 'slug' => 'harman-kardon'],
                            ['name' => 'Sony Speakers', 'slug' => 'sony-speakers'],
                            ['name' => 'Tesco Speakers', 'slug' => 'tesco-speakers'],
                            ['name' => 'Maxider Speakers', 'slug' => 'maxider-speakers'],
                            ['name' => 'Microlab Speakers', 'slug' => 'microlab-speakers'],
                            ['name' => 'Party Box', 'slug' => 'party-box'],
                        ]
                    ],
                    [
                        'name' => 'Headphones',
                        'slug' => 'headphones',
                        'children' => [
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
                        ]
                    ],
                    [
                        'name' => 'Smartwatches',
                        'slug' => 'smartwatches',
                        'children' => [
                            ['name' => 'Samsung Smartwatches', 'slug' => 'samsung-smartwatches'],
                            ['name' => 'Xiaomi Smartwatches', 'slug' => 'xiaomi-smartwatches'],
                            ['name' => 'Smartwatch Bands', 'slug' => 'smartwatch-bands'],
                            ['name' => 'Smartwatch Accessories', 'slug' => 'smartwatch-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Apple Watch',
                        'slug' => 'apple-watch',
                        'children' => [
                            ['name' => 'Apple Watch Ultra 3', 'slug' => 'apple-watch-ultra-3'],
                            ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2'],
                            ['name' => 'Apple Watch 11', 'slug' => 'apple-watch-11'],
                            ['name' => 'Apple Watch 10', 'slug' => 'apple-watch-10'],
                            ['name' => 'Apple Watch SE', 'slug' => 'apple-watch-se'],
                        ]
                    ],
                    [
                        'name' => 'Power Banks',
                        'slug' => 'power-banks',
                        'children' => [
                            ['name' => 'Xiaomi Power Banks', 'slug' => 'xiaomi-power-banks'],
                            ['name' => 'Anker Power Banks', 'slug' => 'anker-power-banks'],
                            ['name' => 'Apple Power Banks', 'slug' => 'apple-power-banks'],
                            ['name' => 'Samsung Power Banks', 'slug' => 'samsung-power-banks'],
                            ['name' => 'Energizer Power Banks', 'slug' => 'energizer-power-banks'],
                        ]
                    ],
                    [
                        'name' => 'Cameras',
                        'slug' => 'cameras',
                        'children' => [
                            ['name' => 'Camcorders', 'slug' => 'camcorders'],
                            ['name' => 'Canon Cameras', 'slug' => 'canon-cameras'],
                            ['name' => 'Sony Cameras', 'slug' => 'sony-cameras'],
                            ['name' => 'Instant Print Cameras', 'slug' => 'instant-print-cameras'],
                            ['name' => 'Compact Cameras', 'slug' => 'compact-cameras'],
                            ['name' => 'DSLR Cameras', 'slug' => 'dslr-cameras'],
                        ]
                    ],
                    [
                        'name' => 'Storage Devices',
                        'slug' => 'storage-devices',
                        'children' => [
                            ['name' => 'SSD Drives', 'slug' => 'ssd-drives'],
                            ['name' => 'Internal HDD', 'slug' => 'internal-hdd'],
                            ['name' => 'External HDD', 'slug' => 'external-hdd'],
                            ['name' => 'External SSD', 'slug' => 'external-ssd'],
                            ['name' => 'HDD Boxes', 'slug' => 'hdd-boxes'],
                            ['name' => 'Flash Drives', 'slug' => 'flash-drives'],
                            ['name' => '256GB Flash', 'slug' => '256gb-flash'],
                            ['name' => '128GB Flash', 'slug' => '128gb-flash'],
                            ['name' => '64GB Flash', 'slug' => '64gb-flash'],
                            ['name' => '32GB Flash', 'slug' => '32gb-flash'],
                            ['name' => '16GB Flash', 'slug' => '16gb-flash'],
                        ]
                    ],
                    [
                        'name' => 'Printers',
                        'slug' => 'printers',
                        'children' => [
                            ['name' => '3D Printers', 'slug' => '3d-printers'],
                            ['name' => 'Thermal Printers', 'slug' => 'thermal-printers'],
                            ['name' => 'Color Printers', 'slug' => 'color-printers'],
                            ['name' => 'Label Printers', 'slug' => 'label-printers'],
                            ['name' => 'HP Printers', 'slug' => 'hp-printers'],
                            ['name' => 'Office Machines', 'slug' => 'office-machines'],
                            ['name' => 'Printer Cables', 'slug' => 'printer-cables'],
                            ['name' => 'Cartridges', 'slug' => 'cartridges'],
                            ['name' => 'Scanners', 'slug' => 'scanners'],
                        ]
                    ],
                    [
                        'name' => 'Networking',
                        'slug' => 'networking',
                        'children' => [
                            ['name' => 'USB Hubs', 'slug' => 'usb-hubs'],
                            ['name' => 'Switches', 'slug' => 'switches'],
                            ['name' => 'Hub Switches', 'slug' => 'hub-switches'],
                            ['name' => 'Video Equipment', 'slug' => 'video-equipment'],
                            ['name' => 'Network Cards', 'slug' => 'network-cards'],
                            ['name' => 'Servers', 'slug' => 'servers'],
                            ['name' => 'Network Racks', 'slug' => 'network-racks'],
                            ['name' => 'Patch Panels', 'slug' => 'patch-panels'],
                            ['name' => 'Print Servers', 'slug' => 'print-servers'],
                            ['name' => 'Network Adapters', 'slug' => 'network-adapters'],
                            ['name' => 'Splitters', 'slug' => 'splitters'],
                            ['name' => 'LAN Cables', 'slug' => 'lan-cables'],
                            ['name' => 'CAT6 Cables', 'slug' => 'cat6-cables'],
                            ['name' => 'Combo Cables', 'slug' => 'combo-cables'],
                        ]
                    ],
                    [
                        'name' => 'Modems & Routers',
                        'slug' => 'modems-routers',
                        'children' => [
                            ['name' => 'Pocket Modems', 'slug' => 'pocket-modems'],
                            ['name' => 'Irancel Modems', 'slug' => 'irancel-modems'],
                            ['name' => 'Hamrahe Aval Modems', 'slug' => 'hamrahe-aval-modems'],
                            ['name' => 'Fiber Optic Modems', 'slug' => 'fiber-optic-modems'],
                            ['name' => 'Routers & Access Points', 'slug' => 'routers-access-points'],
                        ]
                    ],
                    [
                        'name' => 'Computer Components',
                        'slug' => 'computer-components',
                        'children' => [
                            ['name' => 'CPU Processors', 'slug' => 'cpu-processors'],
                            ['name' => 'Graphics Cards', 'slug' => 'graphics-cards'],
                            ['name' => 'Motherboards', 'slug' => 'motherboards'],
                            ['name' => 'RAM Memory', 'slug' => 'ram-memory'],
                            ['name' => 'Computer Cases', 'slug' => 'computer-cases'],
                            ['name' => 'Bluetooth Dongles', 'slug' => 'bluetooth-dongles'],
                            ['name' => 'Thermal Paste', 'slug' => 'thermal-paste'],
                            ['name' => 'Keyboards', 'slug' => 'keyboards'],
                            ['name' => 'Gaming Keyboards', 'slug' => 'gaming-keyboards'],
                            ['name' => 'Wireless Keyboards', 'slug' => 'wireless-keyboards'],
                            ['name' => 'Mechanical Keyboards', 'slug' => 'mechanical-keyboards'],
                            ['name' => 'Mice', 'slug' => 'mice'],
                            ['name' => 'Gaming Mice', 'slug' => 'gaming-mice'],
                            ['name' => 'Wireless Mice', 'slug' => 'wireless-mice'],
                        ]
                    ],
                    [
                        'name' => 'Smart Home',
                        'slug' => 'smart-home',
                        'children' => [
                            ['name' => 'Smart Lighting', 'slug' => 'smart-lighting'],
                            ['name' => 'Smart Switches', 'slug' => 'smart-switches'],
                            ['name' => 'Smart Sensors', 'slug' => 'smart-sensors'],
                            ['name' => 'Smart Hubs', 'slug' => 'smart-hubs'],
                            ['name' => 'Robot Vacuums', 'slug' => 'robot-vacuums'],
                        ]
                    ],
                    [
                        'name' => 'Top Brands',
                        'slug' => 'digital-top-brands',
                        'children' => [
                            ['name' => 'Xiaomi', 'slug' => 'xiaomi-digital'],
                            ['name' => 'Samsung', 'slug' => 'samsung-digital'],
                            ['name' => 'Apple', 'slug' => 'apple-digital'],
                            ['name' => 'Huawei', 'slug' => 'huawei-digital'],
                            ['name' => 'Honor', 'slug' => 'honor-digital'],
                            ['name' => 'Realme', 'slug' => 'realme-digital'],
                            ['name' => 'Toshiba', 'slug' => 'toshiba'],
                            ['name' => 'Techno', 'slug' => 'techno'],
                            ['name' => 'Irancel', 'slug' => 'irancel'],
                            ['name' => 'Mobin Net', 'slug' => 'mobin-net'],
                            ['name' => 'Nintendo', 'slug' => 'nintendo-digital'],
                            ['name' => 'Beats', 'slug' => 'beats-digital'],
                            ['name' => 'JBL', 'slug' => 'jbl-digital'],
                            ['name' => 'One More', 'slug' => 'one-more'],
                            ['name' => 'TP-Link', 'slug' => 'tp-link'],
                            ['name' => 'D-Link', 'slug' => 'd-link'],
                            ['name' => 'QCY', 'slug' => 'qcy'],
                        ]
                    ],
                    [
                        'name' => 'Trending',
                        'slug' => 'digital-trending',
                        'children' => [
                            ['name' => 'FC26 PS5', 'slug' => 'fc26-ps5'],
                            ['name' => 'Nothing 1 Headphones', 'slug' => 'nothing-1-headphones'],
                            ['name' => 'Nintendo Switch 2', 'slug' => 'nintendo-switch-2'],
                            ['name' => 'iPad Cases', 'slug' => 'ipad-cases'],
                            ['name' => 'Cat Headphones', 'slug' => 'cat-headphones'],
                            ['name' => 'AirPods Max', 'slug' => 'airpods-max'],
                            ['name' => 'AirPods Pro 3', 'slug' => 'airpods-pro-3'],
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

            foreach ($mainCat['children'] as $child) {
                $childCategory = DB::table('categories')
                    ->where('slug', $child['slug'])
                    ->where('parent_id', $mainId)
                    ->first();
                
                if (!$childCategory) {
                    $childId = DB::table('categories')->insertGetId([
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'icon_key' => null,
                        'sort_order' => 0,
                        'is_active' => 1,
                        'parent_id' => $mainId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $childId = $childCategory->id;
                }
                
                $categoryIds[$child['slug']] = $childId;

                if (isset($child['children'])) {
                    foreach ($child['children'] as $subChild) {
                        $subChildCategory = DB::table('categories')
                            ->where('slug', $subChild['slug'])
                            ->where('parent_id', $childId)
                            ->first();
                        
                        if (!$subChildCategory) {
                            DB::table('categories')->insert([
                                'name' => $subChild['name'],
                                'slug' => $subChild['slug'],
                                'icon_key' => null,
                                'sort_order' => 0,
                                'is_active' => 1,
                                'parent_id' => $childId,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('✅ Categories created successfully!');
    }
}