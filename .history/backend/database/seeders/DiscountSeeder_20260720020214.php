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

        // غیرفعال کردن محدودیت‌های کلید خارجی
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // پاک کردن جدول‌های مرتبط
        DB::table('discount_usages')->truncate();
        DB::table('discount_user_limits')->truncate();
        DB::table('discountables')->truncate();
        DB::table('discounts')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // دریافت مدل‌های موجود
        $products = DB::table('products')->pluck('id')->toArray();
        $categories = DB::table('categories')->pluck('id')->toArray();
        $brands = DB::table('brands')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($products) || empty($categories) || empty($brands)) {
            $this->command->warn('⚠️ No products, categories, or brands found. Please seed them first.');
            return;
        }

        // ================================================================
        // ۱. تعریف تخفیف‌ها
        // ================================================================
        $discounts = [
            // ===== تخفیف‌های درصدی =====
            [
                'name' => 'تخفیف ۱۰٪ ویژه کاربران جدید',
                'type' => 'ر',
                'value' => 10,
                'stackable' => false,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(10),
                'quantity_limit' => 100,
                'used_quantity' => 12,
                'priority' => 1,
                'is_flash_sale' => false,
            ],
            [
                'name' => 'تخفیف ۲۰٪ بهارانه',
                'type' => 'percent',
                'value' => 20,
                'stackable' => true,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(20),
                'quantity_limit' => 50,
                'used_quantity' => 5,
                'priority' => 2,
                'is_flash_sale' => false,
            ],
            [
                'name' => 'تخفیف ۳۰٪ فلش‌سیل',
                'type' => 'percent',
                'value' => 30,
                'stackable' => false,
                'starts_at' => now()->addHours(2),
                'ends_at' => now()->addHours(8),
                'quantity_limit' => 20,
                'used_quantity' => 0,
                'priority' => 5,
                'is_flash_sale' => true,
            ],
            [
                'name' => 'تخفیف ۵۰٪ ویژه اعضای VIP',
                'type' => 'percent',
                'value' => 50,
                'stackable' => false,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(5),
                'quantity_limit' => 10,
                'used_quantity' => 3,
                'priority' => 4,
                'is_flash_sale' => false,
            ],
            [
                'name' => 'تخفیف ۱۵٪ کد تخفیف دوستانه',
                'type' => 'percent',
                'value' => 15,
                'stackable' => true,
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(3),
                'quantity_limit' => 200,
                'used_quantity' => 45,
                'priority' => 1,
                'is_flash_sale' => false,
            ],

            // ===== تخفیف‌های مبلغ ثابت (به دلار) =====
            [
                'name' => '۵ دلار تخفیف برای خرید اول',
                'type' => 'fixed',
                'value' => 5.00,
                'stackable' => false,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(15),
                'quantity_limit' => 150,
                'used_quantity' => 28,
                'priority' => 1,
                'is_flash_sale' => false,
            ],
            [
                'name' => '۱۰ دلار تخفیف ویژه محصولات دیجیتال',
                'type' => 'fixed',
                'value' => 10.00,
                'stackable' => true,
                'starts_at' => now()->subDays(3),
                'ends_at' => now()->addDays(7),
                'quantity_limit' => 80,
                'used_quantity' => 10,
                'priority' => 2,
                'is_flash_sale' => false,
            ],
            [
                'name' => '۲۰ دلار تخفیف فلش‌سیل شبانه',
                'type' => 'fixed',
                'value' => 20.00,
                'stackable' => false,
                'starts_at' => now()->addHours(5),
                'ends_at' => now()->addHours(10),
                'quantity_limit' => 30,
                'used_quantity' => 0,
                'priority' => 5,
                'is_flash_sale' => true,
            ],
            [
                'name' => '۵۰ دلار تخفیف ویژه خرید بالای ۲۰۰ دلار',
                'type' => 'fixed',
                'value' => 50.00,
                'stackable' => false,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(14),
                'quantity_limit' => 25,
                'used_quantity' => 4,
                'priority' => 3,
                'is_flash_sale' => false,
            ],
            [
                'name' => '۱۵ دلار تخفیف کد دعوت',
                'type' => 'fixed',
                'value' => 15.00,
                'stackable' => true,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(30),
                'quantity_limit' => 100,
                'used_quantity' => 7,
                'priority' => 1,
                'is_flash_sale' => false,
            ],
        ];

        // ================================================================
        // ۲. ذخیره تخفیف‌ها
        // ================================================================
        $discountIds = [];

        foreach ($discounts as $index => $discountData) {
            // ensure starts_at and ends_at are Carbon instances
            $discountData['starts_at'] = $discountData['starts_at'] instanceof Carbon
                ? $discountData['starts_at']
                : Carbon::parse($discountData['starts_at']);
            $discountData['ends_at'] = $discountData['ends_at'] instanceof Carbon
                ? $discountData['ends_at']
                : Carbon::parse($discountData['ends_at']);

            $id = DB::table('discounts')->insertGetId([
                'name' => $discountData['name'],
                'type' => $discountData['type'],
                'value' => $discountData['value'],
                'stackable' => $discountData['stackable'],
                'starts_at' => $discountData['starts_at'],
                'ends_at' => $discountData['ends_at'],
                'quantity_limit' => $discountData['quantity_limit'],
                'used_quantity' => $discountData['used_quantity'],
                'priority' => $discountData['priority'],
                'is_flash_sale' => $discountData['is_flash_sale'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $discountIds[$index] = $id;
            $this->command->line("  ✅ Created discount: {$discountData['name']}");
        }

        // ================================================================
        // ۳. اتصال تخفیف‌ها به محصولات، دسته‌بندی‌ها و برندها (discountables)
        // ================================================================
        $this->command->info('🔗 Attaching discounts to products, categories, and brands...');

        $attachedCount = 0;

        foreach ($discountIds as $discountId) {
            // هر تخفیف به ۲-۴ آیتم متصل میشه
            $targetCount = rand(2, 4);
            $targets = [];

            // انتخاب تصادفی از بین محصولات، دسته‌بندی‌ها و برندها
            for ($i = 0; $i < $targetCount; $i++) {
                $type = rand(1, 3);
                $targetId = null;
                $targetType = null;

                switch ($type) {
                    case 1: // محصول
                        if (!empty($products)) {
                            $targetId = $products[array_rand($products)];
                            $targetType = 'App\Models\Product';
                        }
                        break;
                    case 2: // دسته‌بندی
                        if (!empty($categories)) {
                            $targetId = $categories[array_rand($categories)];
                            $targetType = 'App\Models\Category';
                        }
                        break;
                    case 3: // برند
                        if (!empty($brands)) {
                            $targetId = $brands[array_rand($brands)];
                            $targetType = 'App\Models\Brand';
                        }
                        break;
                }

                if ($targetId && $targetType) {
                    // جلوگیری از تکراری شدن
                    $key = $targetType . '|' . $targetId;
                    if (!in_array($key, $targets)) {
                        $targets[] = $key;
                        DB::table('discountables')->insert([
                            'discount_id' => $discountId,
                            'discountable_type' => $targetType,
                            'discountable_id' => $targetId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $attachedCount++;
                    }
                }
            }
        }

        $this->command->info("✅ {$attachedCount} discountable relationships created!");

        // ================================================================
        // ۴. محدودیت کاربری (discount_user_limits)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('👤 Creating user limits for discounts...');

            $limitCount = 0;
            foreach ($discountIds as $discountId) {
                // ۵۰٪ تخفیف‌ها محدودیت کاربری دارن
                if (rand(0, 1) === 0) {
                    $randomUsers = array_rand(array_flip($users), rand(3, 8));
                    $randomUsers = is_array($randomUsers) ? $randomUsers : [$randomUsers];

                    foreach ($randomUsers as $userId) {
                        DB::table('discount_user_limits')->insert([
                            'discount_id' => $discountId,
                            'user_id' => $userId,
                            'max_quantity' => rand(1, 3),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $limitCount++;
                    }
                }
            }

            $this->command->info("✅ {$limitCount} user limits created!");
        }

        // ================================================================
        // ۵. ثبت استفاده (discount_usages)
        // ================================================================
        if (!empty($users)) {
            $this->command->info('📝 Creating discount usages...');

            $usageCount = 0;
            foreach ($discountIds as $discountId) {
                // فقط تخفیف‌هایی که used_quantity > 0 دارن، استفاده ثبت میشه
                $discount = DB::table('discounts')->where('id', $discountId)->first();
                if ($discount && $discount->used_quantity > 0) {
                    $usageQuantity = min($discount->used_quantity, rand(1, 10));
                    $randomUsers = array_rand(array_flip($users), min($usageQuantity, count($users)));
                    $randomUsers = is_array($randomUsers) ? $randomUsers : [$randomUsers];

                    foreach ($randomUsers as $userId) {
                        DB::table('discount_usages')->insert([
                            'discount_id' => $discountId,
                            'user_id' => $userId,
                            'quantity' => rand(1, 2),
                            'created_at' => now()->subDays(rand(1, 30)),
                            'updated_at' => now()->subDays(rand(0, 5)),
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