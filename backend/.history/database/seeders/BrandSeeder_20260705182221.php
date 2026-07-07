<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = require database_path('data/brands.php');

        $sort = 1;

        foreach ($brands as $brand) {

            Brand::updateOrCreate(

                [
                    'slug' => $brand['slug'],
                ],

                [
                    'name' => $brand['name'],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ]

            );

        }
    }
}