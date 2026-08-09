<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating discounts...');

        // غیرفعال کردن محدودیت‌ها
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('discount_usages')->truncate();
        DB::table('discount_user_limits')->truncate();
        DB::table('discountables')->truncate();
        DB::table('discounts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // دریافت دسته‌بندی‌ها
        $categories = DB::table('categories')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($categories)) {
            $this->command->warn('⚠️ No categories found. Please seed categories first.');
            return;
        }

        // ================================================================
        // ۱. تعریف ۲ تخفیف (انگلیسی)
        // ================================================================
        $discounts = [
            [
                'name' => 'Amazing Discount',
                'type' => 'percent',
                'value' => 20, // ۲۰٪ تخفیف
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
                'value' => 10.00, // ۱۰ دلار تخفیف
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
        // ۲. ذخیره تخفیف‌ها
        // ================================================================
        $discountIds = [];

        foreach ($discounts as $discount) {
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
        // ۳. اتصال تخفیف‌ها به همه دسته‌بندی‌ها (discountables)
        // ================================================================
        $this->command->info('🔗 Attaching discounts to ALL categories...');

        $attachedCount = 0;

        foreach ($discountIds as $discountId) {
            foreach ($categories as $categoryId) {
                DB::table('discountables')->insert([
                    'discount_id' => $discountId,
                    'discountable_type' => 'App\Models\Category',
                    'discountable_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $attachedCount++;
            }
        }

        $this->command->info("✅ {$attachedCount} discountable relationships created!");

        // ================================================================
        // ۴. محدودیت کاربری (فقط برای کاربران موجود)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('👤 Creating user limits...');

            $limitCount = 0;
            foreach ($discountIds as $discountId) {
                // فقط برای ۳-۵ کاربر اول محدودیت بذار
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
        // ۵. ثبت استفاده (فقط برای تخفیف‌هایی که used_quantity > 0 دارن)
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