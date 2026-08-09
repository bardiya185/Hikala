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
}