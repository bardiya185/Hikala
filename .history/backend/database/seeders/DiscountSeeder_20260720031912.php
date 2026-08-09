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

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('discount_usages')->truncate();
        DB::table('discount_user_limits')->truncate();
        DB::table('discountables')->truncate();
        DB::table('discounts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $allProducts = DB::table('products')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($allProducts)) {
            $this->command->warn('⚠️ No products found. Please seed products first.');
            return;
        }

        // تعداد محصولات برای هر تخفیف (مثلاً ۴ تا برای هر کدام)
        $productsPerDiscount = 4;
        shuffle($allProducts);

        // ================================================================
        // ۱. تعریف تخفیف‌ها
        // ================================================================
        $discounts = [
            [
                'name' => 'Amazing Discount',
                'type' => 'percent',
                'value' => 60,
                'stackable' => false,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(7),
                'quantity_limit' => 50,
                'used_quantity' => 5,
                'priority' => 1,
                'is_flash_sale' => false,
            ],
            [
                'name' => 'Special Discount',
                'type' => 'fixed',
                'value' => 10.00,
                'stackable' => false,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(14),
                'quantity_limit' => 30,
                'used_quantity' => 2,
                'priority' => 2,
                'is_flash_sale' => false,
            ],
        ];

        // ================================================================
        // ۲. ذخیره تخفیف‌ها و اختصاص محصولات جداگانه
        // ================================================================
        $discountIds = [];

        foreach ($discounts as $index => $discount) {
            $id = DB::table('discounts')->insertGetId([
                'name' => $discount['name'],
                'type' => $discount['type'],
                'value' => $discount['value'],
                'stackable' => $discount['stackable'],
                'starts_at' => $discount['starts_at'],
                'ends_at' => $discount['ends_at'],
                'quantity_limit' => $discount['quantity_limit'],
                'used_quantity' => $discount['used_quantity'],
                'priority' => $discount['priority'],
                'is_flash_sale' => $discount['is_flash_sale'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $discountIds[] = $id;
            $this->command->line("  ✅ Created discount: {$discount['name']}");
        }

        // ================================================================
        // ۳. اتصال تخفیف‌ها به محصولات (هر تخفیف به یک دسته جدا)
        // ================================================================
        $this->command->info('🔗 Attaching discounts to separate products...');

        $attachedCount = 0;

        // جلوگیری از تداخل محصولات بین دو تخفیف
        $usedProductIds = [];

        foreach ($discountIds as $index => $discountId) {
            // محصولات باقی‌مونده که توی تخفیف قبلی استفاده نشدن
            $availableProducts = array_diff($allProducts, $usedProductIds);
            shuffle($availableProducts);

            // تعداد محصولات برای این تخفیف (حداکثر ۴ عدد)
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

            // علامت‌گذاری محصولات استفاده‌شده برای جلوگیری از تداخل
            $usedProductIds = array_merge($usedProductIds, $selectedForThisDiscount);
        }

        $this->command->info("✅ {$attachedCount} discountable relationships created!");

        // ================================================================
        // ۴. محدودیت کاربری (اختیاری)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('👤 Creating user limits...');

            $limitCount = 0;
            foreach ($discountIds as $discountId) {
                $selectedUsers = array_slice($users, 0, rand(3, 5));

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
        // ۵. ثبت استفاده (برای تخفیف‌هایی که used_quantity > 0 دارن)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('📝 Creating discount usages...');

            $usageCount = 0;
            foreach ($discountIds as $discountId) {
                $discount = DB::table('discounts')->where('id', $discountId)->first();

                if ($discount && $discount->used_quantity > 0) {
                    $randomUsers = array_slice($users, 0, rand(2, 4));

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
        // ۶. گزارش نهایی
        // ================================================================
        $this->command->info('✅ Discount seeding completed!');
        $this->command->info('📊 Total discounts: ' . DB::table('discounts')->count());
        $this->command->info('📊 Total discountables: ' . DB::table('discountables')->count());
        $this->command->info('📊 Total user limits: ' . DB::table('discount_user_limits')->count());
        $this->command->info('📊 Total usages: ' . DB::table('discount_usages')->count());
    }
}