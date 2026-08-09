<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Creating products with 10+ attributes per variant...');

        // ================================================================
        // 1. GET EXISTING CATEGORIES AND BRANDS
        // ================================================================
        $categories = DB::table('categories')->pluck('id', 'slug')->toArray();
        $brands = DB::table('brands')->pluck('id', 'name')->toArray();

        if (empty($categories) || empty($brands)) {
            $this->command->error('❌ Please run BrandSeeder and CategorySeeder first!');
            return;
        }

        $this->command->info('📊 Found ' . count($categories) . ' categories and ' . count($brands) . ' brands');

        // ================================================================
        // 2. PRODUCT LIST (۱۵۰+ محصول)
        // ================================================================
        $productList = $this->getProductList();

        // ================================================================
        // 3. CREATE PRODUCTS
        // ================================================================
        $productCount = 0;
        $variantCount = 0;
        $colors = ['Black', 'White', 'Silver', 'Gold', 'Blue', 'Red', 'Purple', 'Space Gray', 'Midnight', 'Starlight', 'Green', 'Pink'];
        $storages = ['64GB', '128GB', '256GB', '512GB', '1TB', '2TB'];

        foreach ($productList as $productData) {
            // پیدا کردن دسته‌بندی
            $category = DB::table('categories')->where('slug', $productData['category'])->first();
            
            if (!$category) {
                $this->command->warn("⚠️ Category not found: " . $productData['category']);
                continue;
            }
            
            // پیدا کردن برند
            $brand = DB::table('brands')->where('name', $productData['brand'])->first();
            if (!$brand) {
                $this->command->warn("⚠️ Brand not found: " . $productData['brand']);
                continue;
            }

            $title = $productData['title'];
            $slug = Str::slug($title) . '-' . Str::random(6);
            $basePrice = $productData['price'];
            $finalPrice = $basePrice; // در ابتدا همان قیمت پایه است
            
            $productId = DB::table('products')->insertGetId([
                // ...
                'base_price' => $basePrice, // ✅ تغییر از price به base_price
                // ...
            ]);
            $description = "Premium " . $title . " with high-quality features and excellent performance.";

            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brand->id,
                'title' => $title,
                'slug' => $slug,
                'short_description' => Str::limit($description, 150),
                'description' => '<p>' . $description . '</p>',
                'status' => 'active',
                'meta_title' => $title . ' | Buy with best price',
                'meta_keywords' => $title . ', buy, shop, best price, ' . $productData['brand'],
                'meta_description' => 'Buy ' . $title . ' with best price and quality guarantee.',
                'base_price' => $basePrice, // ✅ قیمت اصلی (ثابت)
                'price' => $finalPrice,     // ✅ قیمت نهایی (قابل تغییر با تخفیف)
                'view_count' => rand(100, 50000),
                'rating' => $productData['rating'],
                'sort_order' => $productCount,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // اتصال به دسته‌بندی
            DB::table('category_product')->insert([
                'category_id' => $category->id,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ================================================================
            // 4. CREATE VARIANTS WITH 10+ ATTRIBUTES
            // ================================================================
            $variantCountPerProduct = rand(3, 5);
            $isDefault = true;
            $usedColors = [];

            for ($v = 1; $v <= $variantCountPerProduct; $v++) {
                $availableColors = array_diff($colors, $usedColors);
                if (empty($availableColors)) break;
                $color = $availableColors[array_rand($availableColors)];
                $usedColors[] = $color;

                $storage = $storages[array_rand($storages)];
                $priceModifier = rand(85, 115) / 100;
                $variantBasePrice = round($basePrice * $priceModifier, 2);
                $variantFinalPrice = $variantBasePrice; // در ابتدا همان قیمت پایه است
                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $productId . '-' . $v . '-' . Str::random(4),
                    'barcode' => rand(1000000000000, 9999999999999),
                    'base_price' => $variantBasePrice, // ✅ قیمت اصلی (ثابت)
                    'price' => $variantFinalPrice,     // ✅ قیمت نهایی (قابل تغییر با تخفیف)
                    'stock' => rand(5, 50),
                    'weight' => rand(100, 1000),
                    'is_active' => 1,
                    'is_default' => $isDefault,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // ✅ اتصال حداقل ۱۰ ویژگی به تنوع
                $this->attachManyAttributes($variantId, $color, $storage, $category->id);

                $isDefault = false;
                $variantCount++;
            }

            $productCount++;
            if ($productCount % 10 === 0) {
                $this->command->info('  📦 Created ' . $productCount . ' products...');
            }
        }

        $this->command->info('✅ ' . $productCount . ' products created!');
        $this->command->info('✅ ' . $variantCount . ' variants created with 10+ attributes each!');
    }

    // ================================================================
    // 5. PRODUCT LIST (۱۵۰+ محصول با برند و دسته‌بندی صحیح)
    // ================================================================
    private function getProductList()
    {
        return [
            // ===== MOBILE (۳۰ محصول) =====
            ['title' => 'iPhone 16 Pro Max 256GB', 'brand' => 'Apple', 'category' => 'iphone-16-pro-max', 'price' => 1250, 'rating' => 4.9],
            ['title' => 'iPhone 16 Pro 128GB', 'brand' => 'Apple', 'category' => 'iphone-16-pro', 'price' => 1100, 'rating' => 4.8],
            ['title' => 'iPhone 16 128GB', 'brand' => 'Apple', 'category' => 'iphone-16', 'price' => 900, 'rating' => 4.7],
            ['title' => 'iPhone 15 Pro Max 256GB', 'brand' => 'Apple', 'category' => 'iphone-15-pro-max', 'price' => 1050, 'rating' => 4.8],
            ['title' => 'iPhone 15 Pro 128GB', 'brand' => 'Apple', 'category' => 'iphone-15-pro', 'price' => 950, 'rating' => 4.7],
            ['title' => 'iPhone 15 128GB', 'brand' => 'Apple', 'category' => 'iphone-15', 'price' => 800, 'rating' => 4.6],
            ['title' => 'iPhone 14 128GB', 'brand' => 'Apple', 'category' => 'iphone-14', 'price' => 700, 'rating' => 4.5],
            ['title' => 'iPhone SE 64GB', 'brand' => 'Apple', 'category' => 'iphone-se', 'price' => 500, 'rating' => 4.3],
            ['title' => 'Galaxy S24 Ultra 512GB', 'brand' => 'Samsung', 'category' => 'galaxy-s24-ultra', 'price' => 1200, 'rating' => 4.9],
            ['title' => 'Galaxy S24 Plus 256GB', 'brand' => 'Samsung', 'category' => 'galaxy-s24-plus', 'price' => 1000, 'rating' => 4.8],
            ['title' => 'Galaxy S24 128GB', 'brand' => 'Samsung', 'category' => 'galaxy-s24', 'price' => 850, 'rating' => 4.7],
            ['title' => 'Galaxy Z Fold 6 512GB', 'brand' => 'Samsung', 'category' => 'galaxy-z-fold-6', 'price' => 1700, 'rating' => 4.8],
            ['title' => 'Galaxy Z Flip 6 256GB', 'brand' => 'Samsung', 'category' => 'galaxy-z-flip-6', 'price' => 1100, 'rating' => 4.7],
            ['title' => 'Galaxy A55 128GB', 'brand' => 'Samsung', 'category' => 'galaxy-a55', 'price' => 450, 'rating' => 4.4],
            ['title' => 'Galaxy A35 128GB', 'brand' => 'Samsung', 'category' => 'galaxy-a35', 'price' => 350, 'rating' => 4.3],
            ['title' => 'Xiaomi 14 Ultra 512GB', 'brand' => 'Xiaomi', 'category' => 'xiaomi-14-ultra', 'price' => 950, 'rating' => 4.8],
            ['title' => 'Xiaomi 14 Pro 256GB', 'brand' => 'Xiaomi', 'category' => 'xiaomi-14-pro', 'price' => 850, 'rating' => 4.7],
            ['title' => 'Xiaomi 14 256GB', 'brand' => 'Xiaomi', 'category' => 'xiaomi-14', 'price' => 750, 'rating' => 4.6],
            ['title' => 'Redmi Note 13 Pro 256GB', 'brand' => 'Xiaomi', 'category' => 'redmi-note-13-pro', 'price' => 400, 'rating' => 4.5],
            ['title' => 'Redmi Note 13 128GB', 'brand' => 'Xiaomi', 'category' => 'redmi-note-13', 'price' => 300, 'rating' => 4.3],
            ['title' => 'Poco X7 Pro 256GB', 'brand' => 'Poco', 'category' => 'poco-x7-pro', 'price' => 500, 'rating' => 4.5],
            ['title' => 'Google Pixel 8 Pro 256GB', 'brand' => 'Google', 'category' => 'google-pixel', 'price' => 950, 'rating' => 4.7],
            ['title' => 'Google Pixel 8 128GB', 'brand' => 'Google', 'category' => 'google-pixel', 'price' => 750, 'rating' => 4.6],
            ['title' => 'OnePlus 12 256GB', 'brand' => 'OnePlus', 'category' => 'oneplus', 'price' => 850, 'rating' => 4.6],
            ['title' => 'OnePlus 12R 128GB', 'brand' => 'OnePlus', 'category' => 'oneplus', 'price' => 650, 'rating' => 4.5],
            ['title' => 'Huawei P60 Pro 256GB', 'brand' => 'Huawei', 'category' => 'huawei', 'price' => 800, 'rating' => 4.5],
            ['title' => 'Huawei Nova 11 128GB', 'brand' => 'Huawei', 'category' => 'huawei', 'price' => 500, 'rating' => 4.2],
            ['title' => 'Nokia X30 128GB', 'brand' => 'Nokia', 'category' => 'nokia', 'price' => 400, 'rating' => 4.1],
            ['title' => 'Sony Xperia 1 V 256GB', 'brand' => 'Sony', 'category' => 'sony-xperia', 'price' => 900, 'rating' => 4.5],
            ['title' => 'Nothing Phone 2 256GB', 'brand' => 'Nothing', 'category' => 'nothing-phone', 'price' => 650, 'rating' => 4.4],

            // ===== LAPTOPS (۲۰ محصول) =====
            ['title' => 'MacBook Pro M4 14" 512GB', 'brand' => 'Apple', 'category' => 'macbook-pro-m4', 'price' => 2200, 'rating' => 4.9],
            ['title' => 'MacBook Pro M3 14" 512GB', 'brand' => 'Apple', 'category' => 'macbook-pro-m3', 'price' => 1900, 'rating' => 4.8],
            ['title' => 'MacBook Pro M2 13" 512GB', 'brand' => 'Apple', 'category' => 'macbook-pro-m2', 'price' => 1600, 'rating' => 4.7],
            ['title' => 'MacBook Air M3 13" 256GB', 'brand' => 'Apple', 'category' => 'macbook-air-m3', 'price' => 1200, 'rating' => 4.7],
            ['title' => 'MacBook Air M2 13" 256GB', 'brand' => 'Apple', 'category' => 'macbook-air-m2', 'price' => 1000, 'rating' => 4.6],
            ['title' => 'ASUS ROG Zephyrus G16', 'brand' => 'Asus', 'category' => 'asus-rog-zephyrus', 'price' => 2100, 'rating' => 4.8],
            ['title' => 'ASUS TUF Gaming A16', 'brand' => 'Asus', 'category' => 'asus-tuf-gaming', 'price' => 1300, 'rating' => 4.5],
            ['title' => 'ASUS ZenBook 14 OLED', 'brand' => 'Asus', 'category' => 'asus-zenbook', 'price' => 1100, 'rating' => 4.6],
            ['title' => 'Lenovo ThinkPad X1 Carbon', 'brand' => 'Lenovo', 'category' => 'lenovo-thinkpad-x1', 'price' => 2000, 'rating' => 4.8],
            ['title' => 'Lenovo Legion Pro 7', 'brand' => 'Lenovo', 'category' => 'lenovo-legion-pro', 'price' => 1800, 'rating' => 4.7],
            ['title' => 'Lenovo IdeaPad 5 Pro', 'brand' => 'Lenovo', 'category' => 'lenovo-ideapad-5', 'price' => 800, 'rating' => 4.4],
            ['title' => 'Dell XPS 16 OLED', 'brand' => 'Dell', 'category' => 'dell-xps-16', 'price' => 1900, 'rating' => 4.7],
            ['title' => 'Dell Alienware m18', 'brand' => 'Dell', 'category' => 'dell-alienware', 'price' => 2200, 'rating' => 4.8],
            ['title' => 'HP Spectre x360 14', 'brand' => 'HP', 'category' => 'hp-spectre-x360', 'price' => 1600, 'rating' => 4.6],
            ['title' => 'HP Omen 16', 'brand' => 'HP', 'category' => 'hp-omen', 'price' => 1700, 'rating' => 4.7],
            ['title' => 'MSI Titan GT77 HX', 'brand' => 'MSI', 'category' => 'msi-titan-gt77', 'price' => 3000, 'rating' => 4.9],
            ['title' => 'Razer Blade 16 OLED', 'brand' => 'Razer', 'category' => 'razer-blade-16', 'price' => 2600, 'rating' => 4.8],
            ['title' => 'Acer Aspire 5', 'brand' => 'Acer', 'category' => 'acer-aspire-5', 'price' => 650, 'rating' => 4.3],
            ['title' => 'LG Gram 17"', 'brand' => 'LG', 'category' => 'lg-gram', 'price' => 1500, 'rating' => 4.5],
            ['title' => 'Microsoft Surface Laptop 5', 'brand' => 'Microsoft', 'category' => 'microsoft-surface-laptop', 'price' => 1400, 'rating' => 4.6],

            // ===== GAMING CONSOLES (۷ محصول) =====
            ['title' => 'PlayStation 5 Console', 'brand' => 'PlayStation', 'category' => 'ps5', 'price' => 500, 'rating' => 4.8],
            ['title' => 'PlayStation 5 Slim', 'brand' => 'PlayStation', 'category' => 'ps5-slim', 'price' => 450, 'rating' => 4.7],
            ['title' => 'PlayStation 5 Pro', 'brand' => 'PlayStation', 'category' => 'ps5-pro', 'price' => 700, 'rating' => 4.9],
            ['title' => 'Xbox Series X', 'brand' => 'Xbox', 'category' => 'xbox-series-x', 'price' => 500, 'rating' => 4.8],
            ['title' => 'Xbox Series S', 'brand' => 'Xbox', 'category' => 'xbox-series-s', 'price' => 300, 'rating' => 4.5],
            ['title' => 'Nintendo Switch OLED', 'brand' => 'Nintendo', 'category' => 'nintendo-switch-oled', 'price' => 350, 'rating' => 4.7],
            ['title' => 'Nintendo Switch Lite', 'brand' => 'Nintendo', 'category' => 'nintendo-switch-lite', 'price' => 200, 'rating' => 4.4],

            // ===== HEADPHONES (۹ محصول) =====
            ['title' => 'Sony WH-1000XM5', 'brand' => 'Sony', 'category' => 'sony-wh-1000xm5', 'price' => 350, 'rating' => 4.9],
            ['title' => 'Sony WH-1000XM4', 'brand' => 'Sony', 'category' => 'sony-wh-1000xm4', 'price' => 280, 'rating' => 4.8],
            ['title' => 'Apple AirPods Pro 2', 'brand' => 'Apple', 'category' => 'apple-airpods-pro-2', 'price' => 250, 'rating' => 4.8],
            ['title' => 'Apple AirPods Max', 'brand' => 'Apple', 'category' => 'apple-airpods-max', 'price' => 550, 'rating' => 4.7],
            ['title' => 'Samsung Galaxy Buds 2 Pro', 'brand' => 'Samsung', 'category' => 'samsung-buds-2-pro', 'price' => 200, 'rating' => 4.6],
            ['title' => 'JBL Charge 5', 'brand' => 'JBL', 'category' => 'jbl-charge-5', 'price' => 150, 'rating' => 4.5],
            ['title' => 'JBL Tune 770NC', 'brand' => 'JBL', 'category' => 'jbl-tune-770nc', 'price' => 150, 'rating' => 4.5],
            ['title' => 'Bose QC45', 'brand' => 'Bose', 'category' => 'bose-qc45', 'price' => 300, 'rating' => 4.7],
            ['title' => 'Xiaomi Buds 3 Pro', 'brand' => 'Xiaomi', 'category' => 'xiaomi-buds-3-pro', 'price' => 80, 'rating' => 4.4],

            // ===== SMARTWATCHES (۶ محصول) =====
            ['title' => 'Apple Watch Ultra 2', 'brand' => 'Apple', 'category' => 'apple-watch-ultra-2', 'price' => 800, 'rating' => 4.9],
            ['title' => 'Apple Watch Series 9', 'brand' => 'Apple', 'category' => 'apple-watch-series-9', 'price' => 450, 'rating' => 4.7],
            ['title' => 'Samsung Galaxy Watch 6', 'brand' => 'Samsung', 'category' => 'samsung-watch-6', 'price' => 350, 'rating' => 4.6],
            ['title' => 'Samsung Galaxy Watch 6 Classic', 'brand' => 'Samsung', 'category' => 'samsung-watch-6-classic', 'price' => 400, 'rating' => 4.7],
            ['title' => 'Xiaomi Watch S3', 'brand' => 'Xiaomi', 'category' => 'xiaomi-watch-s3', 'price' => 120, 'rating' => 4.3],
            ['title' => 'Garmin Forerunner 265', 'brand' => 'Garmin', 'category' => 'garmin-forerunner', 'price' => 450, 'rating' => 4.6],

            // ===== TABLETS (۶ محصول) =====
            ['title' => 'iPad Pro M4 11"', 'brand' => 'Apple', 'category' => 'ipad-pro-m4', 'price' => 1100, 'rating' => 4.9],
            ['title' => 'iPad Pro M2 12.9"', 'brand' => 'Apple', 'category' => 'ipad-pro-m2', 'price' => 1000, 'rating' => 4.8],
            ['title' => 'iPad Air 5', 'brand' => 'Apple', 'category' => 'ipad-air', 'price' => 600, 'rating' => 4.6],
            ['title' => 'Samsung Galaxy Tab S9 Ultra', 'brand' => 'Samsung', 'category' => 'samsung-tab-s9', 'price' => 900, 'rating' => 4.7],
            ['title' => 'Samsung Galaxy Tab S9 FE', 'brand' => 'Samsung', 'category' => 'samsung-tab-s9-fe', 'price' => 500, 'rating' => 4.5],
            ['title' => 'Xiaomi Pad 6', 'brand' => 'Xiaomi', 'category' => 'xiaomi-pad-6', 'price' => 400, 'rating' => 4.4],

            // ===== SPEAKERS (۶ محصول) =====
            ['title' => 'JBL Charge 5', 'brand' => 'JBL', 'category' => 'jbl-charge-5', 'price' => 150, 'rating' => 4.5],
            ['title' => 'Sony SRS-XG300', 'brand' => 'Sony', 'category' => 'sony-srs-xg300', 'price' => 250, 'rating' => 4.6],
            ['title' => 'Bose SoundLink Max', 'brand' => 'Bose', 'category' => 'bose-soundlink-max', 'price' => 350, 'rating' => 4.7],
            ['title' => 'Xiaomi Sound Outdoor', 'brand' => 'Xiaomi', 'category' => 'xiaomi-sound-outdoor', 'price' => 40, 'rating' => 4.2],
            ['title' => 'Anker Soundcore Boom 2', 'brand' => 'Anker', 'category' => 'anker-soundcore-boom', 'price' => 120, 'rating' => 4.4],
            ['title' => 'Marshall Emberton II', 'brand' => 'Marshall', 'category' => 'marshall-emberton', 'price' => 180, 'rating' => 4.6],

            // ===== CAMERAS (۶ محصول) =====
            ['title' => 'Canon EOS R5', 'brand' => 'Canon', 'category' => 'canon-eos-r5', 'price' => 3200, 'rating' => 4.9],
            ['title' => 'Canon EOS R6 Mark II', 'brand' => 'Canon', 'category' => 'canon-eos-r6', 'price' => 2400, 'rating' => 4.8],
            ['title' => 'Nikon Z8', 'brand' => 'Nikon', 'category' => 'nikon-z8', 'price' => 3300, 'rating' => 4.9],
            ['title' => 'Sony Alpha A7 IV', 'brand' => 'Sony', 'category' => 'sony-alpha-a7-iv', 'price' => 2600, 'rating' => 4.8],
            ['title' => 'Sony Alpha A6700', 'brand' => 'Sony', 'category' => 'sony-alpha-a6700', 'price' => 1500, 'rating' => 4.6],
            ['title' => 'Fujifilm X-T5', 'brand' => 'Fujifilm', 'category' => 'fujifilm-xt5', 'price' => 1600, 'rating' => 4.7],

            // ===== POWER BANKS (۴ محصول) =====
            ['title' => 'Anker 20000mAh Power Bank', 'brand' => 'Anker', 'category' => 'anker-20000mah', 'price' => 50, 'rating' => 4.5],
            ['title' => 'Anker 10000mAh Nano', 'brand' => 'Anker', 'category' => 'anker-10000mah', 'price' => 30, 'rating' => 4.4],
            ['title' => 'Xiaomi 30000mAh Power Bank', 'brand' => 'Xiaomi', 'category' => 'xiaomi-30000mah', 'price' => 45, 'rating' => 4.3],
            ['title' => 'Samsung 20000mAh Power Bank', 'brand' => 'Samsung', 'category' => 'samsung-20000mah', 'price' => 55, 'rating' => 4.4],

            // ===== COMPUTER COMPONENTS (۸ محصول) =====
            ['title' => 'Intel Core i9-14900K', 'brand' => 'Intel', 'category' => 'intel-core-i9-14900k', 'price' => 600, 'rating' => 4.8],
            ['title' => 'Intel Core i7-14700K', 'brand' => 'Intel', 'category' => 'intel-core-i7-14700k', 'price' => 400, 'rating' => 4.7],
            ['title' => 'NVIDIA RTX 4090', 'brand' => 'NVIDIA', 'category' => 'nvidia-rtx-4090', 'price' => 1600, 'rating' => 4.9],
            ['title' => 'NVIDIA RTX 4080 Super', 'brand' => 'NVIDIA', 'category' => 'nvidia-rtx-4080', 'price' => 1100, 'rating' => 4.8],
            ['title' => 'AMD Ryzen 9 7950X', 'brand' => 'AMD', 'category' => 'amd-ryzen-9-7950x', 'price' => 550, 'rating' => 4.7],
            ['title' => 'Samsung 1TB 990 Pro SSD', 'brand' => 'Samsung', 'category' => 'samsung-1tb-ssd', 'price' => 120, 'rating' => 4.8],
            ['title' => 'Western Digital 2TB Black HDD', 'brand' => 'Western Digital', 'category' => 'wd-2tb-hdd', 'price' => 100, 'rating' => 4.5],
            ['title' => 'Corsair Vengeance 32GB RAM', 'brand' => 'Corsair', 'category' => 'corsair-vengeance-32gb', 'price' => 120, 'rating' => 4.6],

            // ===== HOME & KITCHEN (۱۰ محصول) =====
            ['title' => 'IKEA Non-Stick Frying Pan Set', 'brand' => 'IKEA', 'category' => 'non-stick-frying-pan', 'price' => 60, 'rating' => 4.4],
            ['title' => 'IKEA Pressure Cooker 6L', 'brand' => 'IKEA', 'category' => 'pressure-cooker', 'price' => 80, 'rating' => 4.3],
            ['title' => 'IKEA Knife Set 5-Piece', 'brand' => 'IKEA', 'category' => 'knife-set', 'price' => 50, 'rating' => 4.4],
            ['title' => 'Philips Coffee Maker 2200', 'brand' => 'Philips', 'category' => 'coffee-maker', 'price' => 200, 'rating' => 4.6],
            ['title' => 'Philips Electric Kettle 1.7L', 'brand' => 'Philips', 'category' => 'electric-kettle', 'price' => 50, 'rating' => 4.4],
            ['title' => 'IKEA EKTORP Sofa', 'brand' => 'IKEA', 'category' => 'sofa-set', 'price' => 600, 'rating' => 4.5],
            ['title' => 'IKEA Dining Table 6-Seater', 'brand' => 'IKEA', 'category' => 'dining-table', 'price' => 400, 'rating' => 4.4],
            ['title' => 'IKEA Office Chair', 'brand' => 'IKEA', 'category' => 'office-chair', 'price' => 150, 'rating' => 4.3],
            ['title' => 'IKEA Chandelier', 'brand' => 'IKEA', 'category' => 'chandelier', 'price' => 200, 'rating' => 4.4],
            ['title' => 'IKEA FADO Table Lamp', 'brand' => 'IKEA', 'category' => 'table-lamp', 'price' => 30, 'rating' => 4.3],

            // ===== HOME APPLIANCES (۱۲ محصول) =====
            ['title' => 'LG Smart Refrigerator 650L', 'brand' => 'LG', 'category' => 'lg-refrigerator', 'price' => 1300, 'rating' => 4.7],
            ['title' => 'Samsung Family Hub Refrigerator', 'brand' => 'Samsung', 'category' => 'samsung-refrigerator', 'price' => 1500, 'rating' => 4.8],
            ['title' => 'Bosch Refrigerator 600L', 'brand' => 'Bosch', 'category' => 'bosch-refrigerator', 'price' => 1200, 'rating' => 4.6],
            ['title' => 'LG Washing Machine 12kg', 'brand' => 'LG', 'category' => 'lg-washing-machine', 'price' => 800, 'rating' => 4.5],
            ['title' => 'Samsung Washing Machine 10kg', 'brand' => 'Samsung', 'category' => 'samsung-washing-machine', 'price' => 900, 'rating' => 4.6],
            ['title' => 'Bosch Dishwasher 14 Place', 'brand' => 'Bosch', 'category' => 'bosch-dishwasher', 'price' => 700, 'rating' => 4.5],
            ['title' => 'LG Robot Vacuum Cleaner', 'brand' => 'LG', 'category' => 'robot-vacuum', 'price' => 500, 'rating' => 4.4],
            ['title' => 'Philips Air Fryer XXL', 'brand' => 'Philips', 'category' => 'air-fryer', 'price' => 250, 'rating' => 4.6],
            ['title' => 'LG Microwave Oven 32L', 'brand' => 'LG', 'category' => 'microwave-oven', 'price' => 350, 'rating' => 4.3],
            ['title' => 'Sony OLED 65" A95L', 'brand' => 'Sony', 'category' => 'sony-oled-tv', 'price' => 2200, 'rating' => 4.8],
            ['title' => 'Samsung QLED 65" QN90C', 'brand' => 'Samsung', 'category' => 'samsung-qled-tv', 'price' => 1900, 'rating' => 4.7],
            ['title' => 'LG OLED 65" C3', 'brand' => 'LG', 'category' => 'lg-oled-tv', 'price' => 2000, 'rating' => 4.8],

            // ===== BEAUTY & HEALTH (۱۰ محصول) =====
            ['title' => 'Loreal Revitalift Cream 50ml', 'brand' => 'Loreal', 'category' => 'moisturizer-cream', 'price' => 40, 'rating' => 4.3],
            ['title' => 'Nivea Sunscreen SPF 50 200ml', 'brand' => 'Nivea', 'category' => 'sunscreen-spf-50', 'price' => 25, 'rating' => 4.2],
            ['title' => 'Maybelline SuperStay Lipstick', 'brand' => 'Maybelline', 'category' => 'lipstick', 'price' => 15, 'rating' => 4.4],
            ['title' => 'Loreal Paris Shampoo 400ml', 'brand' => 'Loreal', 'category' => 'shampoo', 'price' => 20, 'rating' => 4.3],
            ['title' => 'Philips Hair Dryer 2000W', 'brand' => 'Philips', 'category' => 'hair-dryer', 'price' => 60, 'rating' => 4.4],
            ['title' => 'Dior Sauvage Eau de Toilette 100ml', 'brand' => 'Dior', 'category' => 'dior-sauvage', 'price' => 100, 'rating' => 4.6],
            ['title' => 'Chanel No.5 Eau de Parfum 50ml', 'brand' => 'Chanel', 'category' => 'chanel-no-5', 'price' => 150, 'rating' => 4.7],
            ['title' => 'Philips Sonicare Electric Toothbrush', 'brand' => 'Philips', 'category' => 'electric-toothbrush', 'price' => 120, 'rating' => 4.5],
            ['title' => 'Nivea Deodorant 48h', 'brand' => 'Nivea', 'category' => 'deodorant', 'price' => 10, 'rating' => 4.2],
            ['title' => 'Loreal Foundation True Match', 'brand' => 'Loreal', 'category' => 'foundation', 'price' => 20, 'rating' => 4.3],

            // ===== FASHION (۱۲ محصول) =====
            ['title' => "Nike Men's Dri-FIT T-Shirt", 'brand' => 'Nike', 'category' => 'mens-t-shirt', 'price' => 35, 'rating' => 4.4],
            ['title' => "Levi's 501 Original Jeans", 'brand' => 'Levis', 'category' => 'mens-jeans', 'price' => 80, 'rating' => 4.5],
            ['title' => "Zara Men's Suit", 'brand' => 'Zara', 'category' => 'mens-suit', 'price' => 200, 'rating' => 4.6],
            ['title' => "Zara Women's Floral Dress", 'brand' => 'Zara', 'category' => 'womens-dress', 'price' => 60, 'rating' => 4.3],
            ['title' => "Levi's Women's Jeans", 'brand' => 'Levis', 'category' => 'womens-jeans', 'price' => 70, 'rating' => 4.4],
            ['title' => 'Zara Manteau Long Coat', 'brand' => 'Zara', 'category' => 'manteau', 'price' => 120, 'rating' => 4.4],
            ['title' => "Nike Air Max 270", 'brand' => 'Nike', 'category' => 'nike-air-max', 'price' => 150, 'rating' => 4.6],
            ['title' => "Adidas Ultraboost Light", 'brand' => 'Adidas', 'category' => 'adidas-ultraboost', 'price' => 180, 'rating' => 4.7],
            ['title' => "Puma RS-X", 'brand' => 'Puma', 'category' => 'puma-rsx', 'price' => 120, 'rating' => 4.5],
            ['title' => "Men's Wallet Leather", 'brand' => 'Levis', 'category' => 'mens-wallet', 'price' => 40, 'rating' => 4.3],
            ['title' => "Women's Handbag", 'brand' => 'Zara', 'category' => 'womens-handbag', 'price' => 80, 'rating' => 4.4],
            ['title' => 'Rolex Oyster Perpetual Watch', 'brand' => 'Rolex', 'category' => 'rolex-watch', 'price' => 6000, 'rating' => 4.9],

            // ===== GOLD & JEWELRY (۵ محصول) =====
            ['title' => 'Rolex 18K Gold Necklace', 'brand' => 'Rolex', 'category' => 'gold-necklace', 'price' => 3000, 'rating' => 4.8],
            ['title' => 'Rolex Gold Wedding Ring', 'brand' => 'Rolex', 'category' => 'gold-ring', 'price' => 1500, 'rating' => 4.7],
            ['title' => 'Rolex Gold Earrings', 'brand' => 'Rolex', 'category' => 'gold-earrings', 'price' => 2000, 'rating' => 4.8],
            ['title' => 'Rolex Diamond Engagement Ring', 'brand' => 'Rolex', 'category' => 'diamond-ring', 'price' => 5000, 'rating' => 4.9],
            ['title' => 'Seiko Silver Necklace', 'brand' => 'Seiko', 'category' => 'silver-necklace', 'price' => 200, 'rating' => 4.4],

            // ===== VEHICLES (۶ محصول) =====
            ['title' => 'BMW 5 Series 530i', 'brand' => 'BMW', 'category' => 'bmw-5-series', 'price' => 55000, 'rating' => 4.8],
            ['title' => 'Mercedes-Benz E-Class E350', 'brand' => 'Mercedes', 'category' => 'mercedes-e-class', 'price' => 58000, 'rating' => 4.8],
            ['title' => 'Toyota Camry XSE', 'brand' => 'Toyota', 'category' => 'toyota-camry', 'price' => 35000, 'rating' => 4.7],
            ['title' => 'Honda Civic Touring', 'brand' => 'Honda', 'category' => 'honda-civic', 'price' => 30000, 'rating' => 4.6],
            ['title' => 'Honda CBR500R Motorcycle', 'brand' => 'Honda', 'category' => 'honda-cbr-500r', 'price' => 7000, 'rating' => 4.5],
            ['title' => 'Hyundai Sonata Limited', 'brand' => 'Hyundai', 'category' => 'hyundai-sonata', 'price' => 32000, 'rating' => 4.5],

            // ===== HEALTH & MEDICAL (۶ محصول) =====
            ['title' => 'Philips Blood Pressure Monitor', 'brand' => 'Philips', 'category' => 'blood-pressure-monitor', 'price' => 80, 'rating' => 4.5],
            ['title' => 'Philips Digital Thermometer', 'brand' => 'Philips', 'category' => 'digital-thermometer', 'price' => 30, 'rating' => 4.3],
            ['title' => 'Nivea Vitamin C Tablets', 'brand' => 'Nivea', 'category' => 'vitamin-c', 'price' => 15, 'rating' => 4.2],
            ['title' => 'Nivea Omega-3 Supplements', 'brand' => 'Nivea', 'category' => 'omega-3', 'price' => 20, 'rating' => 4.3],
            ['title' => 'Nike Treadmill 2024', 'brand' => 'Nike', 'category' => 'treadmill', 'price' => 1200, 'rating' => 4.6],
            ['title' => 'Nike Yoga Mat Premium', 'brand' => 'Nike', 'category' => 'yoga-mat', 'price' => 40, 'rating' => 4.4],

            // ===== TOOLS (۴ محصول) =====
            ['title' => 'Makita 18V Cordless Drill', 'brand' => 'Makita', 'category' => 'makita-drill', 'price' => 150, 'rating' => 4.7],
            ['title' => 'DeWalt Angle Grinder 4.5"', 'brand' => 'DeWalt', 'category' => 'dewalt-grinder', 'price' => 120, 'rating' => 4.6],
            ['title' => 'Stanley 65-Piece Screwdriver Set', 'brand' => 'Stanley', 'category' => 'screwdriver-set', 'price' => 40, 'rating' => 4.4],
            ['title' => 'Milwaukee M12 Hammer Drill', 'brand' => 'Milwaukee', 'category' => 'milwaukee-hammer-drill', 'price' => 180, 'rating' => 4.7],

            // ===== BOOKS (۳ محصول) =====
            ['title' => '1984 George Orwell - Hardcover', 'brand' => 'Apple', 'category' => '1984-george-orwell', 'price' => 25, 'rating' => 4.8],
            ['title' => 'Atomic Habits - James Clear', 'brand' => 'Apple', 'category' => 'atomic-habits', 'price' => 30, 'rating' => 4.9],
            ['title' => 'Oil Painting Canvas 16x20"', 'brand' => 'Apple', 'category' => 'oil-painting-canvas', 'price' => 50, 'rating' => 4.3],

            // ===== SPORTS (۲ محصول) =====
            ['title' => 'Adidas Boxing Punching Bag', 'brand' => 'Adidas', 'category' => 'boxing-punching-bag', 'price' => 100, 'rating' => 4.5],
            ['title' => 'Adidas Suitcase 4 Wheels 28"', 'brand' => 'Adidas', 'category' => 'suitcase-4-wheels', 'price' => 150, 'rating' => 4.4],

            // ===== GIFT CARDS (۴ محصول) =====
            ['title' => 'Digikala Gift Card $50', 'brand' => 'Apple', 'category' => 'digikala-gift-card', 'price' => 50, 'rating' => 4.5],
            ['title' => 'PlayStation Store Gift Card $50', 'brand' => 'PlayStation', 'category' => 'playstation-gift-card', 'price' => 50, 'rating' => 4.6],
            ['title' => 'Google Play Gift Card $25', 'brand' => 'Google', 'category' => 'google-play-gift-card', 'price' => 25, 'rating' => 4.5],
            ['title' => 'Xbox Gift Card $25', 'brand' => 'Xbox', 'category' => 'xbox-gift-card', 'price' => 25, 'rating' => 4.5],
        ];
    }

    // ================================================================
    // 6. متد اتصال ویژگی‌ها (حداقل ۱۰ ویژگی)
    // ================================================================
    private function attachManyAttributes($variantId, $color, $storage, $categoryId)
    {
        // 1. دریافت ویژگی‌های مربوط به این دسته‌بندی
        $categoryAttrs = DB::table('category_attributes')
            ->where('category_id', $categoryId)
            ->pluck('attribute_id')
            ->toArray();

        if (empty($categoryAttrs)) {
            $categoryAttrs = DB::table('attributes')
                ->whereIn('slug', ['color', 'storage', 'ram', 'processor', 'battery', 'material', 'size', 'display-type', 'camera-mp', 'connectivity'])
                ->pluck('id')
                ->toArray();
        }

        $allAttributeValues = DB::table('attribute_values')->get();

        // 2. ویژگی‌های اجباری (color و storage)
        $colorAttr = DB::table('attributes')->where('slug', 'color')->first();
        $storageAttr = DB::table('attributes')->where('slug', 'storage')->first();

        if ($colorAttr) {
            $colorValue = DB::table('attribute_values')
                ->where('attribute_id', $colorAttr->id)
                ->where('value', $color)
                ->first();
            if ($colorValue) {
                $this->insertVariantAttribute($variantId, $colorValue->id);
            }
        }

        if ($storageAttr) {
            $storageValue = DB::table('attribute_values')
                ->where('attribute_id', $storageAttr->id)
                ->where('value', $storage)
                ->first();
            if ($storageValue) {
                $this->insertVariantAttribute($variantId, $storageValue->id);
            }
        }

        // 3. ویژگی‌های تصادفی اضافی (حداقل ۸ عدد دیگه)
        $currentCount = DB::table('product_variant_attribute_values')
            ->where('product_variant_id', $variantId)
            ->count();

        if ($currentCount < 10) {
            // انتخاب ویژگی‌های تصادفی
            $remainingAttrs = array_values(array_diff($categoryAttrs, DB::table('attributes')->whereIn('slug', ['color', 'storage'])->pluck('id')->toArray()));
            shuffle($remainingAttrs);

            $needed = max(10 - $currentCount, 8);
            $selectedAttrs = array_slice($remainingAttrs, 0, $needed);

            foreach ($selectedAttrs as $attrId) {
                if ($currentCount >= 10) break;

                $possibleValues = $allAttributeValues->where('attribute_id', $attrId);
                if ($possibleValues->isNotEmpty()) {
                    $randomValue = $possibleValues->random();
                    if ($randomValue) {
                        $exists = DB::table('product_variant_attribute_values')
                            ->where('product_variant_id', $variantId)
                            ->where('attribute_value_id', $randomValue->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('product_variant_attribute_values')->insert([
                                'product_variant_id' => $variantId,
                                'attribute_value_id' => $randomValue->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $currentCount++;
                        }
                    }
                }
            }
        }

        // 4. اگر هنوز به ۱۰ نرسیده، از ویژگی‌های عمومی استفاده کن
        if ($currentCount < 10) {
            $generalAttrs = DB::table('attributes')
                ->whereIn('slug', ['material', 'weight', 'size', 'display-type', 'connectivity', 'battery-life', 'water-resistant'])
                ->pluck('id')
                ->toArray();

            foreach ($generalAttrs as $attrId) {
                if ($currentCount >= 10) break;

                $possibleValues = $allAttributeValues->where('attribute_id', $attrId);
                if ($possibleValues->isNotEmpty()) {
                    $randomValue = $possibleValues->random();
                    if ($randomValue) {
                        $exists = DB::table('product_variant_attribute_values')
                            ->where('product_variant_id', $variantId)
                            ->where('attribute_value_id', $randomValue->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('product_variant_attribute_values')->insert([
                                'product_variant_id' => $variantId,
                                'attribute_value_id' => $randomValue->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $currentCount++;
                        }
                    }
                }
            }
        }
    }

    private function insertVariantAttribute($variantId, $attributeValueId)
    {
        $exists = DB::table('product_variant_attribute_values')
            ->where('product_variant_id', $variantId)
            ->where('attribute_value_id', $attributeValueId)
            ->exists();

        if (!$exists) {
            DB::table('product_variant_attribute_values')->insert([
                'product_variant_id' => $variantId,
                'attribute_value_id' => $attributeValueId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}