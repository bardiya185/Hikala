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

        // ========== 2. Create Attribute Values (with English slugs) ==========
        $attributeValues = [
            'color' => [
                ['value' => 'مشکی', 'slug' => 'black', 'color_code' => '#1a1a1a'],
                ['value' => 'سفید', 'slug' => 'white', 'color_code' => '#ffffff'],
                ['value' => 'قرمز', 'slug' => 'red', 'color_code' => '#e74c3c'],
                ['value' => 'آبی', 'slug' => 'blue', 'color_code' => '#3498db'],
                ['value' => 'سبز', 'slug' => 'green', 'color_code' => '#2ecc71'],
                ['value' => 'طلایی', 'slug' => 'gold', 'color_code' => '#f1c40f'],
                ['value' => 'نقره‌ای', 'slug' => 'silver', 'color_code' => '#bdc3c7'],
                ['value' => 'صورتی', 'slug' => 'pink', 'color_code' => '#fd79a8'],
                ['value' => 'بنفش', 'slug' => 'purple', 'color_code' => '#9b59b6'],
                ['value' => 'نارنجی', 'slug' => 'orange', 'color_code' => '#e67e22'],
            ],
            'storage' => [
                ['value' => '64GB', 'slug' => '64gb', 'color_code' => null],
                ['value' => '128GB', 'slug' => '128gb', 'color_code' => null],
                ['value' => '256GB', 'slug' => '256gb', 'color_code' => null],
                ['value' => '512GB', 'slug' => '512gb', 'color_code' => null],
                ['value' => '1TB', 'slug' => '1tb', 'color_code' => null],
            ],
            'ram' => [
                ['value' => '4GB', 'slug' => '4gb', 'color_code' => null],
                ['value' => '6GB', 'slug' => '6gb', 'color_code' => null],
                ['value' => '8GB', 'slug' => '8gb', 'color_code' => null],
                ['value' => '12GB', 'slug' => '12gb', 'color_code' => null],
                ['value' => '16GB', 'slug' => '16gb', 'color_code' => null],
                ['value' => '32GB', 'slug' => '32gb', 'color_code' => null],
            ],
            'processor' => [
                ['value' => 'Intel Core i3', 'slug' => 'intel-core-i3', 'color_code' => null],
                ['value' => 'Intel Core i5', 'slug' => 'intel-core-i5', 'color_code' => null],
                ['value' => 'Intel Core i7', 'slug' => 'intel-core-i7', 'color_code' => null],
                ['value' => 'Intel Core i9', 'slug' => 'intel-core-i9', 'color_code' => null],
                ['value' => 'Apple M1', 'slug' => 'apple-m1', 'color_code' => null],
                ['value' => 'Apple M2', 'slug' => 'apple-m2', 'color_code' => null],
                ['value' => 'Apple M3', 'slug' => 'apple-m3', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 1', 'slug' => 'snapdragon-8-gen-1', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 2', 'slug' => 'snapdragon-8-gen-2', 'color_code' => null],
                ['value' => 'Snapdragon 8 Gen 3', 'slug' => 'snapdragon-8-gen-3', 'color_code' => null],
            ],
            'battery' => [
                ['value' => '2000mAh', 'slug' => '2000mah', 'color_code' => null],
                ['value' => '3000mAh', 'slug' => '3000mah', 'color_code' => null],
                ['value' => '4000mAh', 'slug' => '4000mah', 'color_code' => null],
                ['value' => '5000mAh', 'slug' => '5000mah', 'color_code' => null],
                ['value' => '6000mAh', 'slug' => '6000mah', 'color_code' => null],
                ['value' => '8000mAh', 'slug' => '8000mah', 'color_code' => null],
                ['value' => '10000mAh', 'slug' => '10000mah', 'color_code' => null],
            ],
            'size' => [
                ['value' => 'S', 'slug' => 's', 'color_code' => null],
                ['value' => 'M', 'slug' => 'm', 'color_code' => null],
                ['value' => 'L', 'slug' => 'l', 'color_code' => null],
                ['value' => 'XL', 'slug' => 'xl', 'color_code' => null],
                ['value' => 'XXL', 'slug' => 'xxl', 'color_code' => null],
                ['value' => '6.1 اینچ', 'slug' => '6-1-inch', 'color_code' => null],
                ['value' => '6.7 اینچ', 'slug' => '6-7-inch', 'color_code' => null],
                ['value' => '13.3 اینچ', 'slug' => '13-3-inch', 'color_code' => null],
                ['value' => '15.6 اینچ', 'slug' => '15-6-inch', 'color_code' => null],
            ],
            'year' => [
                ['value' => '2022', 'slug' => '2022', 'color_code' => null],
                ['value' => '2023', 'slug' => '2023', 'color_code' => null],
                ['value' => '2024', 'slug' => '2024', 'color_code' => null],
                ['value' => '2025', 'slug' => '2025', 'color_code' => null],
            ],
            'water_resistant' => [
                ['value' => 'بله', 'slug' => 'yes', 'color_code' => null],
                ['value' => 'خیر', 'slug' => 'no', 'color_code' => null],
                ['value' => 'IP67', 'slug' => 'ip67', 'color_code' => null],
                ['value' => 'IP68', 'slug' => 'ip68', 'color_code' => null],
            ],
            'weight' => [
                ['value' => 'کمتر از 200g', 'slug' => 'less-than-200g', 'color_code' => null],
                ['value' => '200-300g', 'slug' => '200-300g', 'color_code' => null],
                ['value' => '300-500g', 'slug' => '300-500g', 'color_code' => null],
                ['value' => '500-1000g', 'slug' => '500-1000g', 'color_code' => null],
            ],
            'display_type' => [
                ['value' => 'AMOLED', 'slug' => 'amoled', 'color_code' => null],
                ['value' => 'OLED', 'slug' => 'oled', 'color_code' => null],
                ['value' => 'LCD', 'slug' => 'lcd', 'color_code' => null],
                ['value' => 'Retina', 'slug' => 'retina', 'color_code' => null],
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
                    'slug' => $item['slug'],
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