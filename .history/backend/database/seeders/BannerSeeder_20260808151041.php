<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerPosition;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\DiscountCampaign;
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

        $this->seedHomeMiddle4();
        $this->seedHomeSlider();

        $this->command->info('🎉 All banners seeded successfully!');
    }

    // ================================================================
    // 🎯 Home Middle 4 Banners
    // ================================================================
    private function seedHomeMiddle4(): void
    {
        $position = BannerPosition::where('key', 'home_middle_4')->first();

        if (!$position) {
            $this->command->warn('⚠️ Position "home_middle_4" not found!');
            return;
        }

        Banner::where('banner_position_id', $position->id)->delete();

        // 🔍 Get real entities from database
        $mobileCategory = Category::find(2);
        $laptopCategory = Category::find(36);
        $beautyCategory = Category::find(127);
        $jewelryCategory = Category::find(155);

        // 🎯 Get campaign for special banner
        $flashSaleCampaign = DiscountCampaign::where('slug', 'flash-sale')->first();

        $banners = [
            // ==========================================
            // Banner 1: Mobile Category
            // ==========================================
            [
                'title' => 'Mobile Mania',
                'subtitle' => 'Latest smartphones, up to 25% off',
                'image' => 'banners/mobile-mania.jpg',
                'alt_text' => 'Latest smartphones on sale',
                'background_color' => '#DC2626',
                'text_color' => '#FFFFFF',
                'link' => $this->resolveLink(
                    Category::class,
                    $mobileCategory?->id,
                    '/products?category_id=2&min_discount=10'
                ),
                'sort_order' => 1,
            ],

            // ==========================================
            // Banner 2: Laptop/Gaming Category
            // ==========================================
            [
                'title' => 'Power Up Your Setup',
                'subtitle' => 'Gaming laptops & consoles',
                'image' => 'banners/power-setup.jpg',
                'alt_text' => 'Gaming setup collection',
                'background_color' => '#1F2937',
                'text_color' => '#FBBF24',
                'link' => $this->resolveLink(
                    Category::class,
                    $laptopCategory?->id,
                    '/products?category_id=36'
                ),
                'sort_order' => 2,
            ],

            // ==========================================
            // Banner 3: Beauty Category
            // ==========================================
            [
                'title' => 'Glow & Shine',
                'subtitle' => 'Beauty essentials for you',
                'image' => 'banners/glow-shine.jpg',
                'alt_text' => 'Beauty and health products',
                'background_color' => '#EC4899',
                'text_color' => '#FFFFFF',
                'link' => $this->resolveLink(
                    Category::class,
                    $beautyCategory?->id,
                    '/products?category_id=127'
                ),
                'sort_order' => 3,
            ],

            // ==========================================
            // Banner 4: Flash Sale Campaign (🔥 اگه موجود بود)
            // یا Jewelry Category (fallback)
            // ==========================================
            $flashSaleCampaign ? [
                'title' => '⚡ Flash Sale Now!',
                'subtitle' => 'Limited time - Up to 60% off',
                'image' => 'banners/flash-sale.jpg',
                'alt_text' => 'Flash Sale offers',
                'background_color' => '#DC2626',
                'text_color' => '#FFFFFF',
                'link' => [
                    'linkable_type' => null,
                    'linkable_id' => null,
                    'custom_url' => "/campaigns/{$flashSaleCampaign->slug}",
                ],
                'sort_order' => 4,
            ] : [
                'title' => 'Luxury Meets Elegance',
                'subtitle' => 'Gold & Diamond collection',
                'image' => 'banners/luxury-elegance.jpg',
                'alt_text' => 'Luxury jewelry collection',
                'background_color' => '#B45309',
                'text_color' => '#FFFBEB',
                'link' => $this->resolveLink(
                    Category::class,
                    $jewelryCategory?->id,
                    '/products?category_id=155'
                ),
                'sort_order' => 4,
            ],
        ];

        $this->createBanners($banners, $position->id, 'home_middle_4');
    }

    // ================================================================
    // 🎠 Home Slider Banners
    // ================================================================
    private function seedHomeSlider(): void
    {
        $position = BannerPosition::where('key', 'home_slider')->first();

        if (!$position) {
            $this->command->warn('⚠️ Position "home_slider" not found!');
            return;
        }

        Banner::where('banner_position_id', $position->id)->delete();

        $flashSaleCampaign = DiscountCampaign::where('slug', 'flash-sale')->first();
        $specialCampaign = DiscountCampaign::where('slug', 'special')->first();
        $mobileCategory = Category::find(2);

        $banners = [
            // Slider 1: Flash Sale Campaign
            [
                'title' => 'Autumn Festival',
                'subtitle' => 'Up to 70% off on thousands of products',
                'image' => 'banners/slider-1.jpg',
                'alt_text' => 'Autumn festival banner',
                'background_color' => '#DC2626',
                'text_color' => '#FFFFFF',
                'link' => $flashSaleCampaign ? [
                    'linkable_type' => null,
                    'linkable_id' => null,
                    'custom_url' => "/campaigns/{$flashSaleCampaign->slug}",
                ] : [
                    'linkable_type' => null,
                    'linkable_id' => null,
                    'custom_url' => '/products',
                ],
                'sort_order' => 1,
            ],

            // Slider 2: Special Offers
            [
                'title' => 'Special Offers',
                'subtitle' => 'Handpicked deals just for you',
                'image' => 'banners/slider-2.jpg',
                'alt_text' => 'Special offers',
                'background_color' => '#7C3AED',
                'text_color' => '#FFFFFF',
                'link' => $specialCampaign ? [
                    'linkable_type' => null,
                    'linkable_id' => null,
                    'custom_url' => "/campaigns/{$specialCampaign->slug}",
                ] : [
                    'linkable_type' => null,
                    'linkable_id' => null,
                    'custom_url' => '/products?has_discount=1',
                ],
                'sort_order' => 2,
            ],

            // Slider 3: Mobile Category
            [
                'title' => 'New Smartphones',
                'subtitle' => 'Latest iPhone & Samsung Galaxy',
                'image' => 'banners/slider-3.jpg',
                'alt_text' => 'New smartphones',
                'background_color' => '#1E40AF',
                'text_color' => '#FFFFFF',
                'link' => $this->resolveLink(
                    Category::class,
                    $mobileCategory?->id,
                    '/products?category_id=2'
                ),
                'sort_order' => 3,
            ],
        ];

        $this->createBanners($banners, $position->id, 'home_slider');
    }

    // ================================================================
    // 🧠 Smart Link Resolver
    // اگه entity موجود بود از linkable استفاده کن، وگرنه از custom_url
    // ================================================================
    private function resolveLink(string $modelClass, ?int $id, string $fallbackUrl): array
    {
        if (!$id) {
            return [
                'linkable_type' => null,
                'linkable_id' => null,
                'custom_url' => $fallbackUrl,
            ];
        }

        $entity = $modelClass::find($id);

        if ($entity) {
            return [
                'linkable_type' => $modelClass,
                'linkable_id' => $entity->id,
                'custom_url' => null,
            ];
        }

        return [
            'linkable_type' => null,
            'linkable_id' => null,
            'custom_url' => $fallbackUrl,
        ];
    }

    // ================================================================
    // 🏗️ Create Banners Helper
    // ================================================================
    private function createBanners(array $banners, int $positionId, string $positionKey): void
    {
        foreach ($banners as $bannerData) {
            $link = $bannerData['link'];
            unset($bannerData['link']);

            Banner::create([
                'banner_position_id' => $positionId,
                'is_active' => true,
                ...$bannerData,
                ...$link,
            ]);

            // 📊 Log for debugging
            $linkInfo = $link['linkable_type']
                ? "→ {$link['linkable_type']} #{$link['linkable_id']}"
                : "→ URL: {$link['custom_url']}";

            $this->command->info("   ✓ [{$positionKey}] {$bannerData['title']} {$linkInfo}");
        }

        $this->command->info("✅ " . count($banners) . " banners created for '{$positionKey}'!");
        $this->command->newLine();
    }
}