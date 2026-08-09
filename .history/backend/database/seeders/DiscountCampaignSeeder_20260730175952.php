<?php

namespace Database\Seeders;

use App\Models\DiscountCampaign;
use Illuminate\Database\Seeder;

class DiscountCampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Flash Sale',
                'slug' => 'flash-sale',
                'description' => 'Limited time offers with huge discounts',
                'icon' => '⚡',
                'color' => '#DC2626',
                'banner_image' => null,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(2),
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Special Offer',
                'slug' => 'special',
                'description' => 'Special deals for our valued customers',
                'icon' => '⭐',
                'color' => '#7C3AED',
                'banner_image' => null,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(10),
                'priority' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Weekly Deal',
                'slug' => 'weekly',
                'description' => 'Fresh deals every week',
                'icon' => '📅',
                'color' => '#059669',
                'banner_image' => null,
                'starts_at' => now()->startOfWeek(),
                'ends_at' => now()->endOfWeek(),
                'priority' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Clearance Sale',
                'slug' => 'clearance',
                'description' => 'Last chance to grab these items',
                'icon' => '🔥',
                'color' => '#F59E0B',
                'banner_image' => null,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(7),
                'priority' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($campaigns as $campaign) {
            DiscountCampaign::updateOrCreate(
                ['slug' => $campaign['slug']],
                $campaign
            );
        }

        $this->command->info('✅ ' . count($campaigns) . ' discount campaigns created!');
    }
}