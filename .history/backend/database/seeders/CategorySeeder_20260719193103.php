<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{

    public function run(): void
    {

        DB::table('categories')->truncate();


        /*
        |--------------------------------------------------------------------------
        | Main Categories
        |--------------------------------------------------------------------------
        */

        $mainCategories = [

            [
                'name'=>'Mobile',
                'slug'=>'mobile'
            ],

            [
                'name'=>'Laptops',
                'slug'=>'laptops'
            ],

            [
                'name'=>'Digital Products',
                'slug'=>'digital-products'
            ],

            [
                'name'=>'Home Appliances',
                'slug'=>'home-appliances'
            ],

            [
                'name'=>'Fashion',
                'slug'=>'fashion'
            ],

            [
                'name'=>'Beauty',
                'slug'=>'beauty-health'
            ],

            [
                'name'=>'Tools',
                'slug'=>'tools-equipment'
            ],

        ];


        $mainIds=[];


        foreach($mainCategories as $category)
        {

            $mainIds[$category['slug']] =
            DB::table('categories')->insertGetId([

                'parent_id'=>null,

                'name'=>$category['name'],

                'slug'=>$category['slug'],

                'is_active'=>1,

                'created_at'=>now(),

                'updated_at'=>now()

            ]);

        }

                /*
        |--------------------------------------------------------------------------
        | Select Categories
        |--------------------------------------------------------------------------
        */


        $selectCategories = [

            [
                'parent'=>'mobile',
                'name'=>'Select Mobile',
                'slug'=>'select-mobile'
            ],

            [
                'parent'=>'laptops',
                'name'=>'Select Laptop',
                'slug'=>'select-laptop'
            ],

            [
                'parent'=>'digital-products',
                'name'=>'Select Digital',
                'slug'=>'select-digital'
            ],

            [
                'parent'=>'home-appliances',
                'name'=>'Select Appliances',
                'slug'=>'select-appliance'
            ],

            [
                'parent'=>'fashion',
                'name'=>'Select Fashion',
                'slug'=>'select-fashion'
            ],

            [
                'parent'=>'beauty-health',
                'name'=>'Select Beauty',
                'slug'=>'select-beauty'
            ],

        ];



        $selectIds=[];



        foreach($selectCategories as $category)
        {


            $parentId = $mainIds[$category['parent']] ?? null;


            $selectIds[$category['slug']] =

            DB::table('categories')->insertGetId([

                'parent_id'=>$parentId,

                'name'=>$category['name'],

                'slug'=>$category['slug'],

                'is_active'=>1,

                'created_at'=>now(),

                'updated_at'=>now()

            ]);

        }




        /*
        |--------------------------------------------------------------------------
        | Child Categories
        |--------------------------------------------------------------------------
        */


        $children = [

            // Mobile

            [
                'parent'=>'select-mobile',
                'name'=>'Apple Phones',
                'slug'=>'apple-phones'
            ],

            [
                'parent'=>'select-mobile',
                'name'=>'Samsung Phones',
                'slug'=>'samsung-phones'
            ],

            [
                'parent'=>'select-mobile',
                'name'=>'Xiaomi Phones',
                'slug'=>'xiaomi-phones'
            ],


            [
                'parent'=>'select-mobile',
                'name'=>'Other Brands',
                'slug'=>'other-brands'
            ],



            // Laptop


            [
                'parent'=>'select-laptop',
                'name'=>'Apple MacBooks',
                'slug'=>'apple-macbooks'
            ],

            [
                'parent'=>'select-laptop',
                'name'=>'Asus Laptops',
                'slug'=>'asus-laptops'
            ],


            [
                'parent'=>'select-laptop',
                'name'=>'Lenovo Laptops',
                'slug'=>'lenovo-laptops'
            ],


            [
                'parent'=>'select-laptop',
                'name'=>'Gaming Laptops',
                'slug'=>'gaming-laptops'
            ],


            [
                'parent'=>'select-laptop',
                'name'=>'Business Laptops',
                'slug'=>'business-laptops'
            ],




            // Digital


            [
                'parent'=>'select-digital',
                'name'=>'Gaming Consoles',
                'slug'=>'gaming-consoles'
            ],


            [
                'parent'=>'select-digital',
                'name'=>'Headphones',
                'slug'=>'headphones'
            ],


            [
                'parent'=>'select-digital',
                'name'=>'Smart Watches',
                'slug'=>'smartwatches'
            ],


            [
                'parent'=>'select-digital',
                'name'=>'Cameras',
                'slug'=>'cameras'
            ],





            // Home


            [
                'parent'=>'select-appliance',
                'name'=>'Refrigerators',
                'slug'=>'refrigerators'
            ],


            [
                'parent'=>'select-appliance',
                'name'=>'Washing Machines',
                'slug'=>'washing-machines'
            ],


            [
                'parent'=>'select-appliance',
                'name'=>'Televisions',
                'slug'=>'tvs'
            ],



            // Fashion


            [
                'parent'=>'select-fashion',
                'name'=>'Mens Clothing',
                'slug'=>'mens-clothing'
            ],


            [
                'parent'=>'select-fashion',
                'name'=>'Womens Clothing',
                'slug'=>'womens-clothing'
            ],


            [
                'parent'=>'select-fashion',
                'name'=>'Shoes',
                'slug'=>'shoes'
            ],



            // Beauty


            [
                'parent'=>'select-beauty',
                'name'=>'Skin Care',
                'slug'=>'skin-care'
            ],


            [
                'parent'=>'select-beauty',
                'name'=>'Makeup',
                'slug'=>'makeup'
            ],


            [
                'parent'=>'select-beauty',
                'name'=>'Perfumes',
                'slug'=>'perfumes'
            ],


        ];




        foreach($children as $child)
        {

            DB::table('categories')->insert([


                'parent_id'=>$selectIds[$child['parent']],


                'name'=>$child['name'],


                'slug'=>$child['slug'],


                'is_active'=>1,


                'created_at'=>now(),


                'updated_at'=>now()


            ]);

        }



        $this->command->info('Categories seeded successfully');

    }

}