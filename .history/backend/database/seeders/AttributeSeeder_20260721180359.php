<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Creating attributes and values...');

        // ================================================================
        // 1. CREATE ATTRIBUTES (ویژگی‌ها)
        // ================================================================
        $attributes = [
            // ===== General =====
            ['name' => 'Color', 'slug' => 'color', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 1],
            ['name' => 'Size', 'slug' => 'size', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 2],
            ['name' => 'Material', 'slug' => 'material', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 3],
            ['name' => 'Weight', 'slug' => 'weight', 'type' => 'select', 'unit' => 'g', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 4],
            ['name' => 'Year', 'slug' => 'year', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 5],
            
            // ===== Mobile =====
            ['name' => 'Storage', 'slug' => 'storage', 'type' => 'select', 'unit' => 'GB', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 10],
            ['name' => 'RAM', 'slug' => 'ram', 'type' => 'select', 'unit' => 'GB', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 11],
            ['name' => 'Processor', 'slug' => 'processor', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 12],
            ['name' => 'Battery', 'slug' => 'battery', 'type' => 'select', 'unit' => 'mAh', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 13],
            ['name' => 'Display Size', 'slug' => 'display-size', 'type' => 'select', 'unit' => 'inch', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 14],
            ['name' => 'Display Type', 'slug' => 'display-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 15],
            ['name' => 'Water Resistant', 'slug' => 'water-resistant', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 16],
            ['name' => 'Camera MP', 'slug' => 'camera-mp', 'type' => 'select', 'unit' => 'MP', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 17],
            ['name' => 'SIM Type', 'slug' => 'sim-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 18],
            ['name' => '5G Support', 'slug' => '5g-support', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 19],
            
            // ===== Laptops =====
            ['name' => 'Screen Size', 'slug' => 'screen-size', 'type' => 'select', 'unit' => 'inch', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 20],
            ['name' => 'Graphics Card', 'slug' => 'graphics-card', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 21],
            ['name' => 'Operating System', 'slug' => 'operating-system', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 22],
            ['name' => 'SSD Storage', 'slug' => 'ssd-storage', 'type' => 'select', 'unit' => 'GB', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 23],
            ['name' => 'CPU Model', 'slug' => 'cpu-model', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 24],
            ['name' => 'Refresh Rate', 'slug' => 'refresh-rate', 'type' => 'select', 'unit' => 'Hz', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 25],
            
            // ===== Digital =====
            ['name' => 'Connectivity', 'slug' => 'connectivity', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 30],
            ['name' => 'Battery Life', 'slug' => 'battery-life', 'type' => 'select', 'unit' => 'hours', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 31],
            ['name' => 'Noise Cancellation', 'slug' => 'noise-cancellation', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 32],
            ['name' => 'Waterproof', 'slug' => 'waterproof', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 33],
            ['name' => 'Bluetooth Version', 'slug' => 'bluetooth-version', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 34],
            ['name' => 'Controller Type', 'slug' => 'controller-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 35],
            
            // ===== Home & Kitchen =====
            ['name' => 'Color Type', 'slug' => 'color-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 40],
            ['name' => 'Product Type', 'slug' => 'product-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 41],
            ['name' => 'Capacity', 'slug' => 'capacity', 'type' => 'select', 'unit' => 'L', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 42],
            ['name' => 'Power Usage', 'slug' => 'power-usage', 'type' => 'select', 'unit' => 'W', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 43],
            ['name' => 'Material Type', 'slug' => 'material-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 44],
            
            // ===== Fashion =====
            ['name' => 'Clothing Size', 'slug' => 'clothing-size', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 50],
            ['name' => 'Fabric Type', 'slug' => 'fabric-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 51],
            ['name' => 'Season', 'slug' => 'season', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 52],
            ['name' => 'Style', 'slug' => 'style', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 53],
            ['name' => 'Gender', 'slug' => 'gender', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 54],
            
            // ===== Jewelry =====
            ['name' => 'Gold Karat', 'slug' => 'gold-karat', 'type' => 'select', 'unit' => 'K', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 60],
            ['name' => 'Metal Type', 'slug' => 'metal-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 61],
            ['name' => 'Gemstone', 'slug' => 'gemstone', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 62],
            ['name' => 'Watch Movement', 'slug' => 'watch-movement', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 63],
            
            // ===== Vehicles =====
            ['name' => 'Engine Type', 'slug' => 'engine-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 70],
            ['name' => 'Transmission', 'slug' => 'transmission', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 71],
            ['name' => 'Fuel Type', 'slug' => 'fuel-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 72],
            ['name' => 'Car Model Year', 'slug' => 'car-model-year', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 73],
            ['name' => 'Mileage', 'slug' => 'mileage', 'type' => 'select', 'unit' => 'km', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 74],
            
            // ===== Tools =====
            ['name' => 'Tool Type', 'slug' => 'tool-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 80],
            ['name' => 'Power Source', 'slug' => 'power-source', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 81],
            ['name' => 'Voltage', 'slug' => 'voltage', 'type' => 'select', 'unit' => 'V', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 82],
            ['name' => 'Power Rating', 'slug' => 'power-rating', 'type' => 'select', 'unit' => 'W', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 83],
            
            // ===== Books =====
            ['name' => 'Author', 'slug' => 'author', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 90],
            ['name' => 'Publisher', 'slug' => 'publisher', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 91],
            ['name' => 'Book Language', 'slug' => 'book-language', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 92],
            ['name' => 'Cover Type', 'slug' => 'cover-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 93],
            ['name' => 'Pages Count', 'slug' => 'pages-count', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 94],
            ['name' => 'Book Genre', 'slug' => 'book-genre', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 95],
            
            // ===== Sports =====
            ['name' => 'Sport Type', 'slug' => 'sport-type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 100],
            ['name' => 'Shoe Size', 'slug' => 'shoe-size', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 102],
            ['name' => 'Sport Level', 'slug' => 'sport-level', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 103],
            ['name' => 'Weather Resistance', 'slug' => 'weather-resistance', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 104],
        ];

        $attributeIds = [];
        foreach ($attributes as $attr) {
            $id = DB::table('attributes')->insertGetId([
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'type' => $attr['type'],
                'unit' => $attr['unit'],
                'is_filterable' => $attr['is_filterable'],
                'is_variant' => $attr['is_variant'],
                'is_required' => 0,
                'sort_order' => $attr['sort_order'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $attributeIds[$attr['slug']] = $id;
        }

        // ================================================================
        // 2. CREATE ATTRIBUTE VALUES
        // ================================================================
        $attributeValues = [
            // ===== Color =====
            'color' => [
                ['value' => 'Black', 'slug' => 'black', 'color_code' => '#1a1a1a'],
                ['value' => 'White', 'slug' => 'white', 'color_code' => '#ffffff'],
                ['value' => 'Red', 'slug' => 'red', 'color_code' => '#e74c3c'],
                ['value' => 'Blue', 'slug' => 'blue', 'color_code' => '#3498db'],
                ['value' => 'Green', 'slug' => 'green', 'color_code' => '#2ecc71'],
                ['value' => 'Gold', 'slug' => 'gold', 'color_code' => '#f1c40f'],
                ['value' => 'Silver', 'slug' => 'silver', 'color_code' => '#bdc3c7'],
                ['value' => 'Pink', 'slug' => 'pink', 'color_code' => '#fd79a8'],
                ['value' => 'Purple', 'slug' => 'purple', 'color_code' => '#9b59b6'],
                ['value' => 'Orange', 'slug' => 'orange', 'color_code' => '#e67e22'],
                ['value' => 'Gray', 'slug' => 'gray', 'color_code' => '#95a5a6'],
                ['value' => 'Brown', 'slug' => 'brown', 'color_code' => '#8B4513'],
                ['value' => 'Rose Gold', 'slug' => 'rose-gold', 'color_code' => '#e8a87c'],
            ],
            
            // ===== Size =====
            'size' => [
                ['value' => 'S', 'slug' => 's', 'color_code' => null],
                ['value' => 'M', 'slug' => 'm', 'color_code' => null],
                ['value' => 'L', 'slug' => 'l', 'color_code' => null],
                ['value' => 'XL', 'slug' => 'xl', 'color_code' => null],
                ['value' => 'XXL', 'slug' => 'xxl', 'color_code' => null],
                ['value' => 'XXXL', 'slug' => 'xxxl', 'color_code' => null],
            ],
            
            // ===== Material =====
            'material' => [
                ['value' => 'Leather', 'slug' => 'leather', 'color_code' => null],
                ['value' => 'Fabric', 'slug' => 'fabric', 'color_code' => null],
                ['value' => 'Metal', 'slug' => 'metal', 'color_code' => null],
                ['value' => 'Plastic', 'slug' => 'plastic', 'color_code' => null],
                ['value' => 'Glass', 'slug' => 'glass', 'color_code' => null],
                ['value' => 'Wood', 'slug' => 'wood', 'color_code' => null],
                ['value' => 'Ceramic', 'slug' => 'ceramic', 'color_code' => null],
                ['value' => 'Aluminum', 'slug' => 'aluminum', 'color_code' => null],
                ['value' => 'Stainless Steel', 'slug' => 'stainless-steel', 'color_code' => null],
                ['value' => 'Cotton', 'slug' => 'cotton', 'color_code' => null],
                ['value' => 'Polyester', 'slug' => 'polyester', 'color_code' => null],
                ['value' => 'Wool', 'slug' => 'wool', 'color_code' => null],
                ['value' => 'Silk', 'slug' => 'silk', 'color_code' => null],
                ['value' => 'Denim', 'slug' => 'denim', 'color_code' => null],
            ],
            
            // ===== Weight =====
            'weight' => [
                ['value' => 'Under 100g', 'slug' => 'under-100g', 'color_code' => null],
                ['value' => '100-200g', 'slug' => '100-200g', 'color_code' => null],
                ['value' => '200-300g', 'slug' => '200-300g', 'color_code' => null],
                ['value' => '300-500g', 'slug' => '300-500g', 'color_code' => null],
                ['value' => '500-1000g', 'slug' => '500-1000g', 'color_code' => null],
                ['value' => '1-2kg', 'slug' => '1-2kg', 'color_code' => null],
                ['value' => '2-5kg', 'slug' => '2-5kg', 'color_code' => null],
                ['value' => '5kg+', 'slug' => '5kg-plus', 'color_code' => null],
            ],
            
            // ===== Year =====
            'year' => [
                ['value' => '2020', 'slug' => '2020', 'color_code' => null],
                ['value' => '2021', 'slug' => '2021', 'color_code' => null],
                ['value' => '2022', 'slug' => '2022', 'color_code' => null],
                ['value' => '2023', 'slug' => '2023', 'color_code' => null],
                ['value' => '2024', 'slug' => '2024', 'color_code' => null],
                ['value' => '2025', 'slug' => '2025', 'color_code' => null],
            ],
            
            // ===== Storage =====
            'storage' => [
                ['value' => '64GB', 'slug' => '64gb', 'color_code' => null],
                ['value' => '128GB', 'slug' => '128gb', 'color_code' => null],
                ['value' => '256GB', 'slug' => '256gb', 'color_code' => null],
                ['value' => '512GB', 'slug' => '512gb', 'color_code' => null],
                ['value' => '1TB', 'slug' => '1tb', 'color_code' => null],
                ['value' => '2TB', 'slug' => '2tb', 'color_code' => null],
            ],
            
            // ===== RAM =====
            'ram' => [
                ['value' => '4GB', 'slug' => '4gb-ram', 'color_code' => null],
                ['value' => '6GB', 'slug' => '6gb-ram', 'color_code' => null],
                ['value' => '8GB', 'slug' => '8gb-ram', 'color_code' => null],
                ['value' => '12GB', 'slug' => '12gb-ram', 'color_code' => null],
                ['value' => '16GB', 'slug' => '16gb-ram', 'color_code' => null],
                ['value' => '32GB', 'slug' => '32gb-ram', 'color_code' => null],
                ['value' => '64GB', 'slug' => '64gb-ram', 'color_code' => null],
            ],
            
            // ===== Processor =====
            'processor' => [
                ['value' => 'Intel Core i3', 'slug' => 'intel-core-i3', 'color_code' => null],
                ['value' => 'Intel Core i5', 'slug' => 'intel-core-i5', 'color_code' => null],
                ['value' => 'Intel Core i7', 'slug' => 'intel-core-i7', 'color_code' => null],
                ['value' => 'Intel Core i9', 'slug' => 'intel-core-i9', 'color_code' => null],
                ['value' => 'Apple M1', 'slug' => 'apple-m1', 'color_code' => null],
                ['value' => 'Apple M2', 'slug' => 'apple-m2', 'color_code' => null],
                ['value' => 'Apple M3', 'slug' => 'apple-m3', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 1', 'slug' => 'snapdragon-8-gen-1', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 2', 'slug' => 'snapdragon-8-gen-2', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 3', 'slug' => 'snapdragon-8-gen-3', 'color_code' => null],
                ['value' => 'AMD Ryzen 5', 'slug' => 'amd-ryzen-5', 'color_code' => null],
                ['value' => 'AMD Ryzen 7', 'slug' => 'amd-ryzen-7', 'color_code' => null],
                ['value' => 'AMD Ryzen 9', 'slug' => 'amd-ryzen-9', 'color_code' => null],
            ],
            
            // ===== Battery =====
            'battery' => [
                ['value' => '2000mAh', 'slug' => '2000mah', 'color_code' => null],
                ['value' => '3000mAh', 'slug' => '3000mah', 'color_code' => null],
                ['value' => '4000mAh', 'slug' => '4000mah', 'color_code' => null],
                ['value' => '5000mAh', 'slug' => '5000mah', 'color_code' => null],
                ['value' => '6000mAh', 'slug' => '6000mah', 'color_code' => null],
                ['value' => '8000mAh', 'slug' => '8000mah', 'color_code' => null],
                ['value' => '10000mAh', 'slug' => '10000mah', 'color_code' => null],
                ['value' => '20000mAh', 'slug' => '20000mah', 'color_code' => null],
            ],
            
            // ===== Display Size =====
            'display-size' => [
                ['value' => '5.5 inch', 'slug' => '5-5-inch', 'color_code' => null],
                ['value' => '6.1 inch', 'slug' => '6-1-inch', 'color_code' => null],
                ['value' => '6.5 inch', 'slug' => '6-5-inch', 'color_code' => null],
                ['value' => '6.7 inch', 'slug' => '6-7-inch', 'color_code' => null],
                ['value' => '7.0 inch', 'slug' => '7-0-inch', 'color_code' => null],
                ['value' => '7.6 inch', 'slug' => '7-6-inch', 'color_code' => null],
            ],
            
            // ===== Display Type =====
            'display-type' => [
                ['value' => 'AMOLED', 'slug' => 'amoled', 'color_code' => null],
                ['value' => 'OLED', 'slug' => 'oled', 'color_code' => null],
                ['value' => 'LCD', 'slug' => 'lcd', 'color_code' => null],
                ['value' => 'Retina', 'slug' => 'retina', 'color_code' => null],
                ['value' => 'Super Retina XDR', 'slug' => 'super-retina-xdr', 'color_code' => null],
                ['value' => 'IPS LCD', 'slug' => 'ips-lcd', 'color_code' => null],
                ['value' => 'QLED', 'slug' => 'qled', 'color_code' => null],
            ],
            
            // ===== Water Resistant =====
            'water-resistant' => [
                ['value' => 'Yes', 'slug' => 'yes-water', 'color_code' => null],
                ['value' => 'No', 'slug' => 'no-water', 'color_code' => null],
                ['value' => 'IP67', 'slug' => 'ip67', 'color_code' => null],
                ['value' => 'IP68', 'slug' => 'ip68', 'color_code' => null],
                ['value' => 'IPX4', 'slug' => 'ipx4', 'color_code' => null],
            ],
            
            // ===== Camera MP =====
            'camera-mp' => [
                ['value' => '12MP', 'slug' => '12mp', 'color_code' => null],
                ['value' => '48MP', 'slug' => '48mp', 'color_code' => null],
                ['value' => '50MP', 'slug' => '50mp', 'color_code' => null],
                ['value' => '64MP', 'slug' => '64mp', 'color_code' => null],
                ['value' => '108MP', 'slug' => '108mp', 'color_code' => null],
                ['value' => '200MP', 'slug' => '200mp', 'color_code' => null],
            ],
            
            // ===== SIM Type =====
            'sim-type' => [
                ['value' => 'Single SIM', 'slug' => 'single-sim', 'color_code' => null],
                ['value' => 'Dual SIM', 'slug' => 'dual-sim', 'color_code' => null],
                ['value' => 'eSIM', 'slug' => 'esim', 'color_code' => null],
                ['value' => 'Dual SIM + eSIM', 'slug' => 'dual-sim-esim', 'color_code' => null],
            ],
            
            // ===== 5G Support =====
            '5g-support' => [
                ['value' => 'Yes', 'slug' => 'yes-5g', 'color_code' => null],
                ['value' => 'No', 'slug' => 'no-5g', 'color_code' => null],
            ],
            
            // ===== Screen Size =====
            'screen-size' => [
                ['value' => '13.3 inch', 'slug' => '133-inch', 'color_code' => null],
                ['value' => '14 inch', 'slug' => '14-inch', 'color_code' => null],
                ['value' => '15.6 inch', 'slug' => '156-inch', 'color_code' => null],
                ['value' => '16 inch', 'slug' => '16-inch', 'color_code' => null],
                ['value' => '17.3 inch', 'slug' => '173-inch', 'color_code' => null],
                ['value' => '18.4 inch', 'slug' => '184-inch', 'color_code' => null],
            ],
            
            // ===== Graphics Card =====
            'graphics-card' => [
                ['value' => 'Integrated', 'slug' => 'integrated', 'color_code' => null],
                ['value' => 'NVIDIA RTX 3050', 'slug' => 'nvidia-rtx-3050', 'color_code' => null],
                ['value' => 'NVIDIA RTX 3060', 'slug' => 'nvidia-rtx-3060', 'color_code' => null],
                ['value' => 'NVIDIA RTX 3070', 'slug' => 'nvidia-rtx-3070', 'color_code' => null],
                ['value' => 'NVIDIA RTX 3080', 'slug' => 'nvidia-rtx-3080', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4070', 'slug' => 'nvidia-rtx-4070', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4080', 'slug' => 'nvidia-rtx-4080', 'color_code' => null],
                ['value' => 'NVIDIA RTX 4090', 'slug' => 'nvidia-rtx-4090', 'color_code' => null],
                ['value' => 'AMD Radeon', 'slug' => 'amd-radeon', 'color_code' => null],
                ['value' => 'AMD Radeon RX 7600', 'slug' => 'amd-radeon-rx-7600', 'color_code' => null],
                ['value' => 'AMD Radeon RX 7900', 'slug' => 'amd-radeon-rx-7900', 'color_code' => null],
            ],
            
            // ===== Operating System =====
            'operating-system' => [
                ['value' => 'Windows 11', 'slug' => 'windows-11', 'color_code' => null],
                ['value' => 'Windows 10', 'slug' => 'windows-10', 'color_code' => null],
                ['value' => 'macOS', 'slug' => 'macos', 'color_code' => null],
                ['value' => 'Linux', 'slug' => 'linux', 'color_code' => null],
                ['value' => 'Chrome OS', 'slug' => 'chrome-os', 'color_code' => null],
                ['value' => 'Android', 'slug' => 'android', 'color_code' => null],
                ['value' => 'iOS', 'slug' => 'ios', 'color_code' => null],
            ],
            
            // ===== SSD Storage =====
            'ssd-storage' => [
                ['value' => '128GB', 'slug' => '128gb-ssd', 'color_code' => null],
                ['value' => '256GB', 'slug' => '256gb-ssd', 'color_code' => null],
                ['value' => '512GB', 'slug' => '512gb-ssd', 'color_code' => null],
                ['value' => '1TB', 'slug' => '1tb-ssd', 'color_code' => null],
                ['value' => '2TB', 'slug' => '2tb-ssd', 'color_code' => null],
                ['value' => '4TB', 'slug' => '4tb-ssd', 'color_code' => null],
            ],
            
            // ===== CPU Model =====
            'cpu-model' => [
                ['value' => 'Intel Core i3', 'slug' => 'intel-core-i3-cpu', 'color_code' => null],
                ['value' => 'Intel Core i5', 'slug' => 'intel-core-i5-cpu', 'color_code' => null],
                ['value' => 'Intel Core i7', 'slug' => 'intel-core-i7-cpu', 'color_code' => null],
                ['value' => 'Intel Core i9', 'slug' => 'intel-core-i9-cpu', 'color_code' => null],
                ['value' => 'Apple M1', 'slug' => 'apple-m1-cpu', 'color_code' => null],
                ['value' => 'Apple M2', 'slug' => 'apple-m2-cpu', 'color_code' => null],
                ['value' => 'Apple M3', 'slug' => 'apple-m3-cpu', 'color_code' => null],
                ['value' => 'AMD Ryzen 5', 'slug' => 'amd-ryzen-5-cpu', 'color_code' => null],
                ['value' => 'AMD Ryzen 7', 'slug' => 'amd-ryzen-7-cpu', 'color_code' => null],
                ['value' => 'AMD Ryzen 9', 'slug' => 'amd-ryzen-9-cpu', 'color_code' => null],
            ],
            
            // ===== Refresh Rate =====
            'refresh-rate' => [
                ['value' => '60Hz', 'slug' => '60hz', 'color_code' => null],
                ['value' => '90Hz', 'slug' => '90hz', 'color_code' => null],
                ['value' => '120Hz', 'slug' => '120hz', 'color_code' => null],
                ['value' => '144Hz', 'slug' => '144hz', 'color_code' => null],
                ['value' => '165Hz', 'slug' => '165hz', 'color_code' => null],
                ['value' => '240Hz', 'slug' => '240hz', 'color_code' => null],
            ],
            
            // ===== Connectivity =====
            'connectivity' => [
                ['value' => 'Bluetooth 5.0', 'slug' => 'bluetooth-5-0', 'color_code' => null],
                ['value' => 'Bluetooth 5.1', 'slug' => 'bluetooth-5-1', 'color_code' => null],
                ['value' => 'Bluetooth 5.2', 'slug' => 'bluetooth-5-2', 'color_code' => null],
                ['value' => 'Bluetooth 5.3', 'slug' => 'bluetooth-5-3', 'color_code' => null],
                ['value' => 'WiFi 5', 'slug' => 'wifi-5', 'color_code' => null],
                ['value' => 'WiFi 6', 'slug' => 'wifi-6', 'color_code' => null],
                ['value' => 'WiFi 6E', 'slug' => 'wifi-6e', 'color_code' => null],
                ['value' => 'WiFi 7', 'slug' => 'wifi-7', 'color_code' => null],
                ['value' => 'USB-C', 'slug' => 'usb-c', 'color_code' => null],
                ['value' => 'Lightning', 'slug' => 'lightning', 'color_code' => null],
                ['value' => 'HDMI', 'slug' => 'hdmi', 'color_code' => null],
                ['value' => 'DisplayPort', 'slug' => 'displayport', 'color_code' => null],
            ],
            
            // ===== Battery Life =====
            'battery-life' => [
                ['value' => 'Up to 5 hours', 'slug' => 'up-to-5-hours', 'color_code' => null],
                ['value' => 'Up to 10 hours', 'slug' => 'up-to-10-hours', 'color_code' => null],
                ['value' => 'Up to 15 hours', 'slug' => 'up-to-15-hours', 'color_code' => null],
                ['value' => 'Up to 20 hours', 'slug' => 'up-to-20-hours', 'color_code' => null],
                ['value' => 'Up to 30 hours', 'slug' => 'up-to-30-hours', 'color_code' => null],
                ['value' => 'Up to 50 hours', 'slug' => 'up-to-50-hours', 'color_code' => null],
            ],
            
            // ===== Noise Cancellation =====
            'noise-cancellation' => [
                ['value' => 'Yes', 'slug' => 'yes-nc', 'color_code' => null],
                ['value' => 'No', 'slug' => 'no-nc', 'color_code' => null],
                ['value' => 'Active ANC', 'slug' => 'active-anc', 'color_code' => null],
                ['value' => 'Passive', 'slug' => 'passive', 'color_code' => null],
                ['value' => 'Hybrid ANC', 'slug' => 'hybrid-anc', 'color_code' => null],
            ],
            
            // ===== Waterproof =====
            'waterproof' => [
                ['value' => 'Yes', 'slug' => 'yes-wp', 'color_code' => null],
                ['value' => 'No', 'slug' => 'no-wp', 'color_code' => null],
                ['value' => 'IPX4', 'slug' => 'ipx4-wp', 'color_code' => null],
                ['value' => 'IPX5', 'slug' => 'ipx5-wp', 'color_code' => null],
                ['value' => 'IPX7', 'slug' => 'ipx7-wp', 'color_code' => null],
                ['value' => 'IPX8', 'slug' => 'ipx8-wp', 'color_code' => null],
            ],
            
            // ===== Bluetooth Version =====
            'bluetooth-version' => [
                ['value' => '4.0', 'slug' => '4-0', 'color_code' => null],
                ['value' => '4.1', 'slug' => '4-1', 'color_code' => null],
                ['value' => '4.2', 'slug' => '4-2', 'color_code' => null],
                ['value' => '5.0', 'slug' => '5-0-bt', 'color_code' => null],
                ['value' => '5.1', 'slug' => '5-1-bt', 'color_code' => null],
                ['value' => '5.2', 'slug' => '5-2-bt', 'color_code' => null],
                ['value' => '5.3', 'slug' => '5-3-bt', 'color_code' => null],
            ],
            
            // ===== Clothing Size =====
            'clothing-size' => [
                ['value' => 'S', 'slug' => 's-clothing', 'color_code' => null],
                ['value' => 'M', 'slug' => 'm-clothing', 'color_code' => null],
                ['value' => 'L', 'slug' => 'l-clothing', 'color_code' => null],
                ['value' => 'XL', 'slug' => 'xl-clothing', 'color_code' => null],
                ['value' => 'XXL', 'slug' => 'xxl-clothing', 'color_code' => null],
                ['value' => 'XXXL', 'slug' => 'xxxl-clothing', 'color_code' => null],
                ['value' => '40', 'slug' => '40-clothing', 'color_code' => null],
                ['value' => '42', 'slug' => '42-clothing', 'color_code' => null],
                ['value' => '44', 'slug' => '44-clothing', 'color_code' => null],
                ['value' => '46', 'slug' => '46-clothing', 'color_code' => null],
                ['value' => '48', 'slug' => '48-clothing', 'color_code' => null],
                ['value' => '50', 'slug' => '50-clothing', 'color_code' => null],
            ],
            
            // ===== Fabric Type =====
            'fabric-type' => [
                ['value' => 'Cotton', 'slug' => 'cotton-fabric', 'color_code' => null],
                ['value' => 'Polyester', 'slug' => 'polyester-fabric', 'color_code' => null],
                ['value' => 'Wool', 'slug' => 'wool-fabric', 'color_code' => null],
                ['value' => 'Silk', 'slug' => 'silk-fabric', 'color_code' => null],
                ['value' => 'Linen', 'slug' => 'linen', 'color_code' => null],
                ['value' => 'Denim', 'slug' => 'denim-fabric', 'color_code' => null],
                ['value' => 'Leather', 'slug' => 'leather-fabric', 'color_code' => null],
                ['value' => 'Nylon', 'slug' => 'nylon', 'color_code' => null],
                ['value' => 'Spandex', 'slug' => 'spandex', 'color_code' => null],
            ],
            
            // ===== Season =====
            'season' => [
                ['value' => 'Spring', 'slug' => 'spring', 'color_code' => null],
                ['value' => 'Summer', 'slug' => 'summer', 'color_code' => null],
                ['value' => 'Fall', 'slug' => 'fall', 'color_code' => null],
                ['value' => 'Winter', 'slug' => 'winter', 'color_code' => null],
                ['value' => 'All Seasons', 'slug' => 'all-seasons', 'color_code' => null],
            ],
            
            // ===== Style =====
            'style' => [
                ['value' => 'Classic', 'slug' => 'classic-style', 'color_code' => null],
                ['value' => 'Modern', 'slug' => 'modern-style', 'color_code' => null],
                ['value' => 'Sport', 'slug' => 'sport-style', 'color_code' => null],
                ['value' => 'Casual', 'slug' => 'casual-style', 'color_code' => null],
                ['value' => 'Formal', 'slug' => 'formal-style', 'color_code' => null],
                ['value' => 'Vintage', 'slug' => 'vintage-style', 'color_code' => null],
                ['value' => 'Minimal', 'slug' => 'minimal-style', 'color_code' => null],
                ['value' => 'Luxury', 'slug' => 'luxury-style', 'color_code' => null],
            ],
            
            // ===== Gender =====
            'gender' => [
                ['value' => 'Men', 'slug' => 'men', 'color_code' => null],
                ['value' => 'Women', 'slug' => 'women', 'color_code' => null],
                ['value' => 'Unisex', 'slug' => 'unisex', 'color_code' => null],
                ['value' => 'Boys', 'slug' => 'boys', 'color_code' => null],
                ['value' => 'Girls', 'slug' => 'girls', 'color_code' => null],
            ],
            
            // ===== Gold Karat =====
            'gold-karat' => [
                ['value' => '18K', 'slug' => '18k', 'color_code' => null],
                ['value' => '21K', 'slug' => '21k', 'color_code' => null],
                ['value' => '22K', 'slug' => '22k', 'color_code' => null],
                ['value' => '24K', 'slug' => '24k', 'color_code' => null],
                ['value' => '925', 'slug' => '925-silver', 'color_code' => null],
            ],
            
            // ===== Metal Type =====
            'metal-type' => [
                ['value' => 'Gold', 'slug' => 'gold-metal', 'color_code' => null],
                ['value' => 'Silver', 'slug' => 'silver-metal', 'color_code' => null],
                ['value' => 'Platinum', 'slug' => 'platinum', 'color_code' => null],
                ['value' => 'Rose Gold', 'slug' => 'rose-gold-metal', 'color_code' => null],
                ['value' => 'White Gold', 'slug' => 'white-gold', 'color_code' => null],
                ['value' => 'Titanium', 'slug' => 'titanium', 'color_code' => null],
            ],
            
            // ===== Gemstone =====
            'gemstone' => [
                ['value' => 'Diamond', 'slug' => 'diamond', 'color_code' => null],
                ['value' => 'Ruby', 'slug' => 'ruby', 'color_code' => null],
                ['value' => 'Sapphire', 'slug' => 'sapphire', 'color_code' => null],
                ['value' => 'Emerald', 'slug' => 'emerald', 'color_code' => null],
                ['value' => 'Opal', 'slug' => 'opal', 'color_code' => null],
                ['value' => 'Amethyst', 'slug' => 'amethyst', 'color_code' => null],
                ['value' => 'Pearl', 'slug' => 'pearl', 'color_code' => null],
                ['value' => 'Topaz', 'slug' => 'topaz', 'color_code' => null],
                ['value' => 'Quartz', 'slug' => 'quartz', 'color_code' => null],
            ],
            
            // ===== Watch Movement =====
            'watch-movement' => [
                ['value' => 'Automatic', 'slug' => 'automatic', 'color_code' => null],
                ['value' => 'Quartz', 'slug' => 'quartz-watch', 'color_code' => null],
                ['value' => 'Mechanical', 'slug' => 'mechanical', 'color_code' => null],
                ['value' => 'Solar', 'slug' => 'solar', 'color_code' => null],
                ['value' => 'Smart', 'slug' => 'smart-movement', 'color_code' => null],
            ],
            
            // ===== Engine Type =====
            'engine-type' => [
                ['value' => 'Petrol', 'slug' => 'petrol', 'color_code' => null],
                ['value' => 'Diesel', 'slug' => 'diesel', 'color_code' => null],
                ['value' => 'Electric', 'slug' => 'electric', 'color_code' => null],
                ['value' => 'Hybrid', 'slug' => 'hybrid', 'color_code' => null],
                ['value' => 'Plug-in Hybrid', 'slug' => 'plug-in-hybrid', 'color_code' => null],
            ],
            
            // ===== Transmission =====
            'transmission' => [
                ['value' => 'Manual', 'slug' => 'manual', 'color_code' => null],
                ['value' => 'Automatic', 'slug' => 'automatic-trans', 'color_code' => null],
                ['value' => 'CVT', 'slug' => 'cvt', 'color_code' => null],
                ['value' => 'Dual Clutch', 'slug' => 'dual-clutch', 'color_code' => null],
            ],
            
            // ===== Fuel Type =====
            'fuel-type' => [
                ['value' => 'Petrol', 'slug' => 'petrol-fuel', 'color_code' => null],
                ['value' => 'Diesel', 'slug' => 'diesel-fuel', 'color_code' => null],
                ['value' => 'Electric', 'slug' => 'electric-fuel', 'color_code' => null],
                ['value' => 'Hybrid', 'slug' => 'hybrid-fuel', 'color_code' => null],
                ['value' => 'CNG', 'slug' => 'cng', 'color_code' => null],
                ['value' => 'LPG', 'slug' => 'lpg', 'color_code' => null],
            ],
            
            // ===== Car Model Year =====
            'car-model-year' => [
                ['value' => '2018', 'slug' => '2018-car', 'color_code' => null],
                ['value' => '2019', 'slug' => '2019-car', 'color_code' => null],
                ['value' => '2020', 'slug' => '2020-car', 'color_code' => null],
                ['value' => '2021', 'slug' => '2021-car', 'color_code' => null],
                ['value' => '2022', 'slug' => '2022-car', 'color_code' => null],
                ['value' => '2023', 'slug' => '2023-car', 'color_code' => null],
                ['value' => '2024', 'slug' => '2024-car', 'color_code' => null],
                ['value' => '2025', 'slug' => '2025-car', 'color_code' => null],
            ],
            
            // ===== Mileage =====
            'mileage' => [
                ['value' => 'Under 10,000 km', 'slug' => 'under-10000-km', 'color_code' => null],
                ['value' => '10,000-30,000 km', 'slug' => '10000-30000-km', 'color_code' => null],
                ['value' => '30,000-50,000 km', 'slug' => '30000-50000-km', 'color_code' => null],
                ['value' => '50,000-100,000 km', 'slug' => '50000-100000-km', 'color_code' => null],
                ['value' => '100,000+ km', 'slug' => '100000-plus-km', 'color_code' => null],
            ],
            
            // ===== Tool Type =====
            'tool-type' => [
                ['value' => 'Drill', 'slug' => 'drill', 'color_code' => null],
                ['value' => 'Grinder', 'slug' => 'grinder', 'color_code' => null],
                ['value' => 'Saw', 'slug' => 'saw', 'color_code' => null],
                ['value' => 'Screwdriver', 'slug' => 'screwdriver', 'color_code' => null],
                ['value' => 'Wrench', 'slug' => 'wrench', 'color_code' => null],
                ['value' => 'Hammer', 'slug' => 'hammer', 'color_code' => null],
                ['value' => 'Sander', 'slug' => 'sander', 'color_code' => null],
                ['value' => 'Router', 'slug' => 'router-tool', 'color_code' => null],
            ],
            
            // ===== Power Source =====
            'power-source' => [
                ['value' => 'Battery', 'slug' => 'battery-ps', 'color_code' => null],
                ['value' => 'Electric', 'slug' => 'electric-ps', 'color_code' => null],
                ['value' => 'Manual', 'slug' => 'manual-ps', 'color_code' => null],
                ['value' => 'Pneumatic', 'slug' => 'pneumatic', 'color_code' => null],
                ['value' => 'Hydraulic', 'slug' => 'hydraulic', 'color_code' => null],
                ['value' => 'Gas', 'slug' => 'gas', 'color_code' => null],
            ],
            
            // ===== Voltage =====
            'voltage' => [
                ['value' => '12V', 'slug' => '12v', 'color_code' => null],
                ['value' => '18V', 'slug' => '18v', 'color_code' => null],
                ['value' => '20V', 'slug' => '20v', 'color_code' => null],
                ['value' => '24V', 'slug' => '24v', 'color_code' => null],
                ['value' => '110V', 'slug' => '110v', 'color_code' => null],
                ['value' => '220V', 'slug' => '220v', 'color_code' => null],
                ['value' => '240V', 'slug' => '240v', 'color_code' => null],
            ],
            
            // ===== Power Rating =====
            'power-rating' => [
                ['value' => '100W', 'slug' => '100w', 'color_code' => null],
                ['value' => '200W', 'slug' => '200w', 'color_code' => null],
                ['value' => '300W', 'slug' => '300w', 'color_code' => null],
                ['value' => '500W', 'slug' => '500w', 'color_code' => null],
                ['value' => '1000W', 'slug' => '1000w', 'color_code' => null],
                ['value' => '1500W', 'slug' => '1500w', 'color_code' => null],
                ['value' => '2000W', 'slug' => '2000w', 'color_code' => null],
            ],
            
            // ===== Author =====
            'author' => [
                ['value' => 'George Orwell', 'slug' => 'george-orwell', 'color_code' => null],
                ['value' => 'Franz Kafka', 'slug' => 'franz-kafka', 'color_code' => null],
                ['value' => 'Sadegh Hedayat', 'slug' => 'sadegh-hedayat', 'color_code' => null],
                ['value' => 'Jalal Al-Ahmad', 'slug' => 'jalal-al-ahmad', 'color_code' => null],
                ['value' => 'Forough Farrokhzad', 'slug' => 'forough-farrokhzad', 'color_code' => null],
                ['value' => 'Simin Behbahani', 'slug' => 'simin-behbahani', 'color_code' => null],
                ['value' => 'Erich Maria Remarque', 'slug' => 'erich-maria-remarque', 'color_code' => null],
                ['value' => 'Gabriel Garcia Marquez', 'slug' => 'gabriel-garcia-marquez', 'color_code' => null],
                ['value' => 'Paulo Coelho', 'slug' => 'paulo-coelho', 'color_code' => null],
                ['value' => 'Haruki Murakami', 'slug' => 'haruki-murakami', 'color_code' => null],
            ],
            
            // ===== Publisher =====
            'publisher' => [
                ['value' => 'Penguin Books', 'slug' => 'penguin-books', 'color_code' => null],
                ['value' => 'Oxford Press', 'slug' => 'oxford-press', 'color_code' => null],
                ['value' => 'HarperCollins', 'slug' => 'harpercollins', 'color_code' => null],
                ['value' => 'Simon & Schuster', 'slug' => 'simon-schuster', 'color_code' => null],
                ['value' => 'Random House', 'slug' => 'random-house', 'color_code' => null],
                ['value' => 'Hachette', 'slug' => 'hachette', 'color_code' => null],
                ['value' => 'Macmillan', 'slug' => 'macmillan', 'color_code' => null],
            ],
            
            // ===== Book Language =====
            'book-language' => [
                ['value' => 'Persian', 'slug' => 'persian', 'color_code' => null],
                ['value' => 'English', 'slug' => 'english', 'color_code' => null],
                ['value' => 'Arabic', 'slug' => 'arabic', 'color_code' => null],
                ['value' => 'French', 'slug' => 'french', 'color_code' => null],
                ['value' => 'German', 'slug' => 'german', 'color_code' => null],
                ['value' => 'Spanish', 'slug' => 'spanish', 'color_code' => null],
                ['value' => 'Russian', 'slug' => 'russian', 'color_code' => null],
                ['value' => 'Turkish', 'slug' => 'turkish', 'color_code' => null],
            ],
            
            // ===== Cover Type =====
            'cover-type' => [
                ['value' => 'Hardcover', 'slug' => 'hardcover', 'color_code' => null],
                ['value' => 'Paperback', 'slug' => 'paperback', 'color_code' => null],
                ['value' => 'Leather', 'slug' => 'leather-cover', 'color_code' => null],
                ['value' => 'E-book', 'slug' => 'e-book', 'color_code' => null],
                ['value' => 'Audiobook', 'slug' => 'audiobook', 'color_code' => null],
            ],
            
            // ===== Pages Count =====
            'pages-count' => [
                ['value' => 'Under 100', 'slug' => 'under-100', 'color_code' => null],
                ['value' => '100-200', 'slug' => '100-200', 'color_code' => null],
                ['value' => '200-300', 'slug' => '200-300', 'color_code' => null],
                ['value' => '300-400', 'slug' => '300-400', 'color_code' => null],
                ['value' => '400-500', 'slug' => '400-500', 'color_code' => null],
                ['value' => '500+', 'slug' => '500-plus', 'color_code' => null],
            ],
            
            // ===== Book Genre =====
            'book-genre' => [
                ['value' => 'Fiction', 'slug' => 'fiction', 'color_code' => null],
                ['value' => 'Non-Fiction', 'slug' => 'non-fiction', 'color_code' => null],
                ['value' => 'Science Fiction', 'slug' => 'science-fiction', 'color_code' => null],
                ['value' => 'Fantasy', 'slug' => 'fantasy', 'color_code' => null],
                ['value' => 'Mystery', 'slug' => 'mystery', 'color_code' => null],
                ['value' => 'Romance', 'slug' => 'romance', 'color_code' => null],
                ['value' => 'Thriller', 'slug' => 'thriller', 'color_code' => null],
                ['value' => 'Horror', 'slug' => 'horror', 'color_code' => null],
                ['value' => 'Biography', 'slug' => 'biography', 'color_code' => null],
                ['value' => 'History', 'slug' => 'history', 'color_code' => null],
                ['value' => 'Science', 'slug' => 'science', 'color_code' => null],
                ['value' => 'Poetry', 'slug' => 'poetry', 'color_code' => null],
            ],
            
            // ===== Sport Type =====
            'sport-type' => [
                ['value' => 'Football', 'slug' => 'football', 'color_code' => null],
                ['value' => 'Basketball', 'slug' => 'basketball', 'color_code' => null],
                ['value' => 'Tennis', 'slug' => 'tennis', 'color_code' => null],
                ['value' => 'Swimming', 'slug' => 'swimming', 'color_code' => null],
                ['value' => 'Cycling', 'slug' => 'cycling', 'color_code' => null],
                ['value' => 'Running', 'slug' => 'running', 'color_code' => null],
                ['value' => 'Gym', 'slug' => 'gym', 'color_code' => null],
                ['value' => 'Yoga', 'slug' => 'yoga', 'color_code' => null],
                ['value' => 'Boxing', 'slug' => 'boxing', 'color_code' => null],
                ['value' => 'Camping', 'slug' => 'camping', 'color_code' => null],
                ['value' => 'Hiking', 'slug' => 'hiking', 'color_code' => null],
                ['value' => 'Skiing', 'slug' => 'skiing', 'color_code' => null],
                ['value' => 'Fishing', 'slug' => 'fishing', 'color_code' => null],
            ],
            
            // ===== Shoe Size =====
            'shoe-size' => [
                ['value' => '36', 'slug' => '36-shoe', 'color_code' => null],
                ['value' => '37', 'slug' => '37-shoe', 'color_code' => null],
                ['value' => '38', 'slug' => '38-shoe', 'color_code' => null],
                ['value' => '39', 'slug' => '39-shoe', 'color_code' => null],
                ['value' => '40', 'slug' => '40-shoe', 'color_code' => null],
                ['value' => '41', 'slug' => '41-shoe', 'color_code' => null],
                ['value' => '42', 'slug' => '42-shoe', 'color_code' => null],
                ['value' => '43', 'slug' => '43-shoe', 'color_code' => null],
                ['value' => '44', 'slug' => '44-shoe', 'color_code' => null],
                ['value' => '45', 'slug' => '45-shoe', 'color_code' => null],
                ['value' => '46', 'slug' => '46-shoe', 'color_code' => null],
                ['value' => '47', 'slug' => '47-shoe', 'color_code' => null],
                ['value' => '48', 'slug' => '48-shoe', 'color_code' => null],
            ],
            
            // ===== Sport Level =====
            'sport-level' => [
                ['value' => 'Beginner', 'slug' => 'beginner', 'color_code' => null],
                ['value' => 'Intermediate', 'slug' => 'intermediate', 'color_code' => null],
                ['value' => 'Advanced', 'slug' => 'advanced', 'color_code' => null],
                ['value' => 'Professional', 'slug' => 'professional', 'color_code' => null],
            ],
            
            // ===== Weather Resistance =====
            'weather-resistance' => [
                ['value' => 'Yes', 'slug' => 'yes-weather', 'color_code' => null],
                ['value' => 'No', 'slug' => 'no-weather', 'color_code' => null],
                ['value' => 'Water Resistant', 'slug' => 'water-resistant-weather', 'color_code' => null],
                ['value' => 'Wind Resistant', 'slug' => 'wind-resistant', 'color_code' => null],
                ['value' => 'All Weather', 'slug' => 'all-weather', 'color_code' => null],
            ],
        ];

        foreach ($attributeValues as $attrSlug => $values) {
            $attrId = $attributeIds[$attrSlug] ?? null;
            if (!$attrId) continue;

            $sortOrder = 1;
            foreach ($values as $item) {
                DB::table('attribute_values')->insert([
                    'attribute_id' => $attrId,
                    'value' => $item['value'],
                    'slug' => $item['slug'],
                    'color_code' => $item['color_code'],
                    'image' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sortOrder++;
            }
        }

        // ================================================================
        // 3. LINK ATTRIBUTES TO CATEGORIES
        // ================================================================
        $categories = DB::table('categories')->get();
        $allAttributes = DB::table('attributes')->get();

        $categoryAttributeMap = [
            // ===== Mobile =====
            'Mobile' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', 'sim-type', '5g-support', 'year', 'weight'],
            'Select Mobile' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', '5g-support'],
            'Apple Phones' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support'],
            'Samsung Phones' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support'],
            'Xiaomi Phones' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support'],
            'Google Pixel' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', 'camera-mp', '5g-support'],
            'OnePlus' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'water-resistant', '5g-support'],
            'Huawei' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type', 'camera-mp'],
            'Nokia' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size'],
            'Sony Xperia' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'water-resistant', 'camera-mp'],
            'Motorola' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size'],
            'Other Brands' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size'],
            'By Price' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size'],
            'By Performance' => ['color', 'storage', 'ram', 'processor', 'battery', '5g-support'],
            'By Storage' => ['storage', 'ram'],
            'Mobile Accessories' => ['color', 'material', 'weight'],
            'Trending' => ['color', 'storage', 'ram', 'processor', 'battery', 'display-size', 'display-type'],
            
            // ===== Laptops =====
            'Laptops' => ['color', 'material', 'weight', 'year', 'screen-size', 'graphics-card', 'operating-system', 'ssd-storage', 'cpu-model', 'refresh-rate'],
            'Select Laptop' => ['color', 'material', 'weight', 'screen-size', 'graphics-card', 'operating-system', 'ssd-storage', 'cpu-model'],
            'Apple MacBooks' => ['color', 'material', 'weight', 'screen-size', 'operating-system', 'ssd-storage', 'cpu-model'],
            'ASUS Laptops' => ['color', 'material', 'weight', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model'],
            'Lenovo Laptops' => ['color', 'material', 'weight', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model'],
            'Gaming Laptops' => ['color', 'material', 'weight', 'screen-size', 'graphics-card', 'ssd-storage', 'cpu-model', 'refresh-rate'],
            'Business Laptops' => ['color', 'material', 'weight', 'screen-size', 'operating-system', 'ssd-storage', 'cpu-model'],
            'Student Laptops' => ['color', 'material', 'weight', 'screen-size', 'ssd-storage', 'cpu-model'],
            'By Processor' => ['cpu-model', 'processor'],
            'By RAM' => ['ram', 'ssd-storage'],
            'Laptop Accessories' => ['color', 'material', 'weight'],
            
            // ===== Digital Products =====
            'Digital Products' => ['color', 'material', 'weight', 'year', 'connectivity', 'battery-life', 'bluetooth-version', 'waterproof'],
            'Gaming Consoles' => ['color', 'material', 'weight', 'storage', 'ram', 'connectivity'],
            'Gaming Accessories' => ['color', 'material', 'weight', 'connectivity', 'bluetooth-version'],
            'Gaming Games' => ['year', 'genre'],
            'Headphones' => ['color', 'material', 'weight', 'connectivity', 'battery-life', 'noise-cancellation', 'bluetooth-version'],
            'Smartwatches' => ['color', 'material', 'weight', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version'],
            'Tablets' => ['color', 'material', 'weight', 'screen-size', 'storage', 'ram', 'operating-system'],
            'Speakers' => ['color', 'material', 'weight', 'connectivity', 'battery-life', 'waterproof', 'bluetooth-version'],
            'Cameras' => ['color', 'material', 'weight', 'connectivity', 'battery-life', 'camera-mp'],
            'Power Banks' => ['color', 'material', 'weight', 'capacity', 'battery', 'connectivity'],
            'Computer Components' => ['material', 'weight', 'storage', 'ram', 'processor', 'power-usage'],
            'Smart Home' => ['color', 'material', 'weight', 'connectivity', 'battery-life', 'waterproof'],
            'Printers' => ['color', 'material', 'weight', 'connectivity', 'power-usage'],
            'Storage Devices' => ['material', 'weight', 'storage', 'connectivity'],
            'Networking' => ['material', 'weight', 'connectivity'],
            'Top Brands' => ['year', 'country'],
            'Trending' => ['color', 'material', 'weight', 'year', 'connectivity'],
            
            // ===== Home & Kitchen =====
            'Home & Kitchen' => ['color', 'material', 'weight', 'year', 'material-type', 'capacity', 'power-usage'],
            'Cookware' => ['color', 'material', 'weight', 'capacity', 'material-type'],
            'Tea & Coffee' => ['color', 'material', 'weight', 'capacity', 'power-usage'],
            'Kitchenware' => ['color', 'material', 'weight', 'capacity', 'material-type'],
            'Dining & Serving' => ['color', 'material', 'weight', 'capacity', 'material-type'],
            'Furniture' => ['color', 'material', 'weight', 'material-type', 'size'],
            'Lighting' => ['color', 'material', 'weight', 'power-usage', 'material-type'],
            'Carpets & Rugs' => ['color', 'material', 'weight', 'material-type', 'size'],
            'Home Decor' => ['color', 'material', 'weight', 'material-type'],
            'Bedroom' => ['color', 'material', 'weight', 'material-type', 'size'],
            'Bathroom' => ['color', 'material', 'weight', 'material-type'],
            'Cleaning' => ['color', 'material', 'weight', 'power-usage'],
            
            // ===== Home Appliances =====
            'Home Appliances' => ['color', 'material', 'weight', 'year', 'power-usage', 'capacity'],
            'Refrigerators' => ['color', 'material', 'weight', 'capacity', 'power-usage'],
            'Washing Machines' => ['color', 'material', 'weight', 'capacity', 'power-usage'],
            'Dishwashers' => ['color', 'material', 'weight', 'capacity', 'power-usage'],
            'Vacuums' => ['color', 'material', 'weight', 'power-usage', 'battery'],
            'TVs' => ['color', 'material', 'weight', 'screen-size', 'display-type', 'refresh-rate'],
            'Cooking Appliances' => ['color', 'material', 'weight', 'power-usage', 'capacity'],
            'Beverage Makers' => ['color', 'material', 'weight', 'power-usage', 'capacity'],
            'Air Conditioners' => ['color', 'material', 'weight', 'power-usage', 'capacity'],
            'Heaters & Fans' => ['color', 'material', 'weight', 'power-usage'],
            'Audio & Video' => ['color', 'material', 'weight', 'connectivity', 'power-usage'],
            'Sewing Machines' => ['color', 'material', 'weight', 'power-usage'],
            'Water Purifiers' => ['color', 'material', 'weight', 'capacity'],
            
            // ===== Beauty & Health =====
            'Beauty & Health' => ['color', 'weight', 'year'],
            'Skin Care' => ['weight', 'year', 'skin-type'],
            'Makeup' => ['color', 'weight', 'shade'],
            'Hair Care' => ['weight', 'hair-type'],
            'Perfumes' => ['color', 'weight', 'fragrance-type'],
            'Oral Care' => ['color', 'weight'],
            'Personal Care' => ['color', 'weight', 'gender'],
            'Health Supplements' => ['weight', 'year', 'dosage'],
            
            // ===== Fashion =====
            'Fashion' => ['color', 'clothing-size', 'fabric-type', 'season', 'style', 'gender'],
            'Mens Clothing' => ['color', 'clothing-size', 'fabric-type', 'season', 'style'],
            'Womens Clothing' => ['color', 'clothing-size', 'fabric-type', 'season', 'style'],
            'Childrens Clothing' => ['color', 'clothing-size', 'fabric-type', 'season'],
            'Shoes' => ['color', 'shoe-size', 'material', 'gender', 'sport-type'],
            'Bags & Accessories' => ['color', 'material', 'gender', 'style'],
            'Sportswear' => ['color', 'clothing-size', 'fabric-type', 'gender', 'sport-type'],
            'Fashion Brands' => ['year', 'country'],
            'Trending Fashion' => ['color', 'clothing-size', 'fabric-type', 'season', 'style'],
            
            // ===== Gold & Jewelry =====
            'Gold & Jewelry' => ['gold-karat', 'metal-type', 'gemstone', 'weight', 'year'],
            'Gold Jewelry' => ['gold-karat', 'metal-type', 'weight'],
            'Silver Jewelry' => ['gold-karat', 'metal-type', 'weight'],
            'Diamonds & Gems' => ['gold-karat', 'metal-type', 'gemstone', 'weight'],
            'Watches' => ['watch-movement', 'metal-type', 'water-resistant', 'weight', 'year'],
            'Gold Coins & Bars' => ['gold-karat', 'weight', 'year'],
            'Gold Galleries' => ['year', 'brand'],
            
            // ===== Vehicles =====
            'Vehicles' => ['engine-type', 'transmission', 'fuel-type', 'car-model-year', 'color', 'weight', 'mileage'],
            'Cars' => ['engine-type', 'transmission', 'fuel-type', 'car-model-year', 'color', 'mileage'],
            'Motorcycles' => ['engine-type', 'transmission', 'fuel-type', 'car-model-year', 'color', 'mileage'],
            'Car Accessories' => ['color', 'material', 'weight', 'brand'],
            'Motorcycle Accessories' => ['color', 'material', 'weight'],
            'Car Consumables' => ['weight', 'volume', 'type'],
            'By Car Model' => ['car-model-year', 'engine-type', 'fuel-type'],
            
            // ===== Health & Medical =====
            'Health & Medical' => ['weight', 'year', 'material'],
            'Medical Equipment' => ['weight', 'year', 'power-usage'],
            'Orthopedic' => ['material', 'weight', 'size'],
            'Supplements' => ['weight', 'year', 'dosage'],
            'Dental Care' => ['material', 'weight', 'power-usage'],
            'First Aid' => ['weight', 'year'],
            'Fitness Equipment' => ['material', 'weight', 'power-usage'],
            
            // ===== Tools & Equipment =====
            'Tools & Equipment' => ['tool-type', 'power-source', 'voltage', 'material', 'weight', 'power-rating'],
            'Power Tools' => ['tool-type', 'power-source', 'voltage', 'weight', 'power-rating'],
            'Hand Tools' => ['tool-type', 'material', 'weight'],
            'Gardening Tools' => ['tool-type', 'material', 'weight'],
            'Safety Equipment' => ['material', 'weight', 'size'],
            'Measuring Tools' => ['material', 'weight', 'accuracy'],
            'Tool Sets' => ['tool-type', 'material', 'weight'],
            
            // ===== Books & Art =====
            'Books & Art' => ['author', 'publisher', 'book-language', 'cover-type', 'pages-count', 'year', 'book-genre'],
            'Books' => ['author', 'publisher', 'book-language', 'cover-type', 'pages-count', 'book-genre'],
            'Art & Painting' => ['material', 'year', 'style'],
            'Handicrafts' => ['material', 'year', 'region'],
            
            // ===== Sports & Travel =====
            'Sports & Travel' => ['sport-type', 'gender', 'size', 'material', 'weight', 'sport-level', 'weather-resistance'],
            'Sports Equipment' => ['sport-type', 'material', 'weight', 'sport-level'],
            'Sportswear' => ['sport-type', 'gender', 'clothing-size', 'fabric-type', 'weather-resistance'],
            'Travel Equipment' => ['material', 'weight', 'size', 'weather-resistance'],
            'Outdoor Sports' => ['sport-type', 'material', 'weight', 'weather-resistance'],
            'Camping Gear' => ['sport-type', 'material', 'weight', 'weather-resistance'],
            'Cycling' => ['sport-type', 'material', 'weight', 'gender', 'size'],
            
            // ===== Gift Cards =====
            'Gift Cards' => ['year', 'amount'],
            'Store Gift Cards' => ['year', 'amount'],
            'Digital Gift Cards' => ['year', 'amount', 'platform'],
            'Custom Gift Cards' => ['year', 'amount', 'occasion'],
        ];

        foreach ($categories as $category) {
            $attrSlugs = $categoryAttributeMap[$category->name] ?? ['color', 'material', 'weight'];
            
            foreach ($attrSlugs as $slug) {
                $attribute = $allAttributes->firstWhere('slug', $slug);
                if ($attribute) {
                    $exists = DB::table('category_attributes')
                        ->where('category_id', $category->id)
                        ->where('attribute_id', $attribute->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('category_attributes')->insert([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'sort_order' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
// ========== کد اصلاح شده بخش ۴ در AttributeSeeder ==========

// ۱. انتخاب بین ۱ تا ۳ رنگ
$selectedColorIds = collect();
if ($colorAttribute) {
    $colors = $allAttributeValues
        ->where('attribute_id', $colorAttribute->id)
        ->random(rand(1, 3));

    foreach ($colors as $color) {
        DB::table('product_variant_attribute_values')
            ->updateOrInsert(
                [
                    'product_variant_id' => $variant->id,
                    'attribute_value_id' => $color->id,
                ],
                ['created_at' => now(), 'updated_at' => now()]
            );
        $selectedColorIds->push($color->id);
    }
}

// ۲. انتخاب دقیقاً ۱۰ ویژگی دیگر (به جز رنگ‌های انتخاب شده)
$otherAttributes = collect($categoryAttrs)
    ->reject(fn($id) => $selectedColorIds->contains($id)) // رنگ‌های انتخاب شده را حذف کن
    ->shuffle()
    ->values();

// تضمین انتخاب ۱۰ ویژگی (حتی اگر لیست کمتر از ۱۰ مورد باشد، دوباره از اول لیست برمی‌دارد)
$selectedAttributes = [];
$pool = $otherAttributes->toArray();

while (count($selectedAttributes) < 10) {
    if (empty($pool)) {
        // اگر ویژگی‌ها تمام شد و به ۱۰ نرسیدیم، دوباره از اول شروع کن (برای تکمیل ۱۰ ویژگی)
        $pool = $otherAttributes->toArray();
        shuffle($pool);
    }
    $randomKey = array_rand($pool);
    $selectedAttributes[] = $pool[$randomKey];
    unset($pool[$randomKey]); // از انتخاب تکراری جلوگیری کن
}

// ۳. ذخیره ۱۰ ویژگی در دیتابیس
foreach ($selectedAttributes as $attributeId) {
    $values = $allAttributeValues->where('attribute_id', $attributeId);
    if ($values->isEmpty()) continue;

    $value = $values->random();

    DB::table('product_variant_attribute_values')
        ->updateOrInsert(
            [
                'product_variant_id' => $variant->id,
                'attribute_value_id' => $value->id,
            ],
            ['created_at' => now(), 'updated_at' => now()]
        );
}


    

}


$this->command->info('✅ Random colors and 10 attributes attached to all variants!');

        // ================================================================
        // 5. FINAL REPORT
        // ================================================================
        $attributeCount = DB::table('attributes')->count();
        $valueCount = DB::table('attribute_values')->count();
        $relationCount = DB::table('product_variant_attribute_values')->count();
        $categoryAttrCount = DB::table('category_attributes')->count();

        $this->command->info('✅ ' . $attributeCount . ' attributes created!');
        $this->command->info('✅ ' . $valueCount . ' attribute values created!');
        $this->command->info('✅ ' . $categoryAttrCount . ' category-attribute relations created!');
        $this->command->info('✅ ' . $relationCount . ' product variant attribute values created!');
        $this->command->info('🎯 All operations completed successfully!');
    }
}
 
  

 