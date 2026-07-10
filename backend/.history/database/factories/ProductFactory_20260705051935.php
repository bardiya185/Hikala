<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            'iPhone 15 Pro',
            'iPhone 14',
            'Samsung Galaxy S24',
            'Samsung A55',
            'MacBook Pro M3',
            'Dell XPS 13',
            'AirPods Pro 2',
            'Sony WH-1000XM5',
            'Xiaomi Redmi Note 13',
            'PlayStation 5',
        ];
    
        return [
            'title' => $this->faker->randomElement($products) . ' ' . $this->faker->numerify('##'),
            'description' => $this->faker->sentence(15),
        ];
    }
}