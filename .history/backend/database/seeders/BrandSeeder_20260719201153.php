<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            // ===== MOBILE (۱۲ برند) =====
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Google', 'slug' => 'google'],
            ['name' => 'OnePlus', 'slug' => 'oneplus'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'Nokia', 'slug' => 'nokia'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'Motorola', 'slug' => 'motorola'],
            ['name' => 'Realme', 'slug' => 'realme'],
            ['name' => 'Nothing', 'slug' => 'nothing'],
            ['name' => 'Poco', 'slug' => 'poco'],
            ['name' => 'Honor', 'slug' => 'honor'], // جدید

            // ===== LAPTOPS (۸ برند) =====
            ['name' => 'Asus', 'slug' => 'asus'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Acer', 'slug' => 'acer'],
            ['name' => 'MSI', 'slug' => 'msi'],
            ['name' => 'Razer', 'slug' => 'razer'],
            ['name' => 'Microsoft', 'slug' => 'microsoft'], // جدید

            // ===== DIGITAL PRODUCTS (۱۵ برند) =====
            ['name' => 'JBL', 'slug' => 'jbl'],
            ['name' => 'Bose', 'slug' => 'bose'],
            ['name' => 'Anker', 'slug' => 'anker'],
            ['name' => 'Canon', 'slug' => 'canon'],
            ['name' => 'Nikon', 'slug' => 'nikon'],
            ['name' => 'Nintendo', 'slug' => 'nintendo'],
            ['name' => 'PlayStation', 'slug' => 'playstation'],
            ['name' => 'Xbox', 'slug' => 'xbox'],
            ['name' => 'Intel', 'slug' => 'intel'], // جدید
            ['name' => 'NVIDIA', 'slug' => 'nvidia'], // جدید
            ['name' => 'AMD', 'slug' => 'amd'], // جدید
            ['name' => 'Corsair', 'slug' => 'corsair'], // جدید
            ['name' => 'Western Digital', 'slug' => 'western-digital'], // جدید
            ['name' => 'TP-Link', 'slug' => 'tp-link'], // جدید
            ['name' => 'D-Link', 'slug' => 'd-link'], // جدید
            ['name' => 'Beats', 'slug' => 'beats'], // جدید
            ['name' => 'Marshall', 'slug' => 'marshall'], // جدید
            ['name' => 'Garmin', 'slug' => 'garmin'], // جدید
            ['name' => 'Fujifilm', 'slug' => 'fujifilm'], // جدید

            // ===== HOME & KITCHEN (۶ برند) =====
            ['name' => 'IKEA', 'slug' => 'ikea'], // جدید
            ['name' => 'Zara Home', 'slug' => 'zara-home'], // جدید
            ['name' => 'Casper', 'slug' => 'casper'], // جدید
            ['name' => 'Kenwood', 'slug' => 'kenwood'], // جدید

            // ===== HOME APPLIANCES (۱۰ برند) =====
            ['name' => 'LG', 'slug' => 'lg'],
            ['name' => 'Bosch', 'slug' => 'bosch'],
            ['name' => 'Philips', 'slug' => 'philips'],
            ['name' => 'Samsung Home', 'slug' => 'samsung-home'],
            ['name' => 'Whirlpool', 'slug' => 'whirlpool'],
            ['name' => 'Snowa', 'slug' => 'snowa'],
            ['name' => 'Pakshoma', 'slug' => 'pakshoma'],
            ['name' => 'Toshiba', 'slug' => 'toshiba'], // جدید
            ['name' => 'X-Vision', 'slug' => 'x-vision'], // جدید
            ['name' => 'TCL', 'slug' => 'tcl'], // جدید
            ['name' => 'Daewoo', 'slug' => 'daewoo'], // جدید
            ['name' => 'Gree', 'slug' => 'gree'], // جدید
            ['name' => 'Janome', 'slug' => 'janome'], // جدید

            // ===== BEAUTY & HEALTH (۷ برند) =====
            ['name' => 'Loreal', 'slug' => 'loreal'],
            ['name' => 'Maybelline', 'slug' => 'maybelline'],
            ['name' => 'Nivea', 'slug' => 'nivea'],
            ['name' => 'Dior', 'slug' => 'dior'],
            ['name' => 'Chanel', 'slug' => 'chanel'],
            ['name' => 'Clinique', 'slug' => 'clinique'],
            ['name' => 'Versace', 'slug' => 'versace'], // جدید
            ['name' => 'Gucci', 'slug' => 'gucci'], // جدید

            // ===== FASHION (۸ برند) =====
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Puma', 'slug' => 'puma'],
            ['name' => 'Zara', 'slug' => 'zara'],
            ['name' => 'H&M', 'slug' => 'hm'],
            ['name' => 'Levis', 'slug' => 'levis'],
            ['name' => 'Under Armour', 'slug' => 'under-armour'], // جدید
            ['name' => 'New Balance', 'slug' => 'new-balance'], // جدید

            // ===== GOLD & JEWELRY (۶ برند) =====
            ['name' => 'Rolex', 'slug' => 'rolex'],
            ['name' => 'Omega', 'slug' => 'omega'],
            ['name' => 'Seiko', 'slug' => 'seiko'],
            ['name' => 'Tissot', 'slug' => 'tissot'],
            ['name' => 'Citizen', 'slug' => 'citizen'],
            ['name' => 'Tag Heuer', 'slug' => 'tag-heuer'], // جدید

            // ===== VEHICLES (۷ برند) =====
            ['name' => 'BMW', 'slug' => 'bmw'],
            ['name' => 'Mercedes', 'slug' => 'mercedes'],
            ['name' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'Honda', 'slug' => 'honda'],
            ['name' => 'Hyundai', 'slug' => 'hyundai'],
            ['name' => 'Kia', 'slug' => 'kia'],
            ['name' => 'Yamaha', 'slug' => 'yamaha'], // جدید
            ['name' => 'Suzuki', 'slug' => 'suzuki'], // جدید
            ['name' => 'Kawasaki', 'slug' => 'kawasaki'], // جدید

            // ===== HEALTH & MEDICAL (۴ برند) =====
            ['name' => 'Philips Medical', 'slug' => 'philips-medical'],
            ['name' => 'Omron', 'slug' => 'omron'], // جدید
            ['name' => 'Braun', 'slug' => 'braun'], // جدید
            ['name' => 'Beurer', 'slug' => 'beurer'], // جدید

            // ===== TOOLS & EQUIPMENT (۵ برند) =====
            ['name' => 'Makita', 'slug' => 'makita'],
            ['name' => 'DeWalt', 'slug' => 'dewalt'],
            ['name' => 'Stanley', 'slug' => 'stanley'],
            ['name' => 'Milwaukee', 'slug' => 'milwaukee'],
            ['name' => 'Black+Decker', 'slug' => 'black-decker'], // جدید
            ['name' => 'Bosch Tools', 'slug' => 'bosch-tools'], // جدید

            // ===== BOOKS & ART (۲ برند) =====
            ['name' => 'Penguin Books', 'slug' => 'penguin-books'], // جدید
            ['name' => 'Oxford Press', 'slug' => 'oxford-press'], // جدید

            // ===== SPORTS & TRAVEL (۳ برند) =====
            ['name' => 'Columbia', 'slug' => 'columbia'], // جدید
            ['name' => 'The North Face', 'slug' => 'north-face'], // جدید
            ['name' => 'Samsonite', 'slug' => 'samsonite'], // جدید

            // ===== GIFT CARDS (بدون برند خاص) =====
            ['name' => 'Digikala', 'slug' => 'digikala'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->updateOrInsert(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('✅ ' . DB::table('brands')->count() . ' brands seeded successfully!');
    }
}