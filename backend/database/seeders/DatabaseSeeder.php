<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,     
            ProductDatabaseSeeder::class,  
            ShippingFeatureSeeder::class,  
            DiscountSeeder::class,
            CouponSeeder::class,
            RolePermissionSeeder::class,
            BannerPositionSeeder::class,
            BannerSeeder::class

        ]);
    }
}