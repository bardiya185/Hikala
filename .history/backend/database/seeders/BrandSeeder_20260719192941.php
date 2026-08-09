<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            // Mobile
            ['name'=>'Apple','slug'=>'apple'],
            ['name'=>'Samsung','slug'=>'samsung'],
            ['name'=>'Xiaomi','slug'=>'xiaomi'],
            ['name'=>'Google','slug'=>'google'],
            ['name'=>'OnePlus','slug'=>'oneplus'],
            ['name'=>'Huawei','slug'=>'huawei'],
            ['name'=>'Nokia','slug'=>'nokia'],
            ['name'=>'Sony','slug'=>'sony'],
            ['name'=>'Motorola','slug'=>'motorola'],
            ['name'=>'Realme','slug'=>'realme'],
            ['name'=>'Nothing','slug'=>'nothing'],
            ['name'=>'Poco','slug'=>'poco'],


            // Laptop
            ['name'=>'Asus','slug'=>'asus'],
            ['name'=>'Lenovo','slug'=>'lenovo'],
            ['name'=>'Dell','slug'=>'dell'],
            ['name'=>'HP','slug'=>'hp'],
            ['name'=>'Acer','slug'=>'acer'],
            ['name'=>'MSI','slug'=>'msi'],
            ['name'=>'Razer','slug'=>'razer'],


            // Digital
            ['name'=>'JBL','slug'=>'jbl'],
            ['name'=>'Bose','slug'=>'bose'],
            ['name'=>'Anker','slug'=>'anker'],
            ['name'=>'Canon','slug'=>'canon'],
            ['name'=>'Nikon','slug'=>'nikon'],
            ['name'=>'Nintendo','slug'=>'nintendo'],
            ['name'=>'PlayStation','slug'=>'playstation'],
            ['name'=>'Xbox','slug'=>'xbox'],


            // Home
            ['name'=>'LG','slug'=>'lg'],
            ['name'=>'Bosch','slug'=>'bosch'],
            ['name'=>'Philips','slug'=>'philips'],
            ['name'=>'Samsung Home','slug'=>'samsung-home'],
            ['name'=>'Whirlpool','slug'=>'whirlpool'],
            ['name'=>'Snowa','slug'=>'snowa'],
            ['name'=>'Pakshoma','slug'=>'pakshoma'],


            // Fashion
            ['name'=>'Nike','slug'=>'nike'],
            ['name'=>'Adidas','slug'=>'adidas'],
            ['name'=>'Puma','slug'=>'puma'],
            ['name'=>'Zara','slug'=>'zara'],
            ['name'=>'H&M','slug'=>'hm'],
            ['name'=>'Levis','slug'=>'levis'],


            // Tools
            ['name'=>'Makita','slug'=>'makita'],
            ['name'=>'DeWalt','slug'=>'dewalt'],
            ['name'=>'Stanley','slug'=>'stanley'],
            ['name'=>'Milwaukee','slug'=>'milwaukee'],


            // Luxury
            ['name'=>'Rolex','slug'=>'rolex'],
            ['name'=>'Omega','slug'=>'omega'],
            ['name'=>'Seiko','slug'=>'seiko'],
            ['name'=>'Tissot','slug'=>'tissot'],
            ['name'=>'Citizen','slug'=>'citizen'],

        ];


        foreach($brands as $brand)
        {

            DB::table('brands')->updateOrInsert(
                [
                    'slug'=>$brand['slug']
                ],
                [
                    'name'=>$brand['name'],
                    'is_active'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]
            );

        }


        $this->command->info('Brands seeded successfully');
    }
}