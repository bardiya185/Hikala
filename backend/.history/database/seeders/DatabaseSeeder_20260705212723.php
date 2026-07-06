<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SimpleProductSeeder::class,   // محصولات و دسته‌بندی‌ها
            AttributeSeeder::class,       // ویژگی‌ها و مقادیر
        ]);
    }
}