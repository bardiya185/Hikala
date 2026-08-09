<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerPosition;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create directory if not exists
        if (!Storage::disk('public')->exists('banners')) {
            Storage::disk('public')->makeDirectory('banners');
        }

        $middle4 = BannerPosition::where('key', 'home_middle_4')->first();

        if (!$middle4) {
            $this->command->warn('⚠️ Position "home_middle_4" not found!');
            return;
        }

        // Clear previous banners
        Banner::where('banner_position_id', $middle4->id)->delete();

        // 🔥 Get real IDs from database (optional - will fallback to custom_url)
        $mobileCategory = Category::where('id', 2)->first();
        $laptopCategory = Category::where('id', 36)->first();
        $beautyCategory = Category::where('id', 127)->first();
        $jewelryCategory = Category::where('id', 155)->first();

        $banners = [
            [
                'title' => 'Mobile Mania',
                'subtitle' => 'Latest smartphones, up to 25% off',
                'image' => 'banners/mobile-mania.jpg',
                'alt_text' => 'Latest smartphones on sale',
                'background_color' => '#DC2626',
                'text_color' => '#FFFFFF',
                'linkable_type' => $mobileCategory ? Category::class : null,
                'linkable_id' => $mobileCategory?->id,
                'custom_url' => $mobileCategory ? null : '/products?category_id=1&min_discount=10',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Power Up Your Setup',
                'subtitle' => 'Gaming laptops & consoles',
                'image' => 'banners/power-setup.jpg',
                'alt_text' => 'Gaming setup collection',
                'background_color' => '#1F2937',
                'text_color' => '#FBBF24',
                'linkable_type' => $laptopCategory ? Category::class : null,
                'linkable_id' => $laptopCategory?->id,
                'custom_url' => $laptopCategory ? null : '/products?category_id=36',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Glow & Shine',
                'subtitle' => 'Beauty essentials for you',
                'image' => 'banners/glow-shine.jpg',
                'alt_text' => 'Beauty and health products',
                'background_color' => '#EC4899',
                'text_color' => '#FFFFFF',
                'linkable_type' => $beautyCategory ? Category::class : null,
                'linkable_id' => $beautyCategory?->id,
                'custom_url' => $beautyCategory ? null : '/products?category_id=127',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Luxury Meets Elegance',
                'subtitle' => 'Gold & Diamond collection',
                'image' => 'banners/luxury-elegance.jpg',
                'alt_text' => 'Luxury jewelry collection',
                'background_color' => '#B45309',
                'text_color' => '#FFFBEB',
                'linkable_type' => $jewelryCategory ? Category::class : null,
                'linkable_id' => $jewelryCategory?->id,
                'custom_url' => $jewelryCategory ? null : '/products?category_id=155',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create([
                'banner_position_id' => $middle4->id,
                ...$banner,
            ]);
        }

        $this->command->info('✅ ' . count($banners) . ' banners created!');
        $this->command->warn('⚠️ Remember to place image files in: storage/app/public/banners/');
    }
}