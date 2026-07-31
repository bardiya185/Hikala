<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating discounts for specific products...');

        // ================================================================
        // 🧹 پاک کردن داده‌های قبلی
        // ================================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('discount_usages')->truncate();
        DB::table('discount_user_limits')->truncate();
        DB::table('discountables')->truncate();
        DB::table('discounts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ================================================================
        // 📥 گرفتن داده‌های مورد نیاز
        // ================================================================
        $allProducts = DB::table('products')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();
        
        // ✅ گرفتن Campaign ها
        $flashSaleCampaign = DB::table('discount_campaigns')
            ->where('slug', 'flash-sale')
            ->first();
        
        $specialCampaign = DB::table('discount_campaigns')
            ->where('slug', 'special')
            ->first();
        
        $weeklyCampaign = DB::table('discount_campaigns')
            ->where('slug', 'weekly')
            ->first();

        // ⚠️ اگه Campaign ها نبودن، پیام بده
        if (!$flashSaleCampaign || !$specialCampaign) {
            $this->command->warn('⚠️ Discount Campaigns not found!');
            $this->command->warn('   Please run DiscountCampaignSeeder first.');
            return;
        }

        if (empty($allProducts)) {
            $this->command->warn('⚠️ No products found. Please seed products first.');
            return;
        }

        // تعداد محصولات برای هر تخفیف
        $productsPerDiscount = 4;
        shuffle($allProducts);

        // ================================================================
        // ۱️⃣ تعریف تخفیف‌ها
        // ================================================================
        $discounts = [
            [
                'campaign_id' => $flashSaleCampaign->id,   // ⚡ Flash Sale
                'name' => 'Amazing Flash Discount',
                'type' => 'percent',
                'value' => 60,
                'stackable' => false,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(7),
                'quantity_limit' => 50,
                'used_quantity' => 5,
                'priority' => 10,
            ],
            [
                'campaign_id' => $specialCampaign->id,     // ⭐ Special
                'name' => 'Special Fixed Discount',
                'type' => 'fixed',
                'value' => 100.00,
                'stackable' => false,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(14),
                'quantity_limit' => 30,
                'used_quantity' => 2,
                'priority' => 8,
            ],
            [
                'campaign_id' => $weeklyCampaign?->id,     // 📅 Weekly (اختیاری)
                'name' => 'Weekly 20% Off',
                'type' => 'percent',
                'value' => 20,
                'stackable' => true,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(6),
                'quantity_limit' => 100,
                'used_quantity' => 10,
                'priority' => 5,
            ],
            [
                'campaign_id' => null,                     // بدون کمپین (تخفیف عادی)
                'name' => 'Regular 10% Off',
                'type' => 'percent',
                'value' => 10,
                'stackable' => true,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(30),
                'quantity_limit' => null,
                'used_quantity' => 0,
                'priority' => 1,
            ],
        ];

        // ================================================================
        // ۲️⃣ ذخیره تخفیف‌ها
        // ================================================================
        $discountIds = [];

        foreach ($discounts as $index => $discount) {
            $id = DB::table('discounts')->insertGetId([
                'campaign_id' => $discount['campaign_id'],  // ✅ اضافه شد
                'name' => $discount['name'],
                'type' => $discount['type'],
                'value' => $discount['value'],
                'stackable' => $discount['stackable'],
                'quantity_limit' => $discount['quantity_limit'],
                'used_quantity' => $discount['used_quantity'],
                'priority' => $discount['priority'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $discountIds[] = $id;
            
            $campaignName = $discount['campaign_id'] ? 'in campaign' : 'standalone';
            $this->command->line("  ✅ Created: {$discount['name']} ({$campaignName})");
        }

        // ================================================================
        // ۳️⃣ اتصال تخفیف‌ها به محصولات
        // ================================================================
        $this->command->info('🔗 Attaching discounts to products...');

        $attachedCount = 0;
        $usedProductIds = [];

        foreach ($discountIds as $index => $discountId) {
            // محصولات باقی‌مونده
            $availableProducts = array_values(array_diff($allProducts, $usedProductIds));
            
            if (empty($availableProducts)) {
                $this->command->warn("  ⚠️ No more products available for discount #{$discountId}");
                continue;
            }
            
            shuffle($availableProducts);

            // انتخاب محصولات
            $count = min($productsPerDiscount, count($availableProducts));
            $selectedForThisDiscount = array_slice($availableProducts, 0, $count);

            foreach ($selectedForThisDiscount as $productId) {
                DB::table('discountables')->insert([
                    'discount_id' => $discountId,
                    'discountable_type' => 'App\Models\Product',
                    'discountable_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $attachedCount++;
            }

            $usedProductIds = array_merge($usedProductIds, $selectedForThisDiscount);
            
            $this->command->line("  ✅ Discount #{$discountId} attached to {$count} products");
        }

        $this->command->info("✅ Total {$attachedCount} discountable relationships created!");

        // ================================================================
        // ۴️⃣ محدودیت کاربری (اختیاری)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('👤 Creating user limits...');

            $limitCount = 0;
            foreach ($discountIds as $discountId) {
                // فقط برای بعضی تخفیف‌ها محدودیت بذار
                if (rand(0, 1) === 0) continue;
                
                $selectedUsers = array_slice($users, 0, min(rand(3, 5), count($users)));

                foreach ($selectedUsers as $userId) {
                    DB::table('discount_user_limits')->insert([
                        'discount_id' => $discountId,
                        'user_id' => $userId,
                        'max_quantity' => rand(1, 2),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $limitCount++;
                }
            }

            $this->command->info("✅ {$limitCount} user limits created!");
        }

        // ================================================================
        // ۵️⃣ ثبت استفاده
        // ================================================================
        if (!empty($users)) {
            $this->command->info('📝 Creating discount usages...');

            $usageCount = 0;
            foreach ($discountIds as $discountId) {
                $discount = DB::table('discounts')->where('id', $discountId)->first();

                if ($discount && $discount->used_quantity > 0) {
                    $userCount = min(rand(2, 4), count($users));
                    $randomUsers = array_slice($users, 0, $userCount);

                    foreach ($randomUsers as $userId) {
                        DB::table('discount_usages')->insert([
                            'discount_id' => $discountId,
                            'user_id' => $userId,
                            'quantity' => rand(1, 2),
                            'created_at' => now()->subDays(rand(1, 10)),
                            'updated_at' => now()->subDays(rand(0, 3)),
                        ]);
                        $usageCount++;
                    }
                }
            }

            $this->command->info("✅ {$usageCount} discount usages created!");
        }

        // ================================================================
        // ۶️⃣ گزارش نهایی
        // ================================================================
        $this->command->newLine();
        $this->command->info('🎉 Discount seeding completed!');
        $this->command->info('📊 Statistics:');
        $this->command->line('   • Discounts: ' . DB::table('discounts')->count());
        $this->command->line('   • Discountables: ' . DB::table('discountables')->count());
        $this->command->line('   • User Limits: ' . DB::table('discount_user_limits')->count());
        $this->command->line('   • Usages: ' . DB::table('discount_usages')->count());
        
        // نمایش تخفیف‌های هر کمپین
        $this->command->newLine();
        $this->command->info('📋 Discounts per Campaign:');
        
        $campaigns = DB::table('discount_campaigns')->get();
        foreach ($campaigns as $campaign) {
            $count = DB::table('discounts')
                ->where('campaign_id', $campaign->id)
                ->count();
            $this->command->line("   {$campaign->icon} {$campaign->name}: {$count} discounts");
        }
        
        $standalone = DB::table('discounts')
            ->whereNull('campaign_id')
            ->count();
        $this->command->line("   📦 Standalone (no campaign): {$standalone} discounts");
    }
}