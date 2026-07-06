<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariantAttributeValue;
use Illuminate\Support\Str;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        // ========== 1. ایجاد دسته‌بندی‌ها ==========
        $categories = [
            'موبایل' => [
                'children' => ['گوشی هوشمند', 'گوشی ساده', 'گوشی مقاوم', 'لوازم جانبی موبایل'],
                'attributes' => ['رنگ', 'حافظه داخلی', 'رم', 'پردازنده', 'اندازه صفحه نمایش'],
                'brands' => ['Apple', 'Samsung', 'Xiaomi', 'Huawei', 'Nokia', 'OnePlus', 'Google', 'Sony', 'Motorola']
            ],
            'لب تاب' => [
                'children' => ['لپ‌تاپ گیمینگ', 'لپ‌تاپ اداری', 'اولترابوک', 'لپ‌تاپ دانشجویی', 'تبلت'],
                'attributes' => ['رنگ', 'رم', 'حافظه SSD', 'پردازنده', 'اندازه صفحه', 'سیستم عامل'],
                'brands' => ['Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'MSI', 'Razer', 'Apple', 'Microsoft']
            ],
            'کالای دیجیتال' => [
                'children' => ['هدفون و هدست', 'ساعت هوشمند', 'دوربین', 'پاوربانک', 'اسپیکر', 'کیس و فن', 'مانیتور'],
                'attributes' => ['رنگ', 'ظرفیت باتری', 'بلوتوث', 'وزن', 'نوع اتصال'],
                'brands' => ['Sony', 'JBL', 'Bose', 'Anker', 'Samsung', 'Canon', 'Nikon', 'Panasonic']
            ],
            'خانه و آشپزخانه' => [
                'children' => ['لوازم آشپزخانه', 'دکوراسیون', 'نورپردازی', 'مبلمان', 'فرش و قالیچه', 'ظروف'],
                'attributes' => ['جنس', 'رنگ', 'ابعاد', 'وزن', 'ماده اولیه'],
                'brands' => ['IKEA', 'Zara Home', 'H&M Home', 'Minoo', 'Parand', 'Delijan']
            ],
            'لوازم خانگی برقی' => [
                'children' => ['یخچال', 'ماشین لباسشویی', 'اجاق گاز', 'ماکروویو', 'جاروبرقی', 'پنکه', 'ساعت', 'اتو'],
                'attributes' => ['رنگ', 'ظرفیت', 'توان مصرفی', 'ابعاد', 'نوع کاربری'],
                'brands' => ['Samsung', 'LG', 'Bosch', 'Whirlpool', 'Miele', 'Kenwood', 'Philips']
            ],
            'آرایشی بهداشتی' => [
                'children' => ['مراقبت پوست', 'آرایشی', 'عطر و ادکلن', 'مراقبت مو', 'بهداشت دهان و دندان', 'لوازم حمام'],
                'attributes' => ['نوع پوست', 'حجم', 'عطر', 'ماده موثره'],
                'brands' => ['L\'Oreal', 'Maybelline', 'Nivea', 'Dior', 'Chanel', 'Yves Saint Laurent', 'Clinique']
            ],
            'مد و پوشاک' => [
                'children' => ['لباس مردانه', 'لباس زنانه', 'لباس بچگانه', 'کفش', 'کیف و کوله', 'زیورآلات'],
                'attributes' => ['سایز', 'رنگ', 'جنس', 'سبک', 'فصل'],
                'brands' => ['Zara', 'H&M', 'Levi\'s', 'Adidas', 'Nike', 'Puma', 'Benetton', 'Mango']
            ],
            'طلا و نقره' => [
                'children' => ['طلا', 'نقره', 'الماس', 'سنگ‌های قیمتی', 'ساعت مچی'],
                'attributes' => ['عیار', 'وزن', 'رنگ', 'طرح'],
                'brands' => ['Tiffany', 'Cartier', 'Rolex', 'Omega', 'Seiko', 'Citizen']
            ],
            'خودرو و موتورسیکلت' => [
                'children' => ['خودرو', 'موتورسیکلت', 'لوازم یدکی', 'لوازم جانبی خودرو', 'قطعات تزئینی'],
                'attributes' => ['رنگ', 'سال ساخت', 'کارکرد', 'گیربکس', 'نوع سوخت'],
                'brands' => ['BMW', 'Mercedes', 'Toyota', 'Honda', 'Hyundai', 'Kia', 'Peugeot', 'Renault']
            ],
            'سلامت و پزشکی' => [
                'children' => ['تجهیزات پزشکی', 'مکمل غذایی', 'لوازم ارتوپدی', 'لوازم دندانپزشکی', 'سلامت جنسی'],
                'attributes' => ['دوز', 'حجم', 'نوع مصرف', 'گروه سنی'],
                'brands' => ['Beehive', 'Miply', 'Vitamin', 'HealthAid', 'FitPlus']
            ],
            'ابزارآلات و تجهیزات' => [
                'children' => ['ابزار دستی', 'ابزار برقی', 'تجهیزات باغبانی', 'لوازم ساختمانی', 'تجهیزات ایمنی'],
                'attributes' => ['جنس', 'سایز', 'توان', 'وزن'],
                'brands' => ['Bosch', 'Makita', 'DeWalt', 'Black+Decker', 'Stanley', 'Milwaukee']
            ],
            'کتاب و هنر' => [
                'children' => ['کتاب', 'کتاب صوتی', 'نقاشی', 'خطاطی', 'صنایع دستی', 'مجلات و نشریات'],
                'attributes' => ['نویسنده', 'ناشر', 'سال چاپ', 'نوع جلد', 'زبان'],
                'brands' => ['نشر چشمه', 'نشر نی', 'نشر ققنوس', 'نشر مرکز', 'نشر کتاب پارسه', 'نشر آگه']
            ],
            'ورزش و سفر' => [
                'children' => ['تجهیزات ورزشی', 'لباس ورزشی', 'کفش ورزشی', 'لوازم سفر', 'چمدان', 'لوازم کوهنوردی'],
                'attributes' => ['سایز', 'جنس', 'وزن', 'رنگ', 'مقاومت'],
                'brands' => ['Adidas', 'Nike', 'Puma', 'New Balance', 'Under Armour', 'Columbia']
            ],
            'کارت هدیه و کیفیت کارت' => [
                'children' => ['کارت هدیه', 'کارت هدیه الکترونیکی', 'کارت هدیه ویژه'],
                'attributes' => ['مبلغ', 'اعتبار', 'نماد'],
                'brands' => ['Digikala', 'Snapp', 'Alibaba', 'Bamilo']
            ]
        ];

        $categoryIds = [];
        $categoryAttributes = [];
        $categoryBrands = [];

        foreach ($categories as $mainName => $data) {
            $mainCategory = Category::create([
                'name' => $mainName,
                'slug' => Str::slug($mainName),
                'is_active' => 1,
                'sort_order' => count($categoryIds) + 1
            ]);
            $categoryIds[$mainName] = $mainCategory->id;
            $categoryAttributes[$mainName] = $data['attributes'];
            $categoryBrands[$mainName] = $data['brands'];

            foreach ($data['children'] as $childName) {
                $child = Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $mainCategory->id,
                    'is_active' => 1
                ]);
                $categoryIds[$childName] = $child->id;
                $categoryAttributes[$childName] = $data['attributes'];
                $categoryBrands[$childName] = $data['brands'];
            }
        }

        // ========== 2. ایجاد برندها ==========
        $allBrands = [];
        foreach ($categoryBrands as $brands) {
            $allBrands = array_merge($allBrands, $brands);
        }
        $allBrands = array_unique($allBrands);

        $brandIds = [];
        foreach ($allBrands as $brand) {
            $brandModel = Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'is_active' => 1
            ]);
            $brandIds[$brand] = $brandModel->id;
        }

        // ========== 3. ایجاد ویژگی‌ها و مقادیر ==========
        $attributeData = [
            'رنگ' => ['قرمز', 'آبی', 'سبز', 'مشکی', 'سفید', 'طلایی', 'نقره‌ای', 'صورتی', 'بژ', 'خاکستری'],
            'حافظه داخلی' => ['64GB', '128GB', '256GB', '512GB', '1TB', '2TB'],
            'رم' => ['4GB', '6GB', '8GB', '12GB', '16GB', '32GB', '64GB'],
            'پردازنده' => ['Intel Core i3', 'Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'Apple M1', 'Apple M2', 'Apple M3', 'Snapdragon 8 Gen 1', 'Snapdragon 8 Gen 2'],
            'اندازه صفحه نمایش' => ['5.5 اینچ', '6.1 اینچ', '6.7 اینچ', '7.0 اینچ', '13.3 اینچ', '14 اینچ', '15.6 اینچ', '16 اینچ', '17.3 اینچ'],
            'حافظه SSD' => ['128GB', '256GB', '512GB', '1TB', '2TB', '4TB'],
            'سیستم عامل' => ['Windows 11', 'macOS', 'Linux', 'Chrome OS', 'Android'],
            'ظرفیت باتری' => ['2000mAh', '3000mAh', '4000mAh', '5000mAh', '6000mAh', '8000mAh', '10000mAh'],
            'بلوتوث' => ['نسخه 4.0', 'نسخه 4.2', 'نسخه 5.0', 'نسخه 5.1', 'نسخه 5.2', 'نسخه 5.3'],
            'وزن' => ['سبک', 'متوسط', 'سنگین', '100g', '200g', '500g', '1kg', '2kg'],
            'نوع اتصال' => ['USB-C', 'Lightning', 'Micro-USB', 'HDMI', 'AUX', 'DisplayPort', 'Thunderbolt'],
            'جنس' => ['چوب', 'فلز', 'پلاستیک', 'شیشه', 'چرم', 'پارچه', 'آلومینیوم', 'استیل'],
            'ابعاد' => ['کوچک', 'متوسط', 'بزرگ', '20x20x20', '30x30x30', '50x50x50'],
            'نوع پوست' => ['چرب', 'خشک', 'مختلط', 'حساس', 'معمولی'],
            'حجم' => ['50ml', '100ml', '150ml', '200ml', '250ml', '500ml', '1L'],
            'ماده موثره' => ['ویتامین C', 'هیالورونیک اسید', 'رتینول', 'نیاسین‌آمید', 'اسید سالیسیلیک'],
            'سایز' => ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', '40', '41', '42', '43', '44', '45'],
            'سبک' => ['کلاسیک', 'اسپرت', 'گیمینگ', 'مینیمال', 'لوکس'],
            'فصل' => ['بهار', 'تابستان', 'پاییز', 'زمستان', 'چهارفصل'],
            'عیار' => ['18K', '21K', '22K', '24K', '925'],
            'سال ساخت' => ['2018', '2019', '2020', '2021', '2022', '2023', '2024', '2025'],
            'کارکرد' => ['0', '10000', '20000', '50000', '100000'],
            'گیربکس' => ['دستی', 'اتوماتیک', 'CVT', 'دو کلاچه'],
            'نوع سوخت' => ['بنزینی', 'دیزلی', 'برقی', 'هیبرید', 'گازسوز'],
            'دوز' => ['100mg', '200mg', '500mg', '1000mg'],
            'گروه سنی' => ['کودک', 'نوجوان', 'بزرگسال', 'سالمند'],
            'نویسنده' => ['جورج اورول', 'فرانتس کافکا', 'صادق هدایت', 'علی دشتی', 'سیمین بهبهانی', 'فروغ فرخزاد'],
            'ناشر' => ['نشر چشمه', 'نشر نی', 'نشر ققنوس', 'نشر مرکز', 'نشر کتاب پارسه', 'نشر آگه'],
            'سال چاپ' => ['1395', '1396', '1397', '1398', '1399', '1400', '1401', '1402', '1403'],
            'نوع جلد' => ['شومیز', 'سخت', 'چرمی', 'گالینگور'],
            'زبان' => ['فارسی', 'انگلیسی', 'عربی', 'فرانسوی', 'آلمانی'],
            'مقاومت' => ['ضد آب', 'ضد ضربه', 'ضد گرد و غبار', 'استاندارد'],
            'مبلغ' => ['100,000', '200,000', '500,000', '1,000,000', '2,000,000'],
            'اعتبار' => ['1 ماه', '3 ماه', '6 ماه', '1 سال', '2 سال'],
            'ظرفیت' => ['5 کیلوگرم', '7 کیلوگرم', '9 کیلوگرم', '12 کیلوگرم', '15 کیلوگرم'],
            'توان مصرفی' => ['1000W', '1500W', '2000W', '3000W', '5000W'],
            'نوع کاربری' => ['خانگی', 'صنعتی', 'اداری', 'سنگین'],
            'ماده اولیه' => ['چوب طبیعی', 'ام دی اف', 'پلاستیک بازیافتی', 'سنگ طبیعی', 'سرمیک'],
        ];

        $attributeIds = [];
        foreach ($attributeData as $attrName => $values) {
            $attribute = Attribute::create([
                'name' => $attrName,
                'slug' => Str::slug($attrName),
                'is_active' => 1
            ]);
            $attributeIds[$attrName] = $attribute->id;

            foreach ($values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'slug' => Str::slug($value)
                ]);
            }
        }

        // ========== 4. ایجاد محصولات خاص برای هر دسته ==========
        $productsData = [
            'موبایل' => [
                'iPhone 16 Pro Max', 'Samsung Galaxy S24 Ultra', 'Xiaomi 14 Pro', 'Google Pixel 8 Pro',
                'OnePlus 12', 'Sony Xperia 1 V', 'Motorola Edge 40'
            ],
            'لب تاب' => [
                'MacBook Pro M3', 'Dell XPS 16', 'HP Spectre x360', 'Lenovo ThinkPad X1',
                'Asus ROG Zephyrus', 'Acer Predator Helios', 'Razer Blade 16'
            ],
            'کالای دیجیتال' => [
                'Sony WH-1000XM5', 'Apple Watch Ultra 2', 'Canon EOS R5', 'Anker Power Bank 20000',
                'JBL Charge 5', 'Samsung Galaxy Watch 6', 'DJI Pocket 3'
            ],
            'خانه و آشپزخانه' => [
                'مبل سلطنتی', 'چراغ لوستر طلایی', 'قالیچه نفیس', 'مجموعه ظروف چینی',
                'میز ناهارخوری مدرن', 'فرش دستباف', 'دفترچه دکوراتیو'
            ],
            'لوازم خانگی برقی' => [
                'یخچال ساید بای ساید', 'ماشین لباسشویی 9 کیلو', 'اجاق گاز 5 شعله', 'مایکروویو هوشمند',
                'جاروبرقی رباتیک', 'پنکه ایستاده', 'اتو بخار', 'ساعت دیواری'
            ],
            'آرایشی بهداشتی' => [
                'کرم ضد چروک', 'رژ لب مخملی', 'عطر دیور', 'شامپو حجم دهنده',
                'سرم ویتامین C', 'لوسیون مرطوب‌کننده', 'کرم ضد آفتاب'
            ],
            'مد و پوشاک' => [
                'کت و شلوار مردانه', 'لباس مجلسی زنانه', 'کفش چرم دست‌دوز', 'کیف دوشی زنانه',
                'تیشرکت پنبه‌ای', 'شلوار جین کلاسیک', 'سوئیشرت مردانه'
            ],
            'طلا و نقره' => [
                'گردنبند طلا 24K', 'دستبند نقره', 'انگشتر الماس', 'ساعت رولکس', 'ساعت سیکو'
            ],
            'خودرو و موتورسیکلت' => [
                'BMW سری 5', 'Mercedes E-Class', 'Toyota Camry', 'Honda Civic',
                'موتورسیکلت هوندا', 'رینگ اسپرت', 'سیستم صوتی خودرو'
            ],
            'سلامت و پزشکی' => [
                'دستگاه فشار خون', 'مکمل کلسیم', 'کمربند طبی', 'مسواک برقی', 'ماسک پزشکی'
            ],
            'ابزارآلات و تجهیزات' => [
                'دربیل برقی', 'پیچ‌گوشتی شارژی', 'اره گردبر', 'ست آچار', 'جعبه ابزار حرفه‌ای'
            ],
            'کتاب و هنر' => [
                'رمان ۱۹۸۴', 'مسخ اثر کافکا', 'بوف کور صادق هدایت', 'تاریخ تمدن ویل دورانت',
                'کتاب هنر نقاشی', 'خطاطی نفیس', 'صنایع دستی اصفهان'
            ],
            'ورزش و سفر' => [
                'کیسه بوکس', 'دوچرخه کوهستان', 'کفش اسپرت نایک', 'چمدان 4 چرخ',
                'کیف کوهنوردی', 'ساک ورزشی', 'کفش فوتبال'
            ],
            'کارت هدیه و کیفیت کارت' => [
                'کارت هدیه دیجی‌کالا', 'کارت هدیه اسنپ', 'کارت هدیه علی‌بابا',
                'کارت هدیه بانک', 'کارت هدیه طلایی'
            ]
        ];

        // ایجاد ۱۰۰ محصول
        $allMainCategories = array_keys($productsData);
        
        for ($i = 1; $i <= 100; $i++) {
            // انتخاب یک دسته اصلی تصادفی
            $mainCategory = $allMainCategories[array_rand($allMainCategories)];
            $categoryProducts = $productsData[$mainCategory];
            
            // انتخاب محصول تصادفی از آن دسته
            $title = $categoryProducts[array_rand($categoryProducts)];
            
            // انتخاب یک زیردسته تصادفی از آن دسته اصلی
            $subCategories = Category::where('parent_id', $categoryIds[$mainCategory])->get();
            $selectedCategory = $subCategories->isNotEmpty() ? $subCategories->random() : Category::find($categoryIds[$mainCategory]);
            
            // انتخاب برند تصادفی از برندهای مربوط به آن دسته
            $categoryBrandsList = $categoryBrands[$mainCategory] ?? $allBrands;
            $brandName = $categoryBrandsList[array_rand($categoryBrandsList)];
            $brandId = $brandIds[$brandName] ?? Brand::inRandomOrder()->first()->id;

            // ویژگی‌های مربوط به این دسته
            $availableAttributes = $categoryAttributes[$mainCategory] ?? ['رنگ', 'جنس'];
            
            $product = Product::create([
                'brand_id' => $brandId,
                'title' => $title . ' ' . Str::random(4),
                'slug' => Str::slug($title . '-' . Str::random(4)),
                'short_description' => substr($title . ' - محصولی با کیفیت عالی از برند ' . $brandName, 0, 100),
                'description' => "<h3>معرفی {$title}</h3><p>این محصول از برند معروف {$brandName} با بالاترین استانداردها تولید شده است.</p><p>مناسب برای استفاده روزمره و با کیفیت بی‌نظیر.</p>",
                'status' => 'active',
                'meta_title' => $title . ' | فروشگاه اینترنتی',
                'meta_keywords' => implode(', ', [$title, $brandName, $mainCategory]),
                'meta_description' => "خرید {$title} با بهترین قیمت و کیفیت از فروشگاه اینترنتی",
                'view_count' => rand(0, 5000),
                'is_active' => 1,
                'sort_order' => $i
            ]);

            // ارتباط با دسته‌بندی (زیردسته یا اصلی)
            $product->categories()->attach($selectedCategory->id);

            // ایجاد بین ۲ تا ۴ تنوع برای هر محصول
            $variantCount = rand(2, 4);
            for ($v = 1; $v <= $variantCount; $v++) {
                // قیمت‌های مختلف برای هر تنوع
                $basePrice = rand(100000, 50000000);
                $price = $basePrice * rand(7, 9) / 10; // قیمت با تخفیف 10-30%
                $comparePrice = $price * rand(11, 15) / 10; // قیمت اصلی

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => 'SKU-' . $product->id . '-' . $v . '-' . Str::random(3),
                    'price' => round($price, -2), // گرد به نزدیک‌ترین 100
                    'compare_price' => round($comparePrice, -2),
                    'stock' => rand(0, 100),
                    'is_active' => 1,
                    'sort_order' => $v
                ]);

                // اختصاص ۲ تا ۳ ویژگی به هر تنوع
                $selectedAttrs = array_rand($availableAttributes, rand(2, 3));
                if (!is_array($selectedAttrs)) {
                    $selectedAttrs = [$selectedAttrs];
                }

                foreach ($selectedAttrs as $attrKey) {
                    $attrName = $availableAttributes[$attrKey];
                    
                    // بررسی وجود ویژگی
                    if (!isset($attributeIds[$attrName])) {
                        continue;
                    }
                    
                    $attrId = $attributeIds[$attrName];
                    
                    // دریافت یک مقدار تصادفی برای این ویژگی
                    $randomValue = AttributeValue::where('attribute_id', $attrId)
                        ->inRandomOrder()
                        ->first();

                    if ($randomValue) {
                        ::create([
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $randomValue->id
                        ]);
                    }
                }
            }
        }

        // ========== 5. گزارش نهایی ==========
        $totalProducts = Product::count();
        $totalVariants = ProductVariant::count();
        $totalCategories = Category::count();
        
        $this->command->info('✅ ' . $totalProducts . ' محصول با ' . $totalVariants . ' تنوع ایجاد شد!');
        $this->command->info('📂 ' . $totalCategories . ' دسته‌بندی در سیستم وجود دارد.');
        $this->command->info('🎯 محصولات در 14 دسته اصلی توزیع شده‌اند.');
    }
}