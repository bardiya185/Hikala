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
                'name' => 'Home Page Slider',
                'description' => 'Main slider at the top of homepage',
                'max_banners' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'home_middle_4',
                'name' => '4 Middle Banners',
                'description' => '4 promotional banners in the middle of homepage',
                'max_banners' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'home_middle_2',
                'name' => '2 Large Middle Banners',
                'description' => '2 large banners for special promotions',
                'max_banners' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'home_single',
                'name' => 'Homepage Single Banner',
                'description' => 'Wide single banner for special ads',
                'max_banners' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'category_top',
                'name' => 'Category Top Banner',
                'description' => 'Banner at the top of category pages',
                'max_banners' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'sidebar',
                'name' => 'Sidebar Banners',
                'description' => 'Banners on the sidebar',
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