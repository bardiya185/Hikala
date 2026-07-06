<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = require database_path('data/categories.php');

        $sort = 1;

        foreach ($categories as $parent) {

            $parentCategory = Category::updateOrCreate(

                [
                    'slug' => $parent['slug'],
                ],

                [
                    'parent_id' => null,
                    'name' => $parent['name'],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ]

            );

            $childSort = 1;

            foreach ($parent['children'] as $child) {

                Category::updateOrCreate(

                    [
                        'slug' => $child['slug'],
                    ],

                    [
                        'parent_id' => $parentCategory->id,
                        'name' => $child['name'],
                        'sort_order' => $childSort++,
                        'is_active' => true,
                    ]

                );

            }

        }
    }
}