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
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Special Offer',
                'slug' => 'special',
                'description' => 'Special deals for our valued customers',
                'icon' => '⭐',
                'color' => '#7C3AED',
                'priority' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Weekly Deal',
                'slug' => 'weekly',
                'description' => 'Fresh deals every week',
                'icon' => '📅',
                'color' => '#059669',
                'priority' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Clearance Sale',
                'slug' => 'clearance',
                'description' => 'Last chance to grab these items',
                'icon' => '🔥',
                'color' => '#F59E0B',
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