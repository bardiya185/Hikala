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
            CategoryDumpSeeder::class,
            ProductImageSeeder::class,  
            ShippingFeatureSeeder::class,  
            DiscountCampaignSeeder::class,
            DiscountSeeder::class,
            CouponSeeder::class,
            RolePermissionSeeder::class,
            BannerPositionSeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            ReviewSeeder::class,
            ReviewReactionSeeder::class,

        ]);
    }
}