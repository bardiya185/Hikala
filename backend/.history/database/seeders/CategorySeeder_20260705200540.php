<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{use App\Models\Category;
    use Illuminate\Support\Str;
   
        public function run()
        {
            $data = [
                'کنسول بازی' => [
                    'PS5',
                    'PS4',
                    'Xbox',
                    'Nintendo',
                ],
    
                'کامپیوتر' => [
                    'پردازنده',
                    'کارت گرافیک',
                    'مادربرد',
                    'رم',
                ],
    
                'موبایل' => [
                    'اپل',
                    'سامسونگ',
                    'شیائومی',
                ],
    
                'لپ تاپ' => [
                    'گیمینگ',
                    'اداری',
                    'اولترابوک',
                ],
            ];
    
            foreach ($data as $parent => $children) {
    
                $parentCategory = Category::create([
                    'name' => $parent,
                    'slug' => Str::slug($parent),
                    'parent_id' => null,
                ]);
    
                foreach ($children as $child) {
                    Category::create([
                        'name' => $child,
                        'slug' => Str::slug($child),
                        'parent_id' => $parentCategory->id,
                    ]);
                }
            }
        }
    }
}