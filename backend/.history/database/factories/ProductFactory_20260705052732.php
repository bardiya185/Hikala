<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => Str::slug($title),
            'status' => 'active',
        ];
    }
}