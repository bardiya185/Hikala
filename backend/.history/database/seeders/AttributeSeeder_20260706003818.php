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

        // ========== 1. Create Attributes ==========
        $attributes = [
            ['name' => 'رنگ', 'slug' => 'color', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 1],
            ['name' => 'حافظه داخلی', 'slug' => 'storage', 'type' => 'select', 'unit' => 'GB', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 2],
            ['name' => 'رم', 'slug' => 'ram', 'type' => 'select', 'unit' => 'GB', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 3],
            ['name' => 'پردازنده', 'slug' => 'processor', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 4],
            ['name' => 'باتری', 'slug' => 'battery', 'type' => 'select', 'unit' => 'mAh', 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 5],
            ['name' => 'سایز', 'slug' => 'size', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 1, 'sort_order' => 6],
            ['name' => 'سال عرضه', 'slug' => 'year', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 7],
            ['name' => 'ضد آب', 'slug' => 'water_resistant', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 8],
            ['name' => 'وزن', 'slug' => 'weight', 'type' => 'select', 'unit' => 'g', 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 9],
            ['name' => 'نوع صفحه نمایش', 'slug' => 'display_type', 'type' => 'select', 'unit' => null, 'is_filterable' => 1, 'is_variant' => 0, 'sort_order' => 10],
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
                'is_required' => 0,
                'sort_order' => $attr['sort_order'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $attributeIds[$attr['slug']] = $id;
        }

        // ========== 2. Create Attribute Values ==========
        $attributeValues = [
            'color' => [
                ['value' => 'مشکی', 'color_code' => '#1a1a1a'],
                ['value' => 'سفید', 'color_code' => '#ffffff'],
                ['value' => 'قرمز', 'color_code' => '#e74c3c'],
                ['value' => 'آبی', 'color_code' => '#3498db'],
                ['value' => 'سبز', 'color_code' => '#2ecc71'],
                ['value' => 'طلایی', 'color_code' => '#f1c40f'],
                ['value' => 'نقره‌ای', 'color_code' => '#bdc3c7'],
                ['value' => 'صورتی', 'color_code' => '#fd79a8'],
                ['value' => 'بنفش', 'color_code' => '#9b59b6'],
                ['value' => 'نارنجی', 'color_code' => '#e67e22'],
            ],
            'storage' => [
                ['value' => '64GB', 'color_code' => null],
                ['value' => '128GB', 'color_code' => null],
                ['value' => '256GB', 'color_code' => null],
                ['value' => '512GB', 'color_code' => null],
                ['value' => '1TB', 'color_code' => null],
            ],
            'ram' => [
                ['value' => '4GB', 'color_code' => null],
                ['value' => '6GB', 'color_code' => null],
                ['value' => '8GB', 'color_code' => null],
                ['value' => '12GB', 'color_code' => null],
                ['value' => '16GB', 'color_code' => null],
                ['value' => '32GB', 'color_code' => null],
            ],
            'processor' => [
                ['value' => 'Intel Core i3', 'color_code' => null],
                ['value' => 'Intel Core i5', 'color_code' => null],
                ['value' => 'Intel Core i7', 'color_code' => null],
                ['value' => 'Intel Core i9', 'color_code' => null],
                ['value' => 'Apple M1', 'color_code' => null],
                ['value' => 'Apple M2', 'color_code' => null],
                ['value' => 'Apple M3', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 1', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 2', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 3', 'color_code' => null],
            ],
            'battery' => [
                ['value' => '2000mAh', 'color_code' => null],
                ['value' => '3000mAh', 'color_code' => null],
                ['value' => '4000mAh', 'color_code' => null],
                ['value' => '5000mAh', 'color_code' => null],
                ['value' => '6000mAh', 'color_code' => null],
                ['value' => '8000mAh', 'color_code' => null],
                ['value' => '10000mAh', 'color_code' => null],
            ],
            'size' => [
                ['value' => 'S', 'color_code' => null],
                ['value' => 'M', 'color_code' => null],
                ['value' => 'L', 'color_code' => null],
                ['value' => 'XL', 'color_code' => null],
                ['value' => 'XXL', 'color_code' => null],
                ['value' => '6.1 اینچ', 'color_code' => null],
                ['value' => '6.7 اینچ', 'color_code' => null],
                ['value' => '13.3 اینچ', 'color_code' => null],
                ['value' => '15.6 اینچ', 'color_code' => null],
            ],
            'year' => [
                ['value' => '2022', 'color_code' => null],
                ['value' => '2023', 'color_code' => null],
                ['value' => '2024', 'color_code' => null],
                ['value' => '2025', 'color_code' => null],
            ],
            'water_resistant' => [
                ['value' => 'بله', 'color_code' => null],
                ['value' => 'خیر', 'color_code' => null],
                ['value' => 'IP67', 'color_code' => null],
                ['value' => 'IP68', 'color_code' => null],
            ],
            'weight' => [
                ['value' => 'کمتر از 200g', 'color_code' => null],
                ['value' => '200-300g', 'color_code' => null],
                ['value' => '300-500g', 'color_code' => null],
                ['value' => '500-1000g', 'color_code' => null],
            ],
            'display_type' => [
                ['value' => 'AMOLED', 'color_code' => null],
                ['value' => 'OLED', 'color_code' => null],
                ['value' => 'LCD', 'color_code' => null],
                ['value' => 'Retina', 'color_code' => null],
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
                    'color_code' => $item['color_code'],
                    'image' => null,
                    'sort_order' => $sortOrder,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sortOrder++;
            }
        }

        // ========== 3. Link Attributes to Categories ==========
        $categories = DB::table('categories')->get();
        $allAttributes = DB::table('attributes')->get();

        // برای موبایل همه ویژگی‌ها
        $categoryAttributeMap = [
            'موبایل' => ['color', 'storage', 'ram', 'processor', 'battery', 'year', 'water_resistant', 'display_type'],
            'لب تاب' => ['color', 'storage', 'ram', 'processor', 'battery', 'size', 'year', 'weight'],
            'کالای دیجیتال' => ['color', 'battery', 'weight', 'water_resistant'],
            'خانه و آشپزخانه' => ['color', 'size', 'weight'],
            'مد و پوشاک' => ['color', 'size', 'weight'],
            'کتاب و هنر' => ['color', 'year'],
        ];

        foreach ($categories as $category) {
            $attrSlugs = $categoryAttributeMap[$category->name] ?? ['color'];
            
            foreach ($attrSlugs as $slug) {
                $attribute = $allAttributes->firstWhere('slug', $slug);
                if ($attribute) {
                    $exists = DB::table('category_attributes')
                        ->where('category_id', $category->id)
                        ->where('attribute_id', $attribute->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('category_attributes')->insert([
                            'category_id' => $category->id,
                            'attribute_id' => $attribute->id,
                            'sort_order' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // ========== 4. Attach 5-6 Attributes to Each Variant ==========
        $this->command->info('🔄 Attaching 5-6 attributes to each variant...');

        $products = DB::table('products')->get();
        $allAttributeValues = DB::table('attribute_values')->get();

        foreach ($products as $product) {
            $categoryProduct = DB::table('category_product')
                ->where('product_id', $product->id)
                ->first();

            if (!$categoryProduct) continue;

            $categoryAttrs = DB::table('category_attributes')
                ->where('category_id', $categoryProduct->category_id)
                ->pluck('attribute_id')
                ->toArray();

            if (empty($categoryAttrs)) continue;

            $variants = DB::table('product_variants')
                ->where('product_id', $product->id)
                ->get();

            foreach ($variants as $variant) {
                // انتخاب ۵ تا ۶ ویژگی برای هر تنوع
                $selectedCount = min(rand(5, 6), count($categoryAttrs));
                $randomKeys = array_rand($categoryAttrs, $selectedCount);
                $selectedAttrs = is_array($randomKeys) 
                    ? array_intersect_key($categoryAttrs, array_flip($randomKeys))
                    : [$categoryAttrs[$randomKeys]];

                foreach ($selectedAttrs as $attrId) {
                    $possibleValues = $allAttributeValues->where('attribute_id', $attrId);
                    if ($possibleValues->isEmpty()) continue;
                    
                    $randomValue = $possibleValues->random();

                    if ($randomValue) {
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

        $this->command->info('✅ All done!');
    }
}