<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'POCO', 'slug' => 'poco'],
            ['name' => 'Honor', 'slug' => 'honor'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'Nokia', 'slug' => 'nokia'],
            ['name' => 'Realme', 'slug' => 'realme'],
            ['name' => 'Nothing', 'slug' => 'nothing'],
            ['name' => 'Google', 'slug' => 'google'],

            ['name' => 'ASUS', 'slug' => 'asus'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'Acer', 'slug' => 'acer'],
            ['name' => 'MSI', 'slug' => 'msi'],

            ['name' => 'LG', 'slug' => 'lg'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Anker', 'slug' => 'anker'],
            ['name' => 'Baseus', 'slug' => 'baseus'],

        ];

        foreach ($brands as $brand) {

            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'sort_order' => 1,
                    'is_active' => true,
                ]
            );

        }
    }
}