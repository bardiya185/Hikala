<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(1000000, 90000000),
            'description' => $this->faker->sentence(12),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}