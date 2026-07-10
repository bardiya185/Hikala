<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [

            'short_description' => fake()->sentence(),

            'description' => fake()->paragraph(),

            'status' => 'active',

            'is_active' => true,

            'view_count' => 0,

            'sort_order' => 0,

            'meta_title' => null,

            'meta_keywords' => null,

            'meta_description' => null,

        ];
    }
}