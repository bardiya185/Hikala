<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ::class,   // محصولات و دسته‌بندی‌ها
            AttributeSeeder::class,       // ویژگی‌ها و مقادیر
        ]);
    }
}