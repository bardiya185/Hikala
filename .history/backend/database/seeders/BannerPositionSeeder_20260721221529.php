<?php

namespace Database\Seeders;

use App\Models\BannerPosition;
use Illuminate\Database\Seeder;

class BannerPositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'key' => 'home_slider',
                'name' => 'اسلایدر بالای صفحه اصلی',
                'description' => 'اسلایدر بزرگ در بالای صفحه اصلی',
                'max_banners' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'home_middle_4',
                'name' => '۴ بنر وسط صفحه اصلی',
                'description' => 'مثل بنرهای دیجی‌کالا وسط صفحه',
                'max_banners' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'home_middle_2',
                'name' => '۲ بنر بزرگ صفحه اصلی',
                'description' => 'دو بنر بزرگ برای تبلیغات ویژه',
                'max_banners' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'home_single',
                'name' => 'بنر تک صفحه اصلی',
                'description' => 'یک بنر عریض برای تبلیغ ویژه',
                'max_banners' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'category_top',
                'name' => 'بنر بالای صفحه دسته‌بندی',
                'description' => 'بنر تبلیغاتی بالای هر صفحه دسته',
                'max_banners' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'sidebar',
                'name' => 'بنر کناری',
                'description' => 'بنر کنار محتوا',
                'max_banners' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($positions as $position) {
            BannerPosition::updateOrCreate(
                ['key' => $position['key']],
                $position
            );
        }
    }
}