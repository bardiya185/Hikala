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
