<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = require database_path('data/attributes.php');

        $sort = 1;

        foreach ($attributes as $attribute) {

            Attribute::updateOrCreate(

                [
                    'slug' => $attribute['slug'],
                ],

                [
                    'name' => $attribute['name'],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ]

            );

        }
    }
}