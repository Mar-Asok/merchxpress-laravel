<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'owner' => $this->faker->name(), // Changed from 'owner_name' to 'owner'
            'avatar' => $this->faker->imageUrl(150, 150, 'people', true), // Changed from 'avatar_url' to 'avatar'
            'products_count' => $this->faker->numberBetween(10, 500),
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'category' => $this->faker->randomElement(['Electronics', 'Fashion', 'Books', 'Food & Drink', 'Home Goods', 'Sports', 'Health & Beauty']),
            'description' => $this->faker->sentence(),
            'is_open' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(30),
        ];
    }
}