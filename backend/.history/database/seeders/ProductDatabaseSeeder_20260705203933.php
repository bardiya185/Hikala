<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 شروع ایجاد محصولات...');

        // ========== 1. ایجاد دسته‌بندی‌ها ==========
        $categories = [
            ['name' => 'موبایل', 'slug' => 'mobile', 'sort_order' => 1],
            ['name' => 'لب تاب', 'slug' => 'laptop', 'sort_order' => 2],
            ['name' => 'کالای دیجیتال', 'slug' => 'digital', 'sort_order' => 3],
            ['name' => 'خانه و آشپزخانه', 'slug' => 'home', 'sort_order' => 4],
            ['name' => 'لوازم خانگی برقی', 'slug' => 'home-appliances', 'sort_order' => 5],
            ['name' => 'آرایشی بهداشتی', 'slug' => 'beauty', 'sort_order' => 6],
            ['name' => 'مد و پوشاک', 'slug' => 'fashion', 'sort_order' => 7],
            ['name' => 'طلا و نقره', 'slug' => 'gold', 'sort_order' => 8],
            ['name' => 'خودرو و موتورسیکلت', 'slug' => 'vehicle', 'sort_order' => 9],
            ['name' => 'سلامت و پزشکی', 'slug' => 'health', 'sort_order' => 10],
            ['name' => 'ابزارآلات و تجهیزات', 'slug' => 'tools', 'sort_order' => 11],
            ['name' => 'کتاب و هنر', 'slug' => 'books', 'sort_order' => 12],
            ['name' => 'ورزش و سفر', 'slug' => 'sport', 'sort_order' => 13],
            ['name' => 'کارت هدیه', 'slug' => 'gift', 'sort_order' => 14],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'sort_order' => $cat['sort_order'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // ========== 2. ایجاد برندها ==========
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'LG', 'slug' => 'lg'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // ========== 3. ایجاد ویژگی‌ها ==========
        $attributes = [
            ['name' => 'رنگ', 'slug' => 'color', 'is_variant' => 1],
            ['name' => 'حافظه', 'slug' => 'storage', 'is_variant' => 1],
            ['name' => 'رم', 'slug' => 'ram', 'is_variant' => 1],
            ['name' => 'سایز', 'slug' => 'size', 'is_variant' => 1],
        ];

        $attributeIds = [];
        foreach ($attributes as $attr) {
            $id = DB::table('attributes')->insertGetId([
                'name' => $attr['name'],
                'slug' => $attr['slug'],
                'is_variant' => $attr['is_variant'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $attributeIds[$attr['slug']] = $id;
        }

        // ========== 4. ایجاد مقادیر ویژگی‌ها ==========
        $attributeValues = [
            'color' => ['قرمز', 'آبی', 'مشکی', 'سفید', 'طلایی'],
            'storage' => ['64GB', '128GB', '256GB', '512GB'],
            'ram' => ['4GB', '6GB', '8GB', '12GB'],
            'size' => ['S', 'M', 'L', 'XL'],
        ];

        $valueIds = [];
        foreach ($attributeValues as $attrSlug => $values) {
            $attrId = $attributeIds[$attrSlug];
            foreach ($values as $value) {
                $id = DB::table('attribute_values')->insertGetId([
                    'attribute_id' => $attrId,
                    'value' => $value,
                    'slug' => Str::slug($value),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $valueIds[$attrSlug][] = $id;
            }
        }

        // ========== 5. ایجاد ۱۰۰ محصول ==========
        $productTitles = [
            'iPhone 15 Pro', 'Samsung Galaxy S24', 'Xiaomi 14', 'MacBook Pro',
            'Dell XPS', 'HP Spectre', 'Sony TV', 'LG Refrigerator',
            'Nike Air Max', 'Adidas Ultraboost', 'کرم ضد آفتاب', 'عطر دیور',
            'کتاب رمان', 'کیف چرمی', 'ساعت هوشمند', 'هدفون بی‌سیم'
        ];

        for ($i = 1; $i <= 100; $i++) {
            // انتخاب تصادفی
            $brand = DB::table('brands')->inRandomOrder()->first();
            $category = DB::table('categories')->inRandomOrder()->first();
            $title = $productTitles[array_rand($productTitles)] . ' ' . Str::random(4);
            
            // ایجاد محصول
            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'short_description' => 'توضیح مختصر برای ' . $title,
                'description' => '<p>توضیحات کامل برای محصول ' . $title . '</p>',
                'status' => 'active',
                'meta_title' => $title,
                'meta_keywords' => $title . ', خرید, فروشگاه',
                'meta_description' => 'خرید ' . $title . ' با بهترین قیمت',
                'view_count' => rand(0, 1000),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ارتباط با دسته‌بندی
            DB::table('category_product')->insert([
                'category_id' => $category->id,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ایجاد ۲ تا ۳ تنوع برای هر محصول
            $variantCount = rand(2, 3);
            for ($v = 1; $v <= $variantCount; $v++) {
                $price = rand(100000, 5000000);
                $salePrice = $price * rand(7, 9) / 10; // تخفیف 10-30%

                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(3),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'price' => $price,
                    'sale_price' => round($salePrice, -2),
                    'stock' => rand(0, 50),
                    'weight' => rand(100, 1000) / 100,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // اختصاص ۲ ویژگی به هر تنوع
                $selectedAttrs = array_rand($attributeIds, 2);
                foreach ($selectedAttrs as $attrSlug) {
                    $attrId = $attributeIds[$attrSlug];
                    $randomValue = DB::table('attribute_values')
                        ->where('attribute_id', $attrId)
                        ->inRandomOrder()
                        ->first();

                    if ($randomValue) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_variant_id' => $variantId,
                            'attribute_value_id' => $randomValue->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }

        // ========== 6. گزارش نهایی ==========
        $productCount = DB::table('products')->count();
        $variantCount = DB::table('product_variants')->count();
        $categoryCount = DB::table('categories')->count();
        
        $this->command->info('✅ ' . $productCount . ' محصول با ' . $variantCount . ' تنوع ایجاد شد!');
        $this->command->info('📂 ' . $categoryCount . ' دسته‌بندی ایجاد شد.');
        $this->command->info('🎯 تمام عملیات با موفقیت انجام شد!');
    }
}