<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryImageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Updating Main Category Images and Banners...');

        // لیست دقیق دسته‌بندی‌های اصلی با مسیر بنر، آیکون و ترتیب بر اساس تصویر دیتابیس شما
        $mainCategories = [
            [
                'slug'       => 'mobile',
                'icon_key'   => 'mobile',
                'banner'     => 'categories_logo/mobile.webp',
                'sort_order' => 1,
            ],
            [
                'slug'       => 'laptops',
                'icon_key'   => 'laptops',
                'banner'     => 'categories_logo/laptop.webp',
                'sort_order' => 2,
            ],
            [
                'slug'       => 'digital-products',
                'icon_key'   => 'digital',
                'banner'     => 'categories_logo/digital_products.webp',
                'sort_order' => 3,
            ],
            [
                'slug'       => 'home-kitchen',
                'icon_key'   => 'home-kitchen',
                'banner'     => 'categories_logo/home_kitchen.webp',
                'sort_order' => 4,
            ],
            [
                'slug'       => 'home-appliances',
                'icon_key'   => 'home-appliances',
                'banner'     => 'categories_logo/home_appliances.webp',
                'sort_order' => 5,
            ],
            [
                'slug'       => 'beauty-health',
                'icon_key'   => 'beauty-health',
                'banner'     => 'categories_logo/beauty_health.webp',
                'sort_order' => 6,
            ],
            [
                'slug'       => 'fashion',
                'icon_key'   => 'fashion',
                'banner'     => 'categories_logo/fashion.webp',
                'sort_order' => 7,
            ],
            [
                'slug'       => 'gold-jewelry',
                'icon_key'   => 'gold-jewelry',
                'banner'     => 'categories_logo/gold_Jewelry.webp',
                'sort_order' => 8,
            ],
            [
                'slug'       => 'vehicles',
                'icon_key'   => 'vehicles',
                'banner'     => 'categories_logo/vehicles.webp',
                'sort_order' => 9,
            ],
            [
                'slug'       => 'health-medical',
                'icon_key'   => 'health-medical',
                'banner'     => 'categories_logo/health_medical.webp',
                'sort_order' => 10,
            ],
            [
                'slug'       => 'tools-equipment',
                'icon_key'   => 'tools-equipment',
                'banner'     => 'categories_logo/tools_equipment.webp',
                'sort_order' => 11,
            ],
            [
                'slug'       => 'sports-travel',
                'icon_key'   => 'sports-travel',
                'banner'     => 'categories_logo/sports_travel.webp',
                'sort_order' => 12,
            ],
        ];

        $updatedCount = 0;

        foreach ($mainCategories as $data) {
            // آپدیت کردن دسته‌بندی بر اساس اسلاگ (اگر وجود نداشت نادیده می‌گیرد)
            $affected = DB::table('categories')
                ->where('slug', $data['slug'])
                ->whereNull('parent_id') // فقط دسته‌های اصلی
                ->update([
                    'icon_key'   => $data['icon_key'],
                    'banner'     => $data['banner'],
                    'sort_order' => $data['sort_order'],
                    'updated_at' => now(),
                ]);

            if ($affected) {
                $updatedCount++;
                $this->command->line("   ✅ Updated image for: {$data['slug']}");
            } else {
                $this->command->warn("   ⚠️ Category with slug [{$data['slug']}] not found.");
            }
        }

        $this->command->newLine();
        $this->command->info("🎉 Successfully updated {$updatedCount} main category banners!");
    }
}