<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discount = Discount::create([

            'name' => 'شگفت انگیز آیفون',

            'type' => 'percent',

            'value' => 20,

            'starts_at' => now()->subDay(),

            'ends_at' => now()->addDays(3),

            'quantity_limit' => 100,

            'priority' => 10,

            'is_flash_sale' => true,

            'is_active' => true,

        ]);

        $variant = ProductVariant::first();

        if ($variant) {
            $discount->variants()->attach($variant->id);
        }
    }
}