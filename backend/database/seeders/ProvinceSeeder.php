<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['name' => 'آذربایجان شرقی', 'code' => '01'],
            ['name' => 'آذربایجان غربی', 'code' => '02'],
            ['name' => 'اردبیل', 'code' => '03'],
            ['name' => 'اصفهان', 'code' => '04'],
            ['name' => 'البرز', 'code' => '05'],
            ['name' => 'ایلام', 'code' => '06'],
            ['name' => 'بوشهر', 'code' => '07'],
            ['name' => 'تهران', 'code' => '08'],
            ['name' => 'چهارمحال و بختیاری', 'code' => '09'],
            ['name' => 'خراسان جنوبی', 'code' => '10'],
            ['name' => 'خراسان رضوی', 'code' => '11'],
            ['name' => 'خراسان شمالی', 'code' => '12'],
            ['name' => 'خوزستان', 'code' => '13'],
            ['name' => 'زنجان', 'code' => '14'],
            ['name' => 'سمنان', 'code' => '15'],
            ['name' => 'سیستان و بلوچستان', 'code' => '16'],
            ['name' => 'فارس', 'code' => '17'],
            ['name' => 'قزوین', 'code' => '18'],
            ['name' => 'قم', 'code' => '19'],
            ['name' => 'کردستان', 'code' => '20'],
            ['name' => 'کرمان', 'code' => '21'],
            ['name' => 'کرمانشاه', 'code' => '22'],
            ['name' => 'کهگیلویه و بویراحمد', 'code' => '23'],
            ['name' => 'گلستان', 'code' => '24'],
            ['name' => 'گیلان', 'code' => '25'],
            ['name' => 'لرستان', 'code' => '26'],
            ['name' => 'مازندران', 'code' => '27'],
            ['name' => 'مرکزی', 'code' => '28'],
            ['name' => 'هرمزگان', 'code' => '29'],
            ['name' => 'همدان', 'code' => '30'],
            ['name' => 'یزد', 'code' => '31'],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'name' => $province['name'],
                'slug' => Str::slug($province['name']),
                'code' => $province['code'],
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Provinces seeded successfully!');
    }
}