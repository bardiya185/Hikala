<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $percentDiscount = Discount::where('type', 'percent')->first();
        $fixedDiscount = Discount::where('type', 'fixed')->first();

        if (!$percentDiscount || !$fixedDiscount) {
            $this->command->warn('⚠️ Discounts not found. Run DiscountSeeder first!');
            return;
        }

        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount_id' => $percentDiscount->id,
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'discount_id' => $percentDiscount->id,
                'usage_limit' => 50,
                'is_active' => true,
            ],
            [
                'code' => 'SAVE100',
                'discount_id' => $fixedDiscount->id,
                'usage_limit' => 30,
                'is_active' => true,
            ],
            [
                'code' => 'FLASH50',
                'discount_id' => $percentDiscount->id,
                'usage_limit' => null, // بدون محدودیت
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }

        $this->command->info('✅ ' . count($coupons) . ' coupons created!');
    }
}