<?php

namespace Database\Seeders;

use App\Enums\ShippingFeatureType;
use App\Models\ProductVariant;
use App\Models\ProductVariantShippingFeature;
use Illuminate\Database\Seeder;

class ShippingFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Get all product variants
        $variants = ProductVariant::all();
        
        if ($variants->isEmpty()) {
            $this->command->warn('⚠️ No product variants found!');
            return;
        }
        
        $count = 0;
        
        foreach ($variants as $variant) {
            
            // Random features for each variant
            $features = $this->getRandomFeatures();
            
            foreach ($features as $feature) {
                ProductVariantShippingFeature::create([
                    'product_variant_id' => $variant->id,
                    'type' => $feature['type'],
                    'title' => $feature['title'],
                    'description' => $feature['description'],
                    'is_active' => true,
                ]);
                
                $count++;
            }
        }
        
        $this->command->info("✅ {$count} shipping features created!");
    }


    /**
     * Get random feature combinations
     */
    private function getRandomFeatures(): array
    {
        $allFeatures = [
            [
                'type' => ShippingFeatureType::FAST->value,
                'title' => 'Fast Delivery Available',
                'description' => 'Get your order in just 24 hours',
            ],
            [
                'type' => ShippingFeatureType::SAME_DAY->value,
                'title' => 'Same Day Delivery',
                'description' => 'Order before 2 PM and get it today',
            ],
            [
                'type' => ShippingFeatureType::FREE->value,
                'title' => 'Free Shipping',
                'description' => 'No shipping cost for this item',
            ],
            [
                'type' => ShippingFeatureType::STANDARD->value,
                'title' => 'Standard Shipping',
                'description' => 'Regular delivery in 5-7 business days',
            ],
        ];
        
        // Randomly select 2-3 features
        $count = rand(2, 3);
        shuffle($allFeatures);
        
        return array_slice($allFeatures, 0, $count);
    }
}