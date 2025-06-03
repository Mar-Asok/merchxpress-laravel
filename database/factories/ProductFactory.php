<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(2), // Ensure your products table has a 'description' column
            'price' => $this->faker->randomFloat(2, 5, 500),
            'image' => $this->faker->imageUrl(400, 300, 'food', true), // Changed from 'image_url' to 'image'
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}