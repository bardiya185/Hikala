<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Creating attributes and values...');

        // ========== 1. ایجاد ویژگی‌ها ==========
        $attributes = [
            [
                'name' => 'رنگ',
                'slug' => 'color',
                'type' => 'select',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 1,
                'is_required' => 0,
                'sort_order' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'حافظه داخلی',
                'slug' => 'storage',
                'type' => 'select',
                'unit' => 'GB',
                'is_filterable' => 1,
                'is_variant' => 1,
                'is_required' => 0,
                'sort_order' => 2,
                'is_active' => 1,
            ],
            [
                'name' => 'رم',
                'slug' => 'ram',
                'type' => 'select',
                'unit' => 'GB',
                'is_filterable' => 1,
                'is_variant' => 1,
                'is_required' => 0,
                'sort_order' => 3,
                'is_active' => 1,
            ],
            [
                'name' => 'سایز',
                'slug' => 'size',
                'type' => 'select',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 1,
                'is_required' => 0,
                'sort_order' => 4,
                'is_active' => 1,
            ],
            [
                'name' => 'پردازنده',
                'slug' => 'processor',
                'type' => 'select',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 0,
                'is_required' => 0,
                'sort_order' => 5,
                'is_active' => 1,
            ],
            [
                'name' => 'ظرفیت باتری',
                'slug' => 'battery',
                'type' => 'select',
                'unit' => 'mAh',
                'is_filterable' => 1,
                'is_variant' => 0,
                'is_required' => 0,
                'sort_order' => 6,
                'is_active' => 1,
            ],
            [
                'name' => 'جنس',
                'slug' => 'material',
                'type' => 'select',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 1,
                'is_required' => 0,
                'sort_order' => 7,
                'is_active' => 1,
            ],
            [
                'name' => 'نوع اتصال',
                'slug' => 'connectivity',
                'type' => 'select',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 0,
                'is_required' => 0,
                'sort_order' => 8,
                'is_active' => 1,
            ],
            [
                'name' => 'نویسنده',
                'slug' => 'author',
                'type' => 'text',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 0,
                'is_required' => 0,
                'sort_order' => 9,
                'is_active' => 1,
            ],
            [
                'name' => 'ناشر',
                'slug' => 'publisher',
                'type' => 'text',
                'unit' => null,
                'is_filterable' => 1,
                'is_variant' => 0,
                'is_required' => 0,
                'sort_order' => 10,
                'is_active' => 1,
            ],
        ];

        $attributeIds = [];
        foreach ($attributes as $attr) {
            $id = DB::table('attributes')->insertGetId([
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'type' => $attr['type'],
                'unit' => $attr['unit'],
                'is_filterable' => $attr['is_filterable'],
                'is_variant' => $attr['is_variant'],
                'is_required' => $attr['is_required'],
                'sort_order' => $attr['sort_order'],
                'is_active' => $attr['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $attributeIds[$attr['slug']] = $id;
        }

        // ========== 2. ایجاد مقادیر ویژگی‌ها ==========
        $attributeValues = [
            'color' => [
                ['value' => 'مشکی', 'color_code' => '#000000'],
                ['value' => 'سفید', 'color_code' => '#FFFFFF'],
                ['value' => 'قرمز', 'color_code' => '#FF0000'],
                ['value' => 'آبی', 'color_code' => '#0000FF'],
                ['value' => 'سبز', 'color_code' => '#00FF00'],
                ['value' => 'طلایی', 'color_code' => '#FFD700'],
                ['value' => 'نقره‌ای', 'color_code' => '#C0C0C0'],
                ['value' => 'صورتی', 'color_code' => '#FFC0CB'],
            ],
            'storage' => [
                ['value' => '64GB'],
                ['value' => '128GB'],
                ['value' => '256GB'],
                ['value' => '512GB'],
                ['value' => '1TB'],
            ],
            'ram' => [
                ['value' => '4GB'],
                ['value' => '6GB'],
                ['value' => '8GB'],
                ['value' => '12GB'],
                ['value' => '16GB'],
                ['value' => '32GB'],
            ],
            'size' => [
                ['value' => 'S'],
                ['value' => 'M'],
                ['value' => 'L'],
                ['value' => 'XL'],
                ['value' => 'XXL'],
                ['value' => '40'],
                ['value' => '41'],
                ['value' => '42'],
                ['value' => '43'],
                ['value' => '44'],
                ['value' => '45'],
            ],
            'processor' => [
                ['value' => 'Intel Core i3'],
                ['value' => 'Intel Core i5'],
                ['value' => 'Intel Core i7'],
                ['value' => 'Intel Core i9'],
                ['value' => 'Apple M1'],
                ['value' => 'Apple M2'],
                ['value' => 'Apple M3'],
                ['value' => 'Snapdragon 8 Gen 1'],
                ['value' => 'Snapdragon 8 Gen 2'],
            ],
            'battery' => [
                ['value' => '2000mAh'],
                ['value' => '3000mAh'],
                ['value' => '4000mAh'],
                ['value' => '5000mAh'],
                ['value' => '6000mAh'],
                ['value' => '8000mAh'],
                ['value' => '10000mAh'],
            ],
            'material' => [
                ['value' => 'چوب'],
                ['value' => 'فلز'],
                ['value' => 'پلاستیک'],
                ['value' => 'شیشه'],
                ['value' => 'چرم'],
                ['value' => 'پارچه'],
                ['value' => 'آلومینیوم'],
            ],
            'connectivity' => [
                ['value' => 'USB-C'],
                ['value' => 'Lightning'],
                ['value' => 'Micro-USB'],
                ['value' => 'HDMI'],
                ['value' => 'AUX'],
                ['value' => 'Bluetooth 5.0'],
                ['value' => 'Bluetooth 5.3'],
                ['value' => 'WiFi 6'],
            ],
            'author' => [
                ['value' => 'جورج اورول'],
                ['value' => 'فرانتس کافکا'],
                ['value' => 'صادق هدایت'],
                ['value' => 'سیمین بهبهانی'],
                ['value' => 'فروغ فرخزاد'],
            ],
            'publisher' => [
                ['value' => 'نشر چشمه'],
                ['value' => 'نشر نی'],
                ['value' => 'نشر ققنوس'],
                ['value' => 'نشر مرکز'],
                ['value' => 'نشر کتاب پارسه'],
            ],
        ];

        foreach ($attributeValues as $attrSlug => $values) {
            $attrId = $attributeIds[$attrSlug] ?? null;
            if (!$attrId) continue;

            $sortOrder = 1;
            foreach ($values as $item) {
                DB::table('attribute_values')->insert([
                    'attribute_id' => $attrId,
                    'value' => $item['value'],
                    'slug' => Str::slug($item['value']),
                    'color_code' => $item['color_code'] ?? null,
                    'image' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sortOrder++;
            }
        }

        // ========== 3. ارتباط ویژگی‌ها با دسته‌بندی‌ها (category_attributes) ==========
        $this->command->info('🔗 Linking attributes to categories...');

        // دریافت همه دسته‌بندی‌ها و ویژگی‌ها
        $categories = DB::table('categories')->get();
        $allAttributes = DB::table('attributes')->get();

        $categoryAttributeMap = [
            'موبایل' => ['color', 'storage', 'ram', 'processor', 'battery'],
            'لب تاب' => ['color', 'storage', 'ram', 'processor', 'connectivity'],
            'کالای دیجیتال' => ['color', 'battery', 'connectivity', 'material'],
            'خانه و آشپزخانه' => ['color', 'material', 'size'],
            'لوازم خانگی برقی' => ['color', 'material', 'battery'],
            'آرایشی بهداشتی' => ['color', 'material'],
            'مد و پوشاک' => ['color', 'size', 'material'],
            'طلا و نقره' => ['color', 'material'],
            'خودرو و موتورسیکلت' => ['color', 'material'],
            'سلامت و پزشکی' => ['material'],
            'ابزارآلات و تجهیزات' => ['color', 'material', 'size'],
            'کتاب و هنر' => ['author', 'publisher'],
            'ورزش و سفر' => ['color', 'size', 'material'],
            'کارت هدیه' => ['material'],
        ];

        foreach ($categories as $category) {
            $attrSlugs = $categoryAttributeMap[$category->name] ?? ['color', 'material'];
            
            foreach ($attrSlugs as $slug) {
                $attribute = $allAttributes->firstWhere('slug', $slug);
                if ($attribute) {
                    // بررسی اینکه قبلاً اضافه نشده باشه
                    $exists = DB::table('category_attributes')
                        ->where('category_id', $category->id)
                        ->where('attribute_id', $attribute->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('category_attributes')->insert([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'is_required' => 0,
                            'sort_order' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // ========== 4. آپدیت محصولات با ویژگی‌های تصادفی ==========
        $this->command->info('🔄 Updating products with random attributes...');

        $products = DB::table('products')->get();
        $allAttributeValues = DB::table('attribute_values')->get();

        foreach ($products as $product) {
            // دریافت دسته‌بندی محصول
            $categoryProduct = DB::table('category_product')
                ->where('product_id', $product->id)
                ->first();

            if (!$categoryProduct) continue;

            // دریافت ویژگی‌های مربوط به این دسته‌بندی
            $categoryAttrs = DB::table('category_attributes')
                ->where('category_id', $categoryProduct->category_id)
                ->pluck('attribute_id')
                ->toArray();

            if (empty($categoryAttrs)) continue;

            // دریافت تنوع‌های محصول
            $variants = DB::table('product_variants')
                ->where('product_id', $product->id)
                ->get();

            foreach ($variants as $variant) {
                // انتخاب ۲ تا ۳ ویژگی تصادفی برای هر تنوع
                $selectedAttrs = array_rand(array_flip($categoryAttrs), rand(2, 3));
                if (!is_array($selectedAttrs)) {
                    $selectedAttrs = [$selectedAttrs];
                }

                foreach ($selectedAttrs as $attrId) {
                    // انتخاب یک مقدار تصادفی برای این ویژگی
                    $randomValue = $allAttributeValues
                        ->where('attribute_id', $attrId)
                        ->random();

                    if ($randomValue) {
                        // بررسی اینکه قبلاً اضافه نشده باشه
                        $exists = DB::table('product_variant_attribute_values')
                            ->where('product_variant_id', $variant->id)
                            ->where('attribute_value_id', $randomValue->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('product_variant_attribute_values')->insert([
                                'product_variant_id' => $variant->id,
                                'attribute_value_id' => $randomValue->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        // ========== 5. گزارش نهایی ==========
        $attributeCount = DB::table('attributes')->count();
        $valueCount = DB::table('attribute_values')->count();
        $relationCount = DB::table('product_variant_attribute_values')->count();
        $categoryAttrCount = DB::table('category_attributes')->count();

        $this->command->info('✅ ' . $attributeCount . ' attributes created!');
        $this->command->info('✅ ' . $valueCount . ' attribute values created!');
        $this->command->info('✅ ' . $categoryAttrCount . ' category-attribute relations created!');
        $this->command->info('✅ ' . $relationCount . ' product variant attribute values created!');
        $this->command->info('🎯 All operations completed successfully!');
    }
}