<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,     
            ProductDatabaseSeeder::class,  
            AttributeSeeder::class, 
        ]);
    }
}