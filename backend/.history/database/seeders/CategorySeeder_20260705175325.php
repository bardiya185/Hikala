<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // موبایل
        // ==========================

        $mobile = Category::updateOrCreate(
            ['slug' => 'mobile'],
            [
                'parent_id' => null,
                'name' => 'موبایل',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'apple-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی اپل',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'samsung-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی سامسونگ',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'xiaomi-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی شیائومی',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'poco-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی پوکو',
                'sort_order' => 4,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'honor-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی آنر',
                'sort_order' => 5,
                'is_active' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'huawei-phone'],
            [
                'parent_id' => $mobile->id,
                'name' => 'گوشی هوآوی',
                'sort_order' => 6,
                'is_active' => true,
            ]
        );
    }
}