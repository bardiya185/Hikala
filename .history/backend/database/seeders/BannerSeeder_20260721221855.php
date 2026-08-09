<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerPosition;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $middle4 = BannerPosition::where('key', 'home_middle_4')->first();
        $middle2 = BannerPosition::where('key', 'home_middle_2')->first();
        $slider = BannerPosition::where('key', 'home_slider')->first();

        if (!$middle4 || !$middle2 || !$slider) {
            $this->command->warn('⚠️ Positions not found. Run BannerPositionSeeder first!');
            return;
        }

        $category = Category::first();
        $brand = Brand::first();

        $banners = [
            // ================================================
            // 🎯 4 Middle Banners (like Digikala)
            // ================================================
            [
                'banner_position_id' => $middle4->id,
                'title' => 'New Arrivals',
                'subtitle' => 'Up to 50% Off',
                'image' => 'banners/sample-1.jpg',
                'alt_text' => 'New products from new sellers',
                'background_color' => '#E53E3E',
                'text_color' => '#FFFFFF',
                'custom_url' => '/new-products',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'banner_position_id' => $middle4->id,
                'title' => 'Featured Sellers',
                'subtitle' => 'Pet Shop',
                'image' => 'banners/sample-2.jpg',
                'alt_text' => 'Featured pet shop sellers',
                'background_color' => '#6B46C1',
                'text_color' => '#FFFFFF',
                'linkable_type' => Category::class,
                'linkable_id' => $category?->id,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'banner_position_id' => $middle4->id,
                'title' => 'Daily Care',
                'subtitle' => 'Up to 20% Off',
                'image' => 'banners/sample-3.jpg',
                'alt_text' => 'Daily dental care products',
                'background_color' => '#2C5282',
                'text_color' => '#FFFFFF',
                'linkable_type' => Brand::class,
                'linkable_id' => $brand?->id,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'banner_position_id' => $middle4->id,
                'title' => 'Everything for Kids',
                'subtitle' => 'Up to 50% Off',
                'image' => 'banners/sample-4.jpg',
                'alt_text' => 'Everything for happy kids',
                'background_color' => '#F687B3',
                'text_color' => '#FFFFFF',
                'custom_url' => '/baby-products',
                'sort_order' => 4,
                'is_active' => true,
            ],

            // ================================================
            // 🎯 2 Large Banners
            // ================================================
            [
                'banner_position_id' => $middle2->id,
                'title' => 'Discount Festival',
                'subtitle' => 'Up to 70% Off',
                'image' => 'banners/big-1.jpg',
                'alt_text' => 'Big discount festival',
                'background_color' => '#DD6B20',
                'text_color' => '#FFFFFF',
                'custom_url' => '/festival',
                'sort_order' => 1,
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addDays(7),
            ],
            [
                'banner_position_id' => $middle2->id,
                'title' => 'Top Brands',
                'subtitle' => 'With Authenticity Guarantee',
                'image' => 'banners/big-2.jpg',
                'alt_text' => 'Top brands collection',
                'background_color' => '#38A169',
                'text_color' => '#FFFFFF',
                'custom_url' => '/brands',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // ================================================
            // 🎯 Homepage Slider
            // ================================================
            [
                'banner_position_id' => $slider->id,
                'title' => 'Autumn Sale',
                'subtitle' => 'Up to 60% off on selected items',
                'image' => 'banners/slider-1.jpg',
                'alt_text' => 'Autumn sale campaign',
                'custom_url' => '/autumn-sale',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'banner_position_id' => $slider->id,
                'title' => 'New Products',
                'subtitle' => 'Discover the latest products',
                'image' => 'banners/slider-2.jpg',
                'alt_text' => 'New products showcase',
                'custom_url' => '/new-arrivals',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $this->command->info('✅ ' . count($banners) . ' banners created successfully!');
    }
}