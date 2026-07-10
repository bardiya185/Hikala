<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimpleProductSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting product creation...');

        // ========== 1. Create Categories ==========
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

        // ========== 2. Create Brands ==========
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

        // ========== 3. Define Products ==========
        $productsData = [
            // ===== موبایل =====
            ['title' => 'iPhone 15 Pro', 'category' => 'موبایل', 'brand' => 'Apple', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 200],
            ['title' => 'iPhone 15 Pro Max', 'category' => 'موبایل', 'brand' => 'Apple', 'min_price' => 30000000, 'max_price' => 40000000, 'weight' => 220],
            ['title' => 'Samsung Galaxy S24', 'category' => 'موبایل', 'brand' => 'Samsung', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 190],
            ['title' => 'Samsung Galaxy S24 Ultra', 'category' => 'موبایل', 'brand' => 'Samsung', 'min_price' => 28000000, 'max_price' => 38000000, 'weight' => 210],
            ['title' => 'Xiaomi 14', 'category' => 'موبایل', 'brand' => 'Xiaomi', 'min_price' => 15000000, 'max_price' => 22000000, 'weight' => 180],
            ['title' => 'Xiaomi 14 Pro', 'category' => 'موبایل', 'brand' => 'Xiaomi', 'min_price' => 18000000, 'max_price' => 25000000, 'weight' => 190],
            
            // ===== لب تاب =====
            ['title' => 'MacBook Pro M3', 'category' => 'لب تاب', 'brand' => 'Apple', 'min_price' => 40000000, 'max_price' => 60000000, 'weight' => 1500],
            ['title' => 'Dell XPS 13', 'category' => 'لب تاب', 'brand' => 'Dell', 'min_price' => 25000000, 'max_price' => 35000000, 'weight' => 1200],
            ['title' => 'HP Spectre x360', 'category' => 'لب تاب', 'brand' => 'HP', 'min_price' => 22000000, 'max_price' => 32000000, 'weight' => 1300],
            ['title' => 'Lenovo ThinkPad X1', 'category' => 'لب تاب', 'brand' => 'Lenovo', 'min_price' => 20000000, 'max_price' => 30000000, 'weight' => 1100],
            
            // ===== کالای دیجیتال =====
            ['title' => 'Sony WH-1000XM5', 'category' => 'کالای دیجیتال', 'brand' => 'Sony', 'min_price' => 8000000, 'max_price' => 12000000, 'weight' => 250],
            ['title' => 'Apple Watch Ultra 2', 'category' => 'کالای دیجیتال', 'brand' => 'Apple', 'min_price' => 15000000, 'max_price' => 20000000, 'weight' => 60],
            ['title' => 'Anker Power Bank 20000', 'category' => 'کالای دیجیتال', 'brand' => 'LG', 'min_price' => 2000000, 'max_price' => 4000000, 'weight' => 400],
            ['title' => 'JBL Charge 5', 'category' => 'کالای دیجیتال', 'brand' => 'Sony', 'min_price' => 3000000, 'max_price' => 5000000, 'weight' => 450],
            
            // ===== خانه و آشپزخانه =====
            ['title' => 'مبل سلطنتی', 'category' => 'خانه و آشپزخانه', 'brand' => 'LG', 'min_price' => 15000000, 'max_price' => 25000000, 'weight' => 25000],
            ['title' => 'چراغ لوستر طلایی', 'category' => 'خانه و آشپزخانه', 'brand' => 'LG', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 5000],
            ['title' => 'قالیچه نفیس', 'category' => 'خانه و آشپزخانه', 'brand' => 'LG', 'min_price' => 3000000, 'max_price' => 8000000, 'weight' => 3000],
            
            // ===== لوازم خانگی برقی =====
            ['title' => 'یخچال ساید بای ساید', 'category' => 'لوازم خانگی برقی', 'brand' => 'LG', 'min_price' => 25000000, 'max_price' => 40000000, 'weight' => 80000],
            ['title' => 'ماشین لباسشویی 9 کیلو', 'category' => 'لوازم خانگی برقی', 'brand' => 'LG', 'min_price' => 12000000, 'max_price' => 20000000, 'weight' => 65000],
            ['title' => 'جاروبرقی رباتیک', 'category' => 'لوازم خانگی برقی', 'brand' => 'LG', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 3000],
            ['title' => 'ماکروویو هوشمند', 'category' => 'لوازم خانگی برقی', 'brand' => 'LG', 'min_price' => 6000000, 'max_price' => 12000000, 'weight' => 15000],
            
            // ===== آرایشی بهداشتی =====
            ['title' => 'کرم ضد چروک', 'category' => 'آرایشی بهداشتی', 'brand' => 'LG', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 50],
            ['title' => 'عطر دیور', 'category' => 'آرایشی بهداشتی', 'brand' => 'LG', 'min_price' => 3000000, 'max_price' => 6000000, 'weight' => 100],
            ['title' => 'سرم ویتامین C', 'category' => 'آرایشی بهداشتی', 'brand' => 'LG', 'min_price' => 800000, 'max_price' => 1500000, 'weight' => 30],
            
            // ===== مد و پوشاک =====
            ['title' => 'کت و شلوار مردانه', 'category' => 'مد و پوشاک', 'brand' => 'Nike', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 800],
            ['title' => 'کفش اسپرت نایک', 'category' => 'مد و پوشاک', 'brand' => 'Nike', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 400],
            ['title' => 'کیف چرمی', 'category' => 'مد و پوشاک', 'brand' => 'Adidas', 'min_price' => 1200000, 'max_price' => 2500000, 'weight' => 500],
            ['title' => 'تیشرکت پنبه‌ای', 'category' => 'مد و پوشاک', 'brand' => 'Nike', 'min_price' => 300000, 'max_price' => 800000, 'weight' => 150],
            
            // ===== طلا و نقره =====
            ['title' => 'گردنبند طلا 24K', 'category' => 'طلا و نقره', 'brand' => 'LG', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 10],
            ['title' => 'ساعت رولکس', 'category' => 'طلا و نقره', 'brand' => 'LG', 'min_price' => 8000000, 'max_price' => 15000000, 'weight' => 150],
            
            // ===== خودرو و موتورسیکلت =====
            ['title' => 'رینگ اسپرت', 'category' => 'خودرو و موتورسیکلت', 'brand' => 'LG', 'min_price' => 5000000, 'max_price' => 10000000, 'weight' => 5000],
            ['title' => 'سیستم صوتی خودرو', 'category' => 'خودرو و موتورسیکلت', 'brand' => 'Sony', 'min_price' => 3000000, 'max_price' => 8000000, 'weight' => 2000],
            
            // ===== سلامت و پزشکی =====
            ['title' => 'دستگاه فشار خون', 'category' => 'سلامت و پزشکی', 'brand' => 'LG', 'min_price' => 1000000, 'max_price' => 3000000, 'weight' => 300],
            ['title' => 'کمربند طبی', 'category' => 'سلامت و پزشکی', 'brand' => 'LG', 'min_price' => 500000, 'max_price' => 1500000, 'weight' => 200],
            ['title' => 'مسواک برقی', 'category' => 'سلامت و پزشکی', 'brand' => 'LG', 'min_price' => 800000, 'max_price' => 2000000, 'weight' => 100],
            
            // ===== ابزارآلات =====
            ['title' => 'دربیل برقی', 'category' => 'ابزارآلات و تجهیزات', 'brand' => 'LG', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 3000],
            ['title' => 'جعبه ابزار حرفه‌ای', 'category' => 'ابزارآلات و تجهیزات', 'brand' => 'LG', 'min_price' => 1000000, 'max_price' => 3000000, 'weight' => 2000],
            
            // ===== کتاب و هنر =====
            ['title' => 'رمان ۱۹۸۴', 'category' => 'کتاب و هنر', 'brand' => 'LG', 'min_price' => 150000, 'max_price' => 300000, 'weight' => 300],
            ['title' => 'مسخ اثر کافکا', 'category' => 'کتاب و هنر', 'brand' => 'LG', 'min_price' => 120000, 'max_price' => 250000, 'weight' => 250],
            ['title' => 'خطاطی نفیس', 'category' => 'کتاب و هنر', 'brand' => 'LG', 'min_price' => 500000, 'max_price' => 1200000, 'weight' => 500],
            
            // ===== ورزش و سفر =====
            ['title' => 'کیسه بوکس', 'category' => 'ورزش و سفر', 'brand' => 'Adidas', 'min_price' => 1500000, 'max_price' => 3000000, 'weight' => 5000],
            ['title' => 'دوچرخه کوهستان', 'category' => 'ورزش و سفر', 'brand' => 'Nike', 'min_price' => 8000000, 'max_price' => 15000000, 'weight' => 14000],
            ['title' => 'چمدان 4 چرخ', 'category' => 'ورزش و سفر', 'brand' => 'LG', 'min_price' => 2000000, 'max_price' => 5000000, 'weight' => 3000],
            ['title' => 'کفش فوتبال', 'category' => 'ورزش و سفر', 'brand' => 'Adidas', 'min_price' => 1200000, 'max_price' => 3000000, 'weight' => 350],
            
            // ===== کارت هدیه =====
            ['title' => 'کارت هدیه دیجی‌کالا', 'category' => 'کارت هدیه', 'brand' => 'LG', 'min_price' => 100000, 'max_price' => 200000, 'weight' => 5],
            ['title' => 'کارت هدیه اسنپ', 'category' => 'کارت هدیه', 'brand' => 'LG', 'min_price' => 100000, 'max_price' => 200000, 'weight' => 5],
        ];

        // ========== 4. Create Products ==========
        foreach ($productsData as $productData) {
            $category = DB::table('categories')->where('name', $productData['category'])->first();
            if (!$category) continue;

            $brand = DB::table('brands')->where('name', $productData['brand'])->first();
            if (!$brand) {
                $brand = DB::table('brands')->first();
            }

            $title = $productData['title'];
            $price = rand($productData['min_price'], $productData['max_price']);
            $price = round($price / 1000) * 1000;
            $salePrice = $price * rand(8, 9) / 10;
            $salePrice = round($salePrice / 1000) * 1000;

            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
                'slug' => Str::slug($title . '-' . Str::random(4)),
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

            DB::table('category_product')->insert([
                'category_id' => $category->id,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $variantCount = rand(2, 3);
            for ($v = 1; $v <= $variantCount; $v++) {
                $variantPrice = $price * rand(9, 12) / 10;
                $variantPrice = round($variantPrice / 1000) * 1000;
                
                $variantSalePrice = $variantPrice * rand(7, 9) / 10;
                $variantSalePrice = round($variantSalePrice / 1000) * 1000;

                DB::table('product_variants')->insert([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(3),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'price' => $variantPrice,
                    'sale_price' => $variantSalePrice,
                    'stock' => rand(5, 50),
                    'weight' => $productData['weight'] * rand(8, 12) / 10,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('✅ ' . DB::table('products')->count() . ' products created!');
    }
}