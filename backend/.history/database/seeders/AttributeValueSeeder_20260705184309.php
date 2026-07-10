<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        $items = require database_path('data/attribute_values.php');

        foreach ($items as $attributeSlug => $values) {

            $attribute = Attribute::where('slug', $attributeSlug)->first();

            if (!$attribute) {
                continue;
            }

            $sort = 1;

            foreach ($values as $item) {

                AttributeValue::updateOrCreate(

                    [
                        'attribute_id' => $attribute->id,
                        'slug' => $item['slug'],
                    ],

                    [
                        'value' => $item['value'],
                        'color_code' => $item['color_code'] ?? null,
                        'sort_order' => $sort++,
                        'is_active' => true,
                    ]

                );

            }

        }
    }
}