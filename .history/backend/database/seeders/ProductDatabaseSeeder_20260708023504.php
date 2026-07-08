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
            // 4. HOME & KITCHEN (خانه و آشپزخانه)
            // ================================================================
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
                            ['name' => 'Pots & Pans', 'slug' => 'pots-pans'],
                            ['name' => 'Pressure Cookers', 'slug' => 'pressure-cookers'],
                            ['name' => 'Cooking Sets', 'slug' => 'cooking-sets'],
                            ['name' => 'Kitchen Tools', 'slug' => 'kitchen-tools'],
                            ['name' => 'Spatula & Ladle Sets', 'slug' => 'spatula-ladle-sets'],
                            ['name' => 'Knives', 'slug' => 'knives'],
                            ['name' => 'Cutting Boards', 'slug' => 'cutting-boards'],
                            ['name' => 'Ice Trays', 'slug' => 'ice-trays'],
                            ['name' => 'Funnels', 'slug' => 'funnels'],
                            ['name' => 'Strainers', 'slug' => 'strainers'],
                            ['name' => 'Nutcrackers', 'slug' => 'nutcrackers'],
                            ['name' => 'Kitchen Lighters', 'slug' => 'kitchen-lighters'],
                            ['name' => 'Kitchen Scales', 'slug' => 'kitchen-scales'],
                        ]
                    ],
                    [
                        'name' => 'Tea & Coffee',
                        'slug' => 'tea-coffee',
                        'children' => [
                            ['name' => 'Coffee Makers', 'slug' => 'coffee-makers'],
                            ['name' => 'Samovars', 'slug' => 'samovars'],
                            ['name' => 'Kettles', 'slug' => 'kettles'],
                            ['name' => 'Tea Pots', 'slug' => 'tea-pots'],
                            ['name' => 'Tea Filters', 'slug' => 'tea-filters'],
                        ]
                    ],
                    [
                        'name' => 'Kitchenware',
                        'slug' => 'kitchenware',
                        'children' => [
                            ['name' => 'Colanders', 'slug' => 'colanders'],
                            ['name' => 'Bottles', 'slug' => 'bottles'],
                            ['name' => 'Kitchen Organizers', 'slug' => 'kitchen-organizers'],
                            ['name' => 'Spice Racks', 'slug' => 'spice-racks'],
                            ['name' => 'Storage Containers', 'slug' => 'storage-containers'],
                            ['name' => 'Baskets', 'slug' => 'baskets'],
                            ['name' => 'Potato & Onion Baskets', 'slug' => 'potato-onion-baskets'],
                        ]
                    ],
                    [
                        'name' => 'Dining & Serving',
                        'slug' => 'dining-serving',
                        'children' => [
                            ['name' => 'Tablecloths', 'slug' => 'tablecloths'],
                            ['name' => 'Mugs', 'slug' => 'mugs'],
                            ['name' => 'Trays', 'slug' => 'trays'],
                            ['name' => 'Glasses', 'slug' => 'glasses'],
                            ['name' => 'Plates', 'slug' => 'plates'],
                            ['name' => 'Candy Bowls', 'slug' => 'candy-bowls'],
                            ['name' => 'Pitchers', 'slug' => 'pitchers'],
                            ['name' => 'Ice Cream Bowls', 'slug' => 'ice-cream-bowls'],
                            ['name' => 'Sauce Bowls', 'slug' => 'sauce-bowls'],
                            ['name' => 'Bowls', 'slug' => 'bowls'],
                            ['name' => 'Coolers & Thermoses', 'slug' => 'coolers-thermoses'],
                            ['name' => 'Dinnerware Sets', 'slug' => 'dinnerware-sets'],
                            ['name' => 'Cutlery', 'slug' => 'cutlery'],
                            ['name' => 'Coasters & Trivets', 'slug' => 'coasters-trivets'],
                            ['name' => 'Party Supplies', 'slug' => 'party-supplies'],
                        ]
                    ],
                    [
                        'name' => 'Kitchen Textiles',
                        'slug' => 'kitchen-textiles',
                        'children' => [
                            ['name' => 'Oven Mitts', 'slug' => 'oven-mitts'],
                            ['name' => 'Aprons', 'slug' => 'aprons'],
                            ['name' => 'Bread Bags', 'slug' => 'bread-bags'],
                            ['name' => 'Cleaning Cloths', 'slug' => 'cleaning-cloths'],
                        ]
                    ],
                    [
                        'name' => 'Furniture',
                        'slug' => 'furniture',
                        'children' => [
                            ['name' => 'Sofas', 'slug' => 'sofas'],
                            ['name' => 'Sofa Beds', 'slug' => 'sofa-beds'],
                            ['name' => 'Air Sofas', 'slug' => 'air-sofas'],
                            ['name' => 'Teddy Sofas', 'slug' => 'teddy-sofas'],
                            ['name' => 'L-Shaped Sofas', 'slug' => 'l-shaped-sofas'],
                            ['name' => 'Minimal Sofas', 'slug' => 'minimal-sofas'],
                            ['name' => 'Chester Sofas', 'slug' => 'chester-sofas'],
                            ['name' => 'Office Chairs', 'slug' => 'office-chairs'],
                            ['name' => 'Ottomans', 'slug' => 'ottomans'],
                            ['name' => 'Kids Chairs', 'slug' => 'kids-chairs'],
                        ]
                    ],
                    [
                        'name' => 'Home Decor',
                        'slug' => 'home-decor',
                        'children' => [
                            ['name' => 'Bookshelves', 'slug' => 'bookshelves'],
                            ['name' => 'Shelves', 'slug' => 'shelves'],
                            ['name' => 'Consoles', 'slug' => 'consoles'],
                            ['name' => 'Chairs', 'slug' => 'chairs'],
                            ['name' => 'TV Stands', 'slug' => 'tv-stands'],
                            ['name' => 'Dining Tables', 'slug' => 'dining-tables'],
                            ['name' => 'Office Furniture', 'slug' => 'office-furniture'],
                            ['name' => 'Office Chairs', 'slug' => 'office-chairs-decor'],
                            ['name' => 'Office Desks', 'slug' => 'office-desks'],
                        ]
                    ],
                    [
                        'name' => 'Clothing & Storage',
                        'slug' => 'clothing-storage',
                        'children' => [
                            ['name' => 'Clothing Racks', 'slug' => 'clothing-racks'],
                            ['name' => 'Wardrobes', 'slug' => 'wardrobes'],
                            ['name' => 'Shoe Racks', 'slug' => 'shoe-racks'],
                            ['name' => 'Coat Hangers', 'slug' => 'coat-hangers'],
                            ['name' => 'Appliance Covers', 'slug' => 'appliance-covers'],
                        ]
                    ],
                    [
                        'name' => 'Decorative',
                        'slug' => 'decorative',
                        'children' => [
                            ['name' => 'Statues', 'slug' => 'statues'],
                            ['name' => 'Clocks', 'slug' => 'clocks'],
                            ['name' => 'Incense', 'slug' => 'incense'],
                            ['name' => 'Candles', 'slug' => 'candles'],
                            ['name' => 'Candle Holders', 'slug' => 'candle-holders'],
                            ['name' => 'Wall Decor', 'slug' => 'wall-decor'],
                            ['name' => 'Smudge Sticks', 'slug' => 'smudge-sticks'],
                            ['name' => 'Flowers & Pots', 'slug' => 'flowers-pots'],
                            ['name' => 'Decorative Mirrors', 'slug' => 'decorative-mirrors'],
                            ['name' => 'Photo Frames', 'slug' => 'photo-frames'],
                            ['name' => 'Canvases', 'slug' => 'canvases'],
                        ]
                    ],
                    [
                        'name' => 'Cushions & Rugs',
                        'slug' => 'cushions-rugs',
                        'children' => [
                            ['name' => 'Table Runners', 'slug' => 'table-runners'],
                            ['name' => 'Cushions', 'slug' => 'cushions'],
                            ['name' => 'Sofa & Bed Covers', 'slug' => 'sofa-bed-covers'],
                            ['name' => 'Posters', 'slug' => 'posters'],
                            ['name' => 'Wallpapers', 'slug' => 'wallpapers'],
                            ['name' => 'Parquet', 'slug' => 'parquet'],
                            ['name' => 'Stickers', 'slug' => 'stickers'],
                            ['name' => 'Curtains', 'slug' => 'curtains'],
                            ['name' => 'Curtain Accessories', 'slug' => 'curtain-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Lighting',
                        'slug' => 'lighting',
                        'children' => [
                            ['name' => 'Table Lamps', 'slug' => 'table-lamps'],
                            ['name' => 'Chandeliers', 'slug' => 'chandeliers'],
                            ['name' => 'Lamps', 'slug' => 'lamps'],
                        ]
                    ],
                    [
                        'name' => 'Carpets & Rugs',
                        'slug' => 'carpets-rugs',
                        'children' => [
                            ['name' => 'Carpets', 'slug' => 'carpets'],
                            ['name' => 'Rugs', 'slug' => 'rugs'],
                            ['name' => 'Modern Carpets', 'slug' => 'modern-carpets'],
                            ['name' => 'Carpet Tapestries', 'slug' => 'carpet-tapestries'],
                            ['name' => 'Door Mats', 'slug' => 'door-mats'],
                            ['name' => 'Underlays', 'slug' => 'underlays'],
                        ]
                    ],
                    [
                        'name' => 'Bathroom',
                        'slug' => 'bathroom',
                        'children' => [
                            ['name' => 'Towels', 'slug' => 'towels'],
                            ['name' => 'Slippers', 'slug' => 'slippers-bath'],
                            ['name' => 'Loofahs', 'slug' => 'loofahs'],
                            ['name' => 'Shower Curtains', 'slug' => 'shower-curtains'],
                            ['name' => 'Bathtubs', 'slug' => 'bathtubs'],
                            ['name' => 'Bathroom Tools', 'slug' => 'bathroom-tools'],
                        ]
                    ],
                    [
                        'name' => 'Toilet & Bathroom',
                        'slug' => 'toilet-bathroom',
                        'children' => [
                            ['name' => 'Toilets', 'slug' => 'toilets'],
                            ['name' => 'Bathroom Sets', 'slug' => 'bathroom-sets'],
                            ['name' => 'Sinks', 'slug' => 'sinks'],
                            ['name' => 'Toothbrush Holders', 'slug' => 'toothbrush-holders'],
                            ['name' => 'Bathroom Mirrors', 'slug' => 'bathroom-mirrors'],
                            ['name' => 'Flush Tanks', 'slug' => 'flush-tanks'],
                            ['name' => 'Soap Dispensers', 'slug' => 'soap-dispensers'],
                            ['name' => 'Toilet Paper Holders', 'slug' => 'toilet-paper-holders'],
                            ['name' => 'Traditional Toilets', 'slug' => 'traditional-toilets'],
                            ['name' => 'Watering Cans', 'slug' => 'watering-cans'],
                        ]
                    ],
                    [
                        'name' => 'Bedroom',
                        'slug' => 'bedroom',
                        'children' => [
                            ['name' => 'Beds', 'slug' => 'beds'],
                            ['name' => 'Duvets', 'slug' => 'duvets'],
                            ['name' => 'Bedspreads', 'slug' => 'bedspreads'],
                            ['name' => 'Bedroom Sets', 'slug' => 'bedroom-sets'],
                            ['name' => 'Sheets', 'slug' => 'sheets'],
                            ['name' => 'Pillows', 'slug' => 'pillows'],
                            ['name' => 'Mattresses', 'slug' => 'mattresses'],
                            ['name' => 'Pillow Cases', 'slug' => 'pillow-cases'],
                            ['name' => 'Comfort Mattresses', 'slug' => 'comfort-mattresses'],
                            ['name' => 'Blankets', 'slug' => 'blankets'],
                        ]
                    ],
                    [
                        'name' => 'Cleaning',
                        'slug' => 'cleaning',
                        'children' => [
                            ['name' => 'Buckets', 'slug' => 'buckets'],
                            ['name' => 'Mops', 'slug' => 'mops'],
                            ['name' => 'Brushes', 'slug' => 'brushes'],
                            ['name' => 'Brooms', 'slug' => 'brooms'],
                            ['name' => 'Lint Rollers', 'slug' => 'lint-rollers'],
                            ['name' => 'Clothespins', 'slug' => 'clothespins'],
                            ['name' => 'Clothing Covers', 'slug' => 'clothing-covers'],
                            ['name' => 'Vacuum Bags', 'slug' => 'vacuum-bags'],
                            ['name' => 'Laundry Baskets', 'slug' => 'laundry-baskets'],
                            ['name' => 'Clotheslines', 'slug' => 'clotheslines'],
                            ['name' => 'Ironing Boards', 'slug' => 'ironing-boards'],
                            ['name' => 'Iron Accessories', 'slug' => 'iron-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Pet Supplies',
                        'slug' => 'pet-supplies',
                        'children' => [
                            ['name' => 'Pet Hygiene', 'slug' => 'pet-hygiene'],
                            ['name' => 'Pet Training', 'slug' => 'pet-training'],
                            ['name' => 'Pet Beds', 'slug' => 'pet-beds'],
                            ['name' => 'Pet Food', 'slug' => 'pet-food'],
                            ['name' => 'Aquarium Supplies', 'slug' => 'aquarium-supplies'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 5. HOME APPLIANCES (لوازم خانگی برقی)
            // ================================================================
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'icon_key' => 'home-appliances',
                'sort_order' => 5,
                'children' => [
                    [
                        'name' => 'Cooling & Heating',
                        'slug' => 'cooling-heating',
                        'children' => [
                            ['name' => 'Air Conditioners', 'slug' => 'air-conditioners'],
                            ['name' => 'Swamp Coolers', 'slug' => 'swamp-coolers'],
                            ['name' => 'Water Coolers', 'slug' => 'water-coolers'],
                            ['name' => 'Fans', 'slug' => 'fans'],
                            ['name' => 'Rechargeable Fans', 'slug' => 'rechargeable-fans'],
                            ['name' => 'Air Purifiers', 'slug' => 'air-purifiers'],
                            ['name' => 'Wall-Mounted Boilers', 'slug' => 'wall-mounted-boilers'],
                            ['name' => 'Radiators', 'slug' => 'radiators'],
                            ['name' => 'Electric Heaters', 'slug' => 'electric-heaters'],
                            ['name' => 'Water Heaters', 'slug' => 'water-heaters'],
                        ]
                    ],
                    [
                        'name' => 'Washing Machines',
                        'slug' => 'washing-machines',
                        'children' => [
                            ['name' => 'LG Washing Machines', 'slug' => 'lg-washing-machines'],
                            ['name' => 'Daewoo Washing Machines', 'slug' => 'daewoo-washing-machines'],
                            ['name' => 'X-Vision Washing Machines', 'slug' => 'x-vision-washing-machines'],
                            ['name' => 'Pakshoma Washing Machines', 'slug' => 'pakshoma-washing-machines'],
                            ['name' => 'Snowa Washing Machines', 'slug' => 'snowa-washing-machines'],
                            ['name' => 'Mini Washers', 'slug' => 'mini-washers'],
                        ]
                    ],
                    [
                        'name' => 'Vacuums',
                        'slug' => 'vacuums',
                        'children' => [
                            ['name' => 'Robot Vacuums', 'slug' => 'robot-vacuums'],
                            ['name' => 'Cordless Vacuums', 'slug' => 'cordless-vacuums'],
                            ['name' => 'Vacuum Accessories', 'slug' => 'vacuum-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Dishwashers',
                        'slug' => 'dishwashers',
                        'children' => [
                            ['name' => 'Bosch Dishwashers', 'slug' => 'bosch-dishwashers'],
                            ['name' => 'LG Dishwashers', 'slug' => 'lg-dishwashers'],
                            ['name' => 'Daewoo Dishwashers', 'slug' => 'daewoo-dishwashers'],
                            ['name' => 'Pakshoma Dishwashers', 'slug' => 'pakshoma-dishwashers'],
                            ['name' => 'X-Vision Dishwashers', 'slug' => 'x-vision-dishwashers'],
                        ]
                    ],
                    [
                        'name' => 'Audio & Video',
                        'slug' => 'audio-video',
                        'children' => [
                            ['name' => 'Radios', 'slug' => 'radios'],
                            ['name' => 'Soundbars', 'slug' => 'soundbars'],
                            ['name' => 'Projectors', 'slug' => 'projectors-appliances'],
                            ['name' => 'Android Boxes', 'slug' => 'android-boxes'],
                            ['name' => 'Home Players', 'slug' => 'home-players'],
                            ['name' => 'AV Accessories', 'slug' => 'av-accessories'],
                            ['name' => 'Remote Controls', 'slug' => 'remote-controls'],
                        ]
                    ],
                    [
                        'name' => 'Refrigerators',
                        'slug' => 'refrigerators',
                        'children' => [
                            ['name' => 'Side by Side', 'slug' => 'side-by-side'],
                            ['name' => 'Twin Refrigerators', 'slug' => 'twin-refrigerators'],
                            ['name' => 'Hotel Refrigerators', 'slug' => 'hotel-refrigerators'],
                            ['name' => 'Top-Bottom (Combi)', 'slug' => 'top-bottom-combi'],
                        ]
                    ],
                    [
                        'name' => 'TVs',
                        'slug' => 'tvs',
                        'children' => [
                            ['name' => 'Sony TVs', 'slug' => 'sony-tvs'],
                            ['name' => 'TCL TVs', 'slug' => 'tcl-tvs'],
                            ['name' => 'J-Plus TVs', 'slug' => 'j-plus-tvs'],
                            ['name' => 'X-Vision TVs', 'slug' => 'x-vision-tvs'],
                            ['name' => 'Android TVs', 'slug' => 'android-tvs'],
                            ['name' => '4K TVs', 'slug' => '4k-tvs'],
                            ['name' => 'Gaming TVs', 'slug' => 'gaming-tvs'],
                            ['name' => 'OLED TVs', 'slug' => 'oled-tvs'],
                            ['name' => 'QLED TVs', 'slug' => 'qled-tvs'],
                            ['name' => '85 Inch TVs', 'slug' => '85-inch-tvs'],
                            ['name' => '75 Inch TVs', 'slug' => '75-inch-tvs'],
                            ['name' => '70 Inch TVs', 'slug' => '70-inch-tvs'],
                            ['name' => '65 Inch TVs', 'slug' => '65-inch-tvs'],
                            ['name' => '60 Inch TVs', 'slug' => '60-inch-tvs'],
                            ['name' => '55 Inch TVs', 'slug' => '55-inch-tvs'],
                            ['name' => '50 Inch TVs', 'slug' => '50-inch-tvs'],
                        ]
                    ],
                    [
                        'name' => 'Cooking Appliances',
                        'slug' => 'cooking-appliances',
                        'children' => [
                            ['name' => 'Egg Cookers', 'slug' => 'egg-cookers'],
                            ['name' => 'Air Fryers', 'slug' => 'air-fryers'],
                            ['name' => 'Microwaves', 'slug' => 'microwaves'],
                            ['name' => 'Gas Cooktops', 'slug' => 'gas-cooktops'],
                            ['name' => 'Rice Cookers', 'slug' => 'rice-cookers'],
                            ['name' => 'Sandwich Makers', 'slug' => 'sandwich-makers'],
                            ['name' => 'Toaster Ovens', 'slug' => 'toaster-ovens'],
                            ['name' => 'Grills', 'slug' => 'grills'],
                            ['name' => 'Toasters', 'slug' => 'toasters'],
                            ['name' => 'Irons', 'slug' => 'irons'],
                            ['name' => 'Steam Irons', 'slug' => 'steam-irons'],
                            ['name' => 'Steamers', 'slug' => 'steamers'],
                        ]
                    ],
                    [
                        'name' => 'Beverage Makers',
                        'slug' => 'beverage-makers',
                        'children' => [
                            ['name' => 'Coffee Makers', 'slug' => 'coffee-makers-appliances'],
                            ['name' => 'Espresso Machines', 'slug' => 'espresso-machines'],
                            ['name' => 'Tea Makers', 'slug' => 'tea-makers'],
                            ['name' => 'Electric Kettles', 'slug' => 'electric-kettles'],
                            ['name' => 'Electric Samovars', 'slug' => 'electric-samovars'],
                            ['name' => 'Water Coolers', 'slug' => 'water-coolers-appliances'],
                            ['name' => 'Juice Extractors', 'slug' => 'juice-extractors'],
                            ['name' => 'Citrus Presses', 'slug' => 'citrus-presses'],
                            ['name' => 'Coffee Grinders', 'slug' => 'coffee-grinders'],
                        ]
                    ],
                    [
                        'name' => 'Sewing Machines',
                        'slug' => 'sewing-machines',
                        'children' => [
                            ['name' => 'Industrial Sewing Machines', 'slug' => 'industrial-sewing-machines'],
                            ['name' => 'Home Sewing Machines', 'slug' => 'home-sewing-machines'],
                            ['name' => 'Janome Sewing Machines', 'slug' => 'janome-sewing-machines'],
                            ['name' => 'Kachiran Sewing Machines', 'slug' => 'kachiran-sewing-machines'],
                        ]
                    ],
                    [
                        'name' => 'Food Processors',
                        'slug' => 'food-processors',
                        'children' => [
                            ['name' => 'Electric Meat Grinders', 'slug' => 'electric-meat-grinders'],
                            ['name' => 'Food Processors', 'slug' => 'food-processors'],
                            ['name' => 'Mixers', 'slug' => 'mixers'],
                            ['name' => 'Blenders', 'slug' => 'blenders'],
                            ['name' => 'Meat Grinders', 'slug' => 'meat-grinders'],
                            ['name' => 'Choppers', 'slug' => 'choppers'],
                            ['name' => 'Grinders', 'slug' => 'grinders'],
                        ]
                    ],
                    [
                        'name' => 'Water Purifiers',
                        'slug' => 'water-purifiers',
                        'children' => [
                            ['name' => 'Water Filters', 'slug' => 'water-filters'],
                        ]
                    ],
                    [
                        'name' => 'Built-in Appliances',
                        'slug' => 'built-in-appliances',
                        'children' => [
                            ['name' => 'Ovens', 'slug' => 'ovens'],
                            ['name' => 'Hoods', 'slug' => 'hoods'],
                            ['name' => 'Gas Hobs', 'slug' => 'gas-hobs'],
                            ['name' => 'Kitchen Sinks', 'slug' => 'kitchen-sinks'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 6. BEAUTY & HEALTH (آرایشی بهداشتی)
            // ================================================================
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
                            ['name' => 'Sunscreen', 'slug' => 'sunscreen'],
                            ['name' => 'Moisturizers', 'slug' => 'moisturizers'],
                            ['name' => 'Face Masks', 'slug' => 'face-masks'],
                            ['name' => 'Face Cleansers', 'slug' => 'face-cleansers'],
                            ['name' => 'Toners', 'slug' => 'toners'],
                            ['name' => 'Body Care', 'slug' => 'body-care'],
                            ['name' => 'Skin Care Tools', 'slug' => 'skin-care-tools'],
                        ]
                    ],
                    [
                        'name' => 'Makeup',
                        'slug' => 'makeup',
                        'children' => [
                            ['name' => 'Nail Care', 'slug' => 'nail-care'],
                            ['name' => 'Makeup Tools', 'slug' => 'makeup-tools'],
                            ['name' => 'Face Makeup', 'slug' => 'face-makeup'],
                            ['name' => 'Foundation', 'slug' => 'foundation'],
                            ['name' => 'Blush', 'slug' => 'blush'],
                            ['name' => 'Concealer', 'slug' => 'concealer'],
                            ['name' => 'Contour', 'slug' => 'contour'],
                            ['name' => 'Eye Makeup', 'slug' => 'eye-makeup'],
                            ['name' => 'Mascara', 'slug' => 'mascara'],
                            ['name' => 'Eyeliner', 'slug' => 'eyeliner'],
                            ['name' => 'Eye Pencil', 'slug' => 'eye-pencil'],
                            ['name' => 'Lip Makeup', 'slug' => 'lip-makeup'],
                            ['name' => 'Lip Pencil', 'slug' => 'lip-pencil'],
                            ['name' => 'Lip Balm', 'slug' => 'lip-balm'],
                            ['name' => 'Lip Tint', 'slug' => 'lip-tint'],
                        ]
                    ],
                    [
                        'name' => 'Hair Care',
                        'slug' => 'hair-care',
                        'children' => [
                            ['name' => 'Hair Treatments', 'slug' => 'hair-treatments'],
                            ['name' => 'Hair Styling', 'slug' => 'hair-styling'],
                            ['name' => 'Conditioners', 'slug' => 'conditioners'],
                            ['name' => 'Shampoos', 'slug' => 'shampoos'],
                        ]
                    ],
                    [
                        'name' => 'Oral Care',
                        'slug' => 'oral-care',
                        'children' => [
                            ['name' => 'Toothbrushes', 'slug' => 'toothbrushes'],
                            ['name' => 'Toothpaste', 'slug' => 'toothpaste'],
                            ['name' => 'Deodorants', 'slug' => 'deodorants'],
                            ['name' => 'Condoms', 'slug' => 'condoms'],
                            ['name' => 'Sanitary Pads', 'slug' => 'sanitary-pads'],
                            ['name' => 'Menstrual Cups', 'slug' => 'menstrual-cups'],
                            ['name' => 'Hair Removal Tools', 'slug' => 'hair-removal-tools'],
                            ['name' => 'Razors & Refills', 'slug' => 'razors-refills'],
                            ['name' => 'Body Wash', 'slug' => 'body-wash'],
                            ['name' => 'Men\'s Body Wash', 'slug' => 'mens-body-wash'],
                            ['name' => 'Women\'s Body Wash', 'slug' => 'womens-body-wash'],
                            ['name' => 'Oily Skin Body Wash', 'slug' => 'oily-skin-body-wash'],
                            ['name' => 'Dry Skin Body Wash', 'slug' => 'dry-skin-body-wash'],
                        ]
                    ],
                    [
                        'name' => 'Perfumes',
                        'slug' => 'perfumes',
                        'children' => [
                            ['name' => 'Women\'s Perfumes', 'slug' => 'womens-perfumes'],
                            ['name' => 'Men\'s Perfumes', 'slug' => 'mens-perfumes'],
                            ['name' => 'Body Sprays', 'slug' => 'body-sprays'],
                            ['name' => 'Pocket Perfumes', 'slug' => 'pocket-perfumes'],
                        ]
                    ],
                    [
                        'name' => 'Electrical Personal Care',
                        'slug' => 'electrical-personal-care',
                        'children' => [
                            ['name' => 'Hair Dryers', 'slug' => 'hair-dryers'],
                            ['name' => 'Hair Straighteners', 'slug' => 'hair-straighteners'],
                            ['name' => 'Facial Hair Trimmers', 'slug' => 'facial-hair-trimmers'],
                            ['name' => 'Hair Clippers', 'slug' => 'hair-clippers'],
                            ['name' => 'Shaving Accessories', 'slug' => 'shaving-accessories'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 7. FASHION (مد و پوشاک)
            // ================================================================
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
                            ['name' => 'Coats & Jackets', 'slug' => 'coats-jackets-men'],
                            ['name' => 'Men\'s Jackets', 'slug' => 'mens-jackets'],
                            ['name' => 'Men\'s Coats', 'slug' => 'mens-coats'],
                            ['name' => 'Leather Jackets', 'slug' => 'leather-jackets-men'],
                            ['name' => 'Puffers', 'slug' => 'puffers-men'],
                            ['name' => 'Hoodies', 'slug' => 'hoodies-men'],
                            ['name' => 'Sweatshirts', 'slug' => 'sweatshirts-men'],
                            ['name' => 'Knitwear', 'slug' => 'knitwear-men'],
                            ['name' => 'Polos', 'slug' => 'polos-men'],
                            ['name' => 'Men\'s Pants', 'slug' => 'mens-pants'],
                            ['name' => 'Underwear', 'slug' => 'underwear-men'],
                            ['name' => 'T-Shirts', 'slug' => 't-shirts-men'],
                            ['name' => 'Shirts', 'slug' => 'shirts-men'],
                            ['name' => 'Suits & Vests', 'slug' => 'suits-vests-men'],
                            ['name' => 'Blazers', 'slug' => 'blazers-men'],
                            ['name' => 'Suits', 'slug' => 'suits-men'],
                            ['name' => 'Vests', 'slug' => 'vests-men'],
                            ['name' => 'Shorts', 'slug' => 'shorts-men'],
                            ['name' => 'Slash Pants', 'slug' => 'slash-pants-men'],
                            ['name' => 'Mom Style Pants', 'slug' => 'mom-style-pants-men'],
                            ['name' => 'Cargo Pants', 'slug' => 'cargo-pants-men'],
                            ['name' => 'Jeans', 'slug' => 'jeans-men'],
                            ['name' => 'Loungewear', 'slug' => 'loungewear-men'],
                            ['name' => 'Socks', 'slug' => 'socks-men'],
                            ['name' => 'Wallets', 'slug' => 'wallets-men'],
                            ['name' => 'Men\'s Shoes', 'slug' => 'mens-shoes'],
                            ['name' => 'Boots', 'slug' => 'boots-men'],
                            ['name' => 'Sports Shoes', 'slug' => 'sports-shoes-men'],
                            ['name' => 'College Shoes', 'slug' => 'college-shoes-men'],
                            ['name' => 'Sandals', 'slug' => 'sandals-men'],
                            ['name' => 'Flip Flops', 'slug' => 'flip-flops-men'],
                            ['name' => 'Giveh', 'slug' => 'giveh-men'],
                            ['name' => 'Accessories', 'slug' => 'accessories-men'],
                            ['name' => 'Eyewear', 'slug' => 'eyewear-men'],
                            ['name' => 'Belts', 'slug' => 'belts-men'],
                            ['name' => 'Hats', 'slug' => 'hats-men'],
                            ['name' => 'Ties', 'slug' => 'ties-men'],
                            ['name' => 'Sports Clothing', 'slug' => 'sports-clothing-men'],
                            ['name' => 'Men\'s Swimwear', 'slug' => 'mens-swimwear'],
                            ['name' => 'Track Suits', 'slug' => 'track-suits-men'],
                            ['name' => 'Sports Accessories', 'slug' => 'sports-accessories-men'],
                            ['name' => 'Sports Shoes', 'slug' => 'sports-shoes-men-accessories'],
                            ['name' => 'Gift Sets', 'slug' => 'gift-sets-men'],
                            ['name' => 'Underwear Sets', 'slug' => 'underwear-sets-men'],
                            ['name' => 'Undershirts', 'slug' => 'undershirts-men'],
                            ['name' => 'Boxers', 'slug' => 'boxers-men'],
                        ]
                    ],
                    [
                        'name' => 'Women\'s Clothing',
                        'slug' => 'womens-clothing',
                        'children' => [
                            ['name' => 'Coats & Jackets', 'slug' => 'coats-jackets-women'],
                            ['name' => 'Women\'s Jackets', 'slug' => 'womens-jackets'],
                            ['name' => 'Women\'s Coats', 'slug' => 'womens-coats'],
                            ['name' => 'Leather Coats', 'slug' => 'leather-coats-women'],
                            ['name' => 'Raincoats', 'slug' => 'raincoats-women'],
                            ['name' => 'Women\'s Puffers', 'slug' => 'womens-puffers'],
                            ['name' => 'Hoodies', 'slug' => 'hoodies-women'],
                            ['name' => 'Sweatshirts', 'slug' => 'sweatshirts-women'],
                            ['name' => 'Knitwear', 'slug' => 'knitwear-women'],
                            ['name' => 'Manteau', 'slug' => 'manteau'],
                            ['name' => 'Short Manteau', 'slug' => 'short-manteau'],
                            ['name' => 'Long Manteau', 'slug' => 'long-manteau'],
                            ['name' => 'Open Front Manteau', 'slug' => 'open-front-manteau'],
                            ['name' => 'Crepe Manteau', 'slug' => 'crepe-manteau'],
                            ['name' => 'Hidden Button Manteau', 'slug' => 'hidden-button-manteau'],
                            ['name' => 'Formal Manteau', 'slug' => 'formal-manteau'],
                            ['name' => 'Robes', 'slug' => 'robes'],
                            ['name' => 'Button Manteau', 'slug' => 'button-manteau'],
                            ['name' => 'Closed Front Manteau', 'slug' => 'closed-front-manteau'],
                            ['name' => 'Lace Manteau', 'slug' => 'lace-manteau'],
                            ['name' => 'Velvet Manteau', 'slug' => 'velvet-manteau'],
                            ['name' => 'Thread Manteau', 'slug' => 'thread-manteau'],
                            ['name' => 'Floral Manteau', 'slug' => 'floral-manteau'],
                            ['name' => 'Headscarves', 'slug' => 'headscarves'],
                            ['name' => 'Maternity Wear', 'slug' => 'maternity-wear'],
                            ['name' => 'Maternity Pants', 'slug' => 'maternity-pants'],
                            ['name' => 'Maternity Dresses', 'slug' => 'maternity-dresses'],
                            ['name' => 'Blouses & Tops', 'slug' => 'blouses-tops-women'],
                            ['name' => 'Casual Wear', 'slug' => 'casual-wear-women'],
                            ['name' => 'Tops', 'slug' => 'tops-women'],
                            ['name' => 'T-Shirts', 'slug' => 't-shirts-women'],
                            ['name' => 'Skirts', 'slug' => 'skirts-women'],
                            ['name' => 'Dresses', 'slug' => 'dresses-women'],
                            ['name' => 'Pajamas & Sleepwear', 'slug' => 'pajamas-sleepwear-women'],
                            ['name' => 'Blouses', 'slug' => 'blouses-women'],
                            ['name' => 'Bodysuits', 'slug' => 'bodysuits-women'],
                            ['name' => 'Tunics', 'slug' => 'tunics-women'],
                            ['name' => 'Suits & Vests', 'slug' => 'suits-vests-women'],
                            ['name' => 'Women\'s Underwear', 'slug' => 'womens-underwear'],
                            ['name' => 'Panties', 'slug' => 'panties-women'],
                            ['name' => 'Bras', 'slug' => 'bras'],
                            ['name' => 'Girdles', 'slug' => 'girdles'],
                            ['name' => 'Laser Girdles', 'slug' => 'laser-girdles'],
                            ['name' => 'Hourglass Girdles', 'slug' => 'hourglass-girdles'],
                            ['name' => 'Women\'s Pants', 'slug' => 'womens-pants'],
                            ['name' => 'Jeans', 'slug' => 'jeans-women'],
                            ['name' => 'Leggings', 'slug' => 'leggings-women'],
                            ['name' => 'Shorts', 'slug' => 'shorts-women'],
                            ['name' => 'Cargo Pants', 'slug' => 'cargo-pants-women'],
                            ['name' => 'Baggy Pants', 'slug' => 'baggy-pants-women'],
                            ['name' => 'Mom Style Pants', 'slug' => 'mom-style-pants-women'],
                            ['name' => 'Sportswear', 'slug' => 'sportswear-women'],
                            ['name' => 'Women\'s Tracksuits', 'slug' => 'womens-tracksuits'],
                            ['name' => 'Sports Tops', 'slug' => 'sports-tops-women'],
                            ['name' => 'Women\'s Swimwear', 'slug' => 'womens-swimwear'],
                            ['name' => 'Women\'s Shoes', 'slug' => 'womens-shoes'],
                            ['name' => 'Boots', 'slug' => 'boots-women'],
                            ['name' => 'Sports Shoes', 'slug' => 'sports-shoes-women'],
                            ['name' => 'College Shoes', 'slug' => 'college-shoes-women'],
                            ['name' => 'Sandals', 'slug' => 'sandals-women'],
                            ['name' => 'Flip Flops', 'slug' => 'flip-flops-women'],
                            ['name' => 'Giveh', 'slug' => 'giveh-women'],
                            ['name' => 'Bags', 'slug' => 'bags-women'],
                            ['name' => 'Accessories', 'slug' => 'accessories-women'],
                            ['name' => 'Women\'s Watches', 'slug' => 'womens-watches'],
                            ['name' => 'Eyewear', 'slug' => 'eyewear-women'],
                            ['name' => 'Women\'s Belts', 'slug' => 'womens-belts'],
                            ['name' => 'Gift Sets', 'slug' => 'gift-sets-women'],
                        ]
                    ],
                    [
                        'name' => 'Children\'s Clothing',
                        'slug' => 'childrens-clothing',
                        'children' => [
                            ['name' => 'Baby & Kids Sets', 'slug' => 'baby-kids-sets'],
                            ['name' => 'Kids Bags & Shoes', 'slug' => 'kids-bags-shoes'],
                            ['name' => 'Boys\' Clothing', 'slug' => 'boys-clothing'],
                            ['name' => 'Boys\' T-Shirts', 'slug' => 'boys-t-shirts'],
                            ['name' => 'Girls\' Clothing', 'slug' => 'girls-clothing'],
                            ['name' => 'Girls\' Dresses', 'slug' => 'girls-dresses'],
                            ['name' => 'Girls\' Sets', 'slug' => 'girls-sets'],
                            ['name' => 'Girls\' Sweatshirts', 'slug' => 'girls-sweatshirts'],
                            ['name' => 'Girls\' Jeans', 'slug' => 'girls-jeans'],
                            ['name' => 'Newborn Clothing', 'slug' => 'newborn-clothing'],
                            ['name' => 'Newborn Sets', 'slug' => 'newborn-sets'],
                            ['name' => 'Newborn Bodysuits', 'slug' => 'newborn-bodysuits'],
                            ['name' => 'Rompers', 'slug' => 'rompers'],
                        ]
                    ],
                    [
                        'name' => 'Fashion Brands',
                        'slug' => 'fashion-brands',
                        'children' => [
                            ['name' => 'Casio', 'slug' => 'casio'],
                            ['name' => 'Novin Charm', 'slug' => 'novin-charm'],
                            ['name' => 'One by One', 'slug' => 'one-by-one'],
                            ['name' => 'Chrome', 'slug' => 'chrome'],
                            ['name' => 'Tourivar', 'slug' => 'tourivar'],
                            ['name' => 'Mel & Mouj', 'slug' => 'mel-mouj'],
                            ['name' => 'Hamto', 'slug' => 'hamto'],
                            ['name' => 'Charm Mashhad', 'slug' => 'charm-mashhad'],
                            ['name' => 'Asmara', 'slug' => 'asmara'],
                            ['name' => 'Serjeh', 'slug' => 'serjeh'],
                            ['name' => 'Gordieh', 'slug' => 'gordieh'],
                            ['name' => 'Charm Ataroud', 'slug' => 'charm-ataroud'],
                            ['name' => 'Tolika', 'slug' => 'tolika'],
                            ['name' => 'Pama', 'slug' => 'pama'],
                            ['name' => 'I-Tech', 'slug' => 'i-tech'],
                            ['name' => 'Mom Fit Pants', 'slug' => 'mom-fit-pants'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 8. GOLD & JEWELRY (طلا و نقره)
            // ================================================================
            [
                'name' => 'Gold & Jewelry',
                'slug' => 'gold-jewelry',
                'icon_key' => 'gold-jewelry',
                'sort_order' => 8,
                'children' => [
                    [
                        'name' => 'Gold Deals',
                        'slug' => 'gold-deals',
                        'children' => [
                            ['name' => 'Today\'s Gold Discounts', 'slug' => 'todays-gold-discounts'],
                            ['name' => 'Gold Under 10M', 'slug' => 'gold-under-10m'],
                            ['name' => 'Gold Under 15M', 'slug' => 'gold-under-15m'],
                            ['name' => 'Gold Under 20M', 'slug' => 'gold-under-20m'],
                        ]
                    ],
                    [
                        'name' => 'Gold Bars & Coins',
                        'slug' => 'gold-bars-coins',
                        'children' => [
                            ['name' => 'Gold Bars', 'slug' => 'gold-bars'],
                            ['name' => 'Gold Coins', 'slug' => 'gold-coins'],
                            ['name' => 'Quarter Coins', 'slug' => 'quarter-coins'],
                            ['name' => 'Half Coins', 'slug' => 'half-coins'],
                            ['name' => 'Full Coins', 'slug' => 'full-coins'],
                            ['name' => 'Parsian Coins', 'slug' => 'parsian-coins'],
                            ['name' => 'Installment Coins', 'slug' => 'installment-coins'],
                            ['name' => 'Melted Gold', 'slug' => 'melted-gold'],
                        ]
                    ],
                    [
                        'name' => 'Women\'s Gold Jewelry',
                        'slug' => 'womens-gold-jewelry',
                        'children' => [
                            ['name' => 'Gold Necklaces', 'slug' => 'gold-necklaces'],
                            ['name' => 'Pearl Necklaces', 'slug' => 'pearl-necklaces'],
                            ['name' => 'Evil Eye Necklaces', 'slug' => 'evil-eye-necklaces'],
                            ['name' => 'Van Cleef Necklaces', 'slug' => 'van-cleef-necklaces'],
                            ['name' => 'Name Necklaces', 'slug' => 'name-necklaces'],
                            ['name' => 'Cartier Necklaces', 'slug' => 'cartier-necklaces'],
                            ['name' => 'Birthstone Necklaces', 'slug' => 'birthstone-necklaces'],
                            ['name' => 'Roll Necklaces', 'slug' => 'roll-necklaces'],
                            ['name' => 'Gold Rings', 'slug' => 'gold-rings'],
                            ['name' => 'Gemstone Rings', 'slug' => 'gemstone-rings'],
                            ['name' => 'Cartier Rings', 'slug' => 'cartier-rings'],
                            ['name' => 'Van Cleef Rings', 'slug' => 'van-cleef-rings'],
                            ['name' => 'Delicate Rings', 'slug' => 'delicate-rings'],
                            ['name' => 'Evil Eye Rings', 'slug' => 'evil-eye-rings'],
                            ['name' => 'Gold Toe Rings', 'slug' => 'gold-toe-rings'],
                            ['name' => 'Pearl Rings', 'slug' => 'pearl-rings'],
                            ['name' => 'Plain Rings', 'slug' => 'plain-rings'],
                            ['name' => 'Gold Earrings', 'slug' => 'gold-earrings'],
                            ['name' => 'Stud Earrings', 'slug' => 'stud-earrings'],
                            ['name' => 'Clip Earrings', 'slug' => 'clip-earrings'],
                            ['name' => 'Hoop Earrings', 'slug' => 'hoop-earrings'],
                            ['name' => 'Gold Bracelets', 'slug' => 'gold-bracelets'],
                            ['name' => 'Cartier Bracelets', 'slug' => 'cartier-bracelets'],
                            ['name' => 'Evil Eye Bracelets', 'slug' => 'evil-eye-bracelets'],
                            ['name' => 'Pearl Bracelets', 'slug' => 'pearl-bracelets'],
                            ['name' => 'Leather & Gold Bracelets', 'slug' => 'leather-gold-bracelets'],
                            ['name' => 'Tiffany Bracelets', 'slug' => 'tiffany-bracelets'],
                            ['name' => 'Name Bracelets', 'slug' => 'name-bracelets'],
                            ['name' => 'Gold Anklets', 'slug' => 'gold-anklets'],
                            ['name' => 'Gold Chains', 'slug' => 'gold-chains'],
                            ['name' => 'Flamingo Chains', 'slug' => 'flamingo-chains'],
                            ['name' => 'Venetian Chains', 'slug' => 'venetian-chains'],
                            ['name' => 'Miro Chains', 'slug' => 'miro-chains'],
                            ['name' => 'Fishbone Chains', 'slug' => 'fishbone-chains'],
                        ]
                    ],
                    [
                        'name' => 'Low-Wage Gold',
                        'slug' => 'low-wage-gold',
                        'children' => [
                            ['name' => 'Gold Necklaces', 'slug' => 'low-wage-necklaces'],
                            ['name' => 'Gold Anklets', 'slug' => 'low-wage-anklets'],
                            ['name' => 'Gold Bracelets', 'slug' => 'low-wage-bracelets'],
                            ['name' => 'Gold Earrings', 'slug' => 'low-wage-earrings'],
                            ['name' => 'Gold Accessories', 'slug' => 'gold-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Men\'s Gold Jewelry',
                        'slug' => 'mens-gold-jewelry',
                        'children' => [
                            ['name' => 'Gold Rings', 'slug' => 'mens-gold-rings'],
                            ['name' => 'Gold Chains', 'slug' => 'mens-gold-chains'],
                            ['name' => 'Gold Necklaces', 'slug' => 'mens-gold-necklaces'],
                            ['name' => 'Gold Bracelets', 'slug' => 'mens-gold-bracelets'],
                            ['name' => 'Gold Sets', 'slug' => 'mens-gold-sets'],
                        ]
                    ],
                    [
                        'name' => 'Kids\' Gold Jewelry',
                        'slug' => 'kids-gold-jewelry',
                        'children' => [
                            ['name' => 'Gold Bracelets', 'slug' => 'kids-gold-bracelets'],
                            ['name' => 'Gold Earrings', 'slug' => 'kids-gold-earrings'],
                            ['name' => 'Gold Sets', 'slug' => 'kids-gold-sets'],
                            ['name' => 'Gold Necklaces', 'slug' => 'kids-gold-necklaces'],
                            ['name' => 'Gold Pendants', 'slug' => 'kids-gold-pendants'],
                            ['name' => 'Gold Rings', 'slug' => 'kids-gold-rings'],
                            ['name' => 'Gold Brooches', 'slug' => 'kids-gold-brooches'],
                        ]
                    ],
                    [
                        'name' => 'Silver Jewelry',
                        'slug' => 'silver-jewelry',
                        'children' => [
                            ['name' => 'Silver Bars', 'slug' => 'silver-bars'],
                            ['name' => 'Women\'s Silver Jewelry', 'slug' => 'womens-silver-jewelry'],
                            ['name' => 'Silver Rings', 'slug' => 'silver-rings'],
                            ['name' => 'Silver Bracelets', 'slug' => 'silver-bracelets'],
                            ['name' => 'Silver Necklaces', 'slug' => 'silver-necklaces'],
                            ['name' => 'Silver Chains', 'slug' => 'silver-chains'],
                            ['name' => 'Silver Earrings', 'slug' => 'silver-earrings'],
                            ['name' => 'Silver Anklets', 'slug' => 'silver-anklets'],
                            ['name' => 'Silver Piercings', 'slug' => 'silver-piercings'],
                            ['name' => 'Silver Pendants', 'slug' => 'silver-pendants'],
                            ['name' => 'Men\'s Silver Jewelry', 'slug' => 'mens-silver-jewelry'],
                            ['name' => 'Silver Rings', 'slug' => 'mens-silver-rings'],
                            ['name' => 'Silver Necklaces', 'slug' => 'mens-silver-necklaces'],
                            ['name' => 'Silver Chains', 'slug' => 'mens-silver-chains'],
                            ['name' => 'Silver Bracelets', 'slug' => 'mens-silver-bracelets'],
                            ['name' => 'Gold Pendants', 'slug' => 'gold-pendants'],
                            ['name' => 'Flower Pendants', 'slug' => 'flower-pendants'],
                            ['name' => 'Star Pendants', 'slug' => 'star-pendants'],
                            ['name' => 'Butterfly Pendants', 'slug' => 'butterfly-pendants'],
                            ['name' => 'Heart Pendants', 'slug' => 'heart-pendants'],
                            ['name' => 'Name Pendants', 'slug' => 'name-pendants'],
                            ['name' => 'Infinity Pendants', 'slug' => 'infinity-pendants'],
                            ['name' => 'Silver Watch Charms', 'slug' => 'silver-watch-charms'],
                            ['name' => 'Women\'s Gold Brooches', 'slug' => 'womens-gold-brooches'],
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
                ];

            // ================================================================
            // 9. VEHICLES (خودرو و موتورسیکلت)
            // ================================================================
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'icon_key' => 'vehicles',
                'sort_order' => 9,
                'children' => [
                    [
                        'name' => 'Vehicles',
                        'slug' => 'vehicles-main',
                        'children' => [
                            ['name' => 'Motorcycles', 'slug' => 'motorcycles'],
                            ['name' => 'Cars', 'slug' => 'cars'],
                        ]
                    ],
                    [
                        'name' => 'Car Consumables',
                        'slug' => 'car-consumables',
                        'children' => [
                            ['name' => 'Engine Oil', 'slug' => 'engine-oil'],
                            ['name' => 'Filters', 'slug' => 'filters'],
                            ['name' => 'Service Parts', 'slug' => 'service-parts'],
                            ['name' => 'Transmission Oil', 'slug' => 'transmission-oil'],
                            ['name' => 'Hydraulic Oil', 'slug' => 'hydraulic-oil'],
                            ['name' => 'Antifreeze', 'slug' => 'antifreeze'],
                            ['name' => 'Batteries', 'slug' => 'batteries'],
                            ['name' => 'Tires', 'slug' => 'tires'],
                            ['name' => 'Fuel Additives', 'slug' => 'fuel-additives'],
                            ['name' => 'Waxes & Polishes', 'slug' => 'waxes-polishes'],
                            ['name' => 'Car Cleaning Supplies', 'slug' => 'car-cleaning-supplies'],
                        ]
                    ],
                    [
                        'name' => 'Car Parts',
                        'slug' => 'car-parts',
                        'children' => [
                            ['name' => 'Body Parts', 'slug' => 'body-parts'],
                            ['name' => 'Lights', 'slug' => 'lights'],
                            ['name' => 'Side Mirrors', 'slug' => 'side-mirrors'],
                            ['name' => 'Suspension Parts', 'slug' => 'suspension-parts'],
                            ['name' => 'Electronic Parts', 'slug' => 'electronic-parts'],
                            ['name' => 'Mechanical Parts', 'slug' => 'mechanical-parts'],
                        ]
                    ],
                    [
                        'name' => 'Car Audio & Video',
                        'slug' => 'car-audio-video',
                        'children' => [
                            ['name' => 'Dashcams', 'slug' => 'dashcams'],
                            ['name' => 'Players', 'slug' => 'players'],
                            ['name' => 'Speakers', 'slug' => 'speakers-car'],
                            ['name' => 'Amplifiers', 'slug' => 'amplifiers'],
                            ['name' => 'FM Players', 'slug' => 'fm-players'],
                            ['name' => 'Car Navigation', 'slug' => 'car-navigation'],
                            ['name' => 'AV Accessories', 'slug' => 'av-accessories-car'],
                        ]
                    ],
                    [
                        'name' => 'Car Accessories',
                        'slug' => 'car-accessories',
                        'children' => [
                            ['name' => 'Floor Mats', 'slug' => 'floor-mats'],
                            ['name' => 'Dashcams', 'slug' => 'dashcams-accessories'],
                            ['name' => 'Seat Covers', 'slug' => 'seat-covers'],
                            ['name' => 'Tire Chains', 'slug' => 'tire-chains'],
                            ['name' => 'Sunshades', 'slug' => 'sunshades'],
                            ['name' => 'Organizers', 'slug' => 'organizers'],
                            ['name' => 'Headlights', 'slug' => 'headlights'],
                            ['name' => 'Trunk Mats', 'slug' => 'trunk-mats'],
                            ['name' => 'Car Covers', 'slug' => 'car-covers'],
                            ['name' => 'Sound Insulation', 'slug' => 'sound-insulation'],
                            ['name' => 'Comfort Equipment', 'slug' => 'comfort-equipment'],
                            ['name' => 'Anti-Theft Devices', 'slug' => 'anti-theft-devices'],
                            ['name' => 'Footrests', 'slug' => 'footrests'],
                            ['name' => 'Spoilers', 'slug' => 'spoilers'],
                            ['name' => 'Key Holders', 'slug' => 'key-holders'],
                            ['name' => 'Air Fresheners', 'slug' => 'air-fresheners'],
                            ['name' => 'Roof Racks', 'slug' => 'roof-racks'],
                            ['name' => 'Exhaust Tips', 'slug' => 'exhaust-tips'],
                            ['name' => 'Gear Shifters', 'slug' => 'gear-shifters'],
                            ['name' => 'Decorative Accessories', 'slug' => 'decorative-accessories'],
                            ['name' => 'Off-Road Equipment', 'slug' => 'off-road-equipment'],
                            ['name' => 'Engine Tuning', 'slug' => 'engine-tuning'],
                            ['name' => 'Car Jacks', 'slug' => 'car-jacks'],
                            ['name' => 'Seat Belts', 'slug' => 'seat-belts'],
                            ['name' => 'Car Antennas', 'slug' => 'car-antennas'],
                            ['name' => 'Tire Valves', 'slug' => 'tire-valves'],
                            ['name' => 'Car Horns', 'slug' => 'car-horns'],
                            ['name' => 'Steering Wheels', 'slug' => 'steering-wheels'],
                            ['name' => 'Other Accessories', 'slug' => 'other-accessories'],
                        ]
                    ],
                    [
                        'name' => 'Motorcycle Parts',
                        'slug' => 'motorcycle-parts',
                        'children' => [
                            ['name' => 'Motorcycle Consumables', 'slug' => 'motorcycle-consumables'],
                            ['name' => 'Motorcycle Parts', 'slug' => 'motorcycle-parts'],
                            ['name' => 'Motorcycle Accessories', 'slug' => 'motorcycle-accessories'],
                            ['name' => 'Anti-Theft Devices', 'slug' => 'motorcycle-anti-theft'],
                        ]
                    ],
                    [
                        'name' => 'By Car Model',
                        'slug' => 'by-car-model',
                        'children' => [
                            ['name' => 'Peugeot 206-207', 'slug' => 'peugeot-206-207'],
                            ['name' => 'Sandero-L90', 'slug' => 'sandero-l90'],
                            ['name' => 'Parsia-Peugeot 405', 'slug' => 'parsia-peugeot-405'],
                            ['name' => 'Pride-Tiba', 'slug' => 'pride-tiba'],
                            ['name' => 'Samand-Dena', 'slug' => 'samand-dena'],
                            ['name' => 'Quick-Saina', 'slug' => 'quick-saina'],
                            ['name' => 'Zantia-Brilliance', 'slug' => 'zantia-brilliance'],
                            ['name' => 'MVM-Phoenix', 'slug' => 'mvm-phoenix'],
                            ['name' => 'Jack-Lifan', 'slug' => 'jack-lifan'],
                            ['name' => 'Hyundai-Kia', 'slug' => 'hyundai-kia'],
                            ['name' => 'Toyota-Renault', 'slug' => 'toyota-renault'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 10. HEALTH & MEDICAL (سلامت و پزشکی)
            // ================================================================
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
                            ['name' => 'Blood Pressure Monitors', 'slug' => 'blood-pressure-monitors'],
                            ['name' => 'Thermometers', 'slug' => 'thermometers'],
                            ['name' => 'Glucose Meters', 'slug' => 'glucose-meters'],
                            ['name' => 'Nebulizers', 'slug' => 'nebulizers'],
                            ['name' => 'Hearing Aids', 'slug' => 'hearing-aids'],
                            ['name' => 'Stethoscopes', 'slug' => 'stethoscopes'],
                        ]
                    ],
                    [
                        'name' => 'Orthopedic',
                        'slug' => 'orthopedic',
                        'children' => [
                            ['name' => 'Back Braces', 'slug' => 'back-braces'],
                            ['name' => 'Knee Braces', 'slug' => 'knee-braces'],
                            ['name' => 'Wrist Braces', 'slug' => 'wrist-braces'],
                            ['name' => 'Ankle Supports', 'slug' => 'ankle-supports'],
                            ['name' => 'Orthopedic Shoes', 'slug' => 'orthopedic-shoes'],
                        ]
                    ],
                    [
                        'name' => 'Supplements',
                        'slug' => 'supplements',
                        'children' => [
                            ['name' => 'Vitamins', 'slug' => 'vitamins'],
                            ['name' => 'Minerals', 'slug' => 'minerals'],
                            ['name' => 'Protein Supplements', 'slug' => 'protein-supplements'],
                            ['name' => 'Herbal Supplements', 'slug' => 'herbal-supplements'],
                            ['name' => 'Omega-3', 'slug' => 'omega-3'],
                        ]
                    ],
                    [
                        'name' => 'Dental Care',
                        'slug' => 'dental-care',
                        'children' => [
                            ['name' => 'Electric Toothbrushes', 'slug' => 'electric-toothbrushes'],
                            ['name' => 'Dental Floss', 'slug' => 'dental-floss'],
                            ['name' => 'Mouthwash', 'slug' => 'mouthwash'],
                            ['name' => 'Teeth Whitening', 'slug' => 'teeth-whitening'],
                        ]
                    ],
                    [
                        'name' => 'First Aid',
                        'slug' => 'first-aid',
                        'children' => [
                            ['name' => 'First Aid Kits', 'slug' => 'first-aid-kits'],
                            ['name' => 'Bandages', 'slug' => 'bandages'],
                            ['name' => 'Antiseptics', 'slug' => 'antiseptics'],
                            ['name' => 'Pain Relievers', 'slug' => 'pain-relievers'],
                        ]
                    ],
                    [
                        'name' => 'Fitness Equipment',
                        'slug' => 'fitness-equipment',
                        'children' => [
                            ['name' => 'Treadmills', 'slug' => 'treadmills'],
                            ['name' => 'Exercise Bikes', 'slug' => 'exercise-bikes'],
                            ['name' => 'Dumbbells', 'slug' => 'dumbbells'],
                            ['name' => 'Yoga Mats', 'slug' => 'yoga-mats'],
                            ['name' => 'Resistance Bands', 'slug' => 'resistance-bands'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 11. TOOLS & EQUIPMENT (ابزارآلات و تجهیزات)
            // ================================================================
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
                            ['name' => 'Drills', 'slug' => 'drills'],
                            ['name' => 'Angle Grinders', 'slug' => 'angle-grinders'],
                            ['name' => 'Circular Saws', 'slug' => 'circular-saws'],
                            ['name' => 'Jigsaws', 'slug' => 'jigsaws'],
                            ['name' => 'Impact Wrenches', 'slug' => 'impact-wrenches'],
                            ['name' => 'Sanders', 'slug' => 'sanders'],
                        ]
                    ],
                    [
                        'name' => 'Hand Tools',
                        'slug' => 'hand-tools',
                        'children' => [
                            ['name' => 'Screwdrivers', 'slug' => 'screwdrivers'],
                            ['name' => 'Wrenches', 'slug' => 'wrenches'],
                            ['name' => 'Pliers', 'slug' => 'pliers'],
                            ['name' => 'Hammers', 'slug' => 'hammers'],
                            ['name' => 'Tape Measures', 'slug' => 'tape-measures'],
                            ['name' => 'Tool Sets', 'slug' => 'tool-sets'],
                        ]
                    ],
                    [
                        'name' => 'Gardening Tools',
                        'slug' => 'gardening-tools',
                        'children' => [
                            ['name' => 'Lawn Mowers', 'slug' => 'lawn-mowers'],
                            ['name' => 'Hedge Trimmers', 'slug' => 'hedge-trimmers'],
                            ['name' => 'Garden Shears', 'slug' => 'garden-shears'],
                            ['name' => 'Shovels', 'slug' => 'shovels'],
                            ['name' => 'Rakes', 'slug' => 'rakes'],
                            ['name' => 'Watering Cans', 'slug' => 'watering-cans-tools'],
                        ]
                    ],
                    [
                        'name' => 'Safety Equipment',
                        'slug' => 'safety-equipment',
                        'children' => [
                            ['name' => 'Safety Helmets', 'slug' => 'safety-helmets'],
                            ['name' => 'Safety Glasses', 'slug' => 'safety-glasses'],
                            ['name' => 'Work Gloves', 'slug' => 'work-gloves'],
                            ['name' => 'Ear Protection', 'slug' => 'ear-protection'],
                            ['name' => 'Safety Boots', 'slug' => 'safety-boots'],
                        ]
                    ],
                    [
                        'name' => 'Measuring Tools',
                        'slug' => 'measuring-tools',
                        'children' => [
                            ['name' => 'Laser Measures', 'slug' => 'laser-measures'],
                            ['name' => 'Spirit Levels', 'slug' => 'spirit-levels'],
                            ['name' => 'Calipers', 'slug' => 'calipers'],
                            ['name' => 'Micrometers', 'slug' => 'micrometers'],
                        ]
                    ],
                    [
                        'name' => 'Tool Sets',
                        'slug' => 'tool-sets',
                        'children' => [
                            ['name' => 'Mechanic Tool Sets', 'slug' => 'mechanic-tool-sets'],
                            ['name' => 'Home Tool Sets', 'slug' => 'home-tool-sets'],
                            ['name' => 'Electrician Tool Sets', 'slug' => 'electrician-tool-sets'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 12. BOOKS & ART (کتاب و هنر)
            // ================================================================
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
                            ['name' => 'Novels', 'slug' => 'novels'],
                            ['name' => 'Science Books', 'slug' => 'science-books'],
                            ['name' => 'History Books', 'slug' => 'history-books'],
                            ['name' => 'Children Books', 'slug' => 'children-books'],
                            ['name' => 'Self-Help Books', 'slug' => 'self-help-books'],
                            ['name' => 'Poetry Books', 'slug' => 'poetry-books'],
                            ['name' => 'Art Books', 'slug' => 'art-books'],
                        ]
                    ],
                    [
                        'name' => 'Art & Painting',
                        'slug' => 'art-painting',
                        'children' => [
                            ['name' => 'Oil Paintings', 'slug' => 'oil-paintings'],
                            ['name' => 'Watercolor Paintings', 'slug' => 'watercolor-paintings'],
                            ['name' => 'Calligraphy', 'slug' => 'calligraphy'],
                            ['name' => 'Prints', 'slug' => 'prints'],
                            ['name' => 'Art Supplies', 'slug' => 'art-supplies'],
                            ['name' => 'Canvas', 'slug' => 'canvas'],
                            ['name' => 'Brushes', 'slug' => 'brushes-art'],
                            ['name' => 'Paints', 'slug' => 'paints'],
                        ]
                    ],
                    [
                        'name' => 'Handicrafts',
                        'slug' => 'handicrafts',
                        'children' => [
                            ['name' => 'Pottery', 'slug' => 'pottery'],
                            ['name' => 'Wood Crafts', 'slug' => 'wood-crafts'],
                            ['name' => 'Metal Crafts', 'slug' => 'metal-crafts'],
                            ['name' => 'Textile Crafts', 'slug' => 'textile-crafts'],
                            ['name' => 'Glass Art', 'slug' => 'glass-art'],
                            ['name' => 'Ceramics', 'slug' => 'ceramics'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 13. SPORTS & TRAVEL (ورزش و سفر)
            // ================================================================
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
                            ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment-sports'],
                            ['name' => 'Boxing Equipment', 'slug' => 'boxing-equipment'],
                            ['name' => 'Basketball', 'slug' => 'basketball'],
                            ['name' => 'Football', 'slug' => 'football'],
                            ['name' => 'Volleyball', 'slug' => 'volleyball'],
                            ['name' => 'Yoga Equipment', 'slug' => 'yoga-equipment'],
                            ['name' => 'Swimming Gear', 'slug' => 'swimming-gear'],
                        ]
                    ],
                    [
                        'name' => 'Sportswear',
                        'slug' => 'sportswear',
                        'children' => [
                            ['name' => 'Sports T-Shirts', 'slug' => 'sports-t-shirts'],
                            ['name' => 'Sports Shorts', 'slug' => 'sports-shorts'],
                            ['name' => 'Track Suits', 'slug' => 'track-suits'],
                            ['name' => 'Compression Wear', 'slug' => 'compression-wear'],
                            ['name' => 'Sports Socks', 'slug' => 'sports-socks'],
                            ['name' => 'Sports Bras', 'slug' => 'sports-bras'],
                        ]
                    ],
                    [
                        'name' => 'Travel Equipment',
                        'slug' => 'travel-equipment',
                        'children' => [
                            ['name' => 'Suitcases', 'slug' => 'suitcases'],
                            ['name' => 'Backpacks', 'slug' => 'backpacks-travel'],
                            ['name' => 'Travel Bags', 'slug' => 'travel-bags'],
                            ['name' => 'Travel Accessories', 'slug' => 'travel-accessories'],
                            ['name' => 'Camping Gear', 'slug' => 'camping-gear'],
                            ['name' => 'Hiking Gear', 'slug' => 'hiking-gear'],
                            ['name' => 'Travel Pillows', 'slug' => 'travel-pillows'],
                            ['name' => 'Luggage Tags', 'slug' => 'luggage-tags'],
                        ]
                    ],
                    [
                        'name' => 'Outdoor Sports',
                        'slug' => 'outdoor-sports',
                        'children' => [
                            ['name' => 'Camping Tents', 'slug' => 'camping-tents'],
                            ['name' => 'Sleeping Bags', 'slug' => 'sleeping-bags'],
                            ['name' => 'Climbing Gear', 'slug' => 'climbing-gear'],
                            ['name' => 'Fishing Equipment', 'slug' => 'fishing-equipment'],
                            ['name' => 'Cycling Gear', 'slug' => 'cycling-gear'],
                            ['name' => 'Skiing Gear', 'slug' => 'skiing-gear'],
                        ]
                    ],
                    [
                        'name' => 'Cycling',
                        'slug' => 'cycling',
                        'children' => [
                            ['name' => 'Bicycles', 'slug' => 'bicycles'],
                            ['name' => 'Electric Bikes', 'slug' => 'electric-bikes'],
                            ['name' => 'Cycling Helmets', 'slug' => 'cycling-helmets'],
                            ['name' => 'Bike Accessories', 'slug' => 'bike-accessories'],
                            ['name' => 'Bike Parts', 'slug' => 'bike-parts'],
                        ]
                    ],
                    [
                        'name' => 'Camping',
                        'slug' => 'camping',
                        'children' => [
                            ['name' => 'Camping Tents', 'slug' => 'camping-tents'],
                            ['name' => 'Sleeping Bags', 'slug' => 'sleeping-bags-camping'],
                            ['name' => 'Camping Chairs', 'slug' => 'camping-chairs'],
                            ['name' => 'Camping Tables', 'slug' => 'camping-tables'],
                            ['name' => 'Camping Stoves', 'slug' => 'camping-stoves'],
                            ['name' => 'Camping Lights', 'slug' => 'camping-lights'],
                        ]
                    ],
                    [
                        'name' => 'Hiking',
                        'slug' => 'hiking',
                        'children' => [
                            ['name' => 'Hiking Boots', 'slug' => 'hiking-boots'],
                            ['name' => 'Hiking Backpacks', 'slug' => 'hiking-backpacks'],
                            ['name' => 'Trekking Poles', 'slug' => 'trekking-poles'],
                            ['name' => 'Hiking Clothing', 'slug' => 'hiking-clothing'],
                        ]
                    ],
                ]
                ];

            // ================================================================
            // 14. GIFT CARDS (کارت هدیه)
            // ================================================================
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
                        ]
                    ],
                    [
                        'name' => 'Digital Gift Cards',
                        'slug' => 'digital-gift-cards',
                        'children' => [
                            ['name' => 'E-Gift Cards', 'slug' => 'e-gift-cards'],
                            ['name' => 'Virtual Gift Cards', 'slug' => 'virtual-gift-cards'],
                            ['name' => 'Gaming Gift Cards', 'slug' => 'gaming-gift-cards'],
                            ['name' => 'PlayStation Gift Card', 'slug' => 'playstation-gift-card'],
                            ['name' => 'Xbox Gift Card', 'slug' => 'xbox-gift-card'],
                            ['name' => 'Nintendo Gift Card', 'slug' => 'nintendo-gift-card'],
                            ['name' => 'Steam Gift Card', 'slug' => 'steam-gift-card'],
                            ['name' => 'Google Play Gift Card', 'slug' => 'google-play-gift-card'],
                            ['name' => 'App Store Gift Card', 'slug' => 'app-store-gift-card'],
                        ]
                    ],
                    [
                        'name' => 'Custom Gift Cards',
                        'slug' => 'custom-gift-cards',
                        'children' => [
                            ['name' => 'Personalized Gift Cards', 'slug' => 'personalized-gift-cards'],
                            ['name' => 'Corporate Gift Cards', 'slug' => 'corporate-gift-cards'],
                            ['name' => 'Birthday Gift Cards', 'slug' => 'birthday-gift-cards'],
                            ['name' => 'Wedding Gift Cards', 'slug' => 'wedding-gift-cards'],
                            ['name' => 'Holiday Gift Cards', 'slug' => 'holiday-gift-cards'],
                        ]
                    ],
                ]
                ];
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

        $this->command->info('✅ All categories created successfully!');
        $this->command->info('📊 Total categories: ' . DB::table('categories')->count());
    }
}
