<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => 'active',
        ];
    }
}