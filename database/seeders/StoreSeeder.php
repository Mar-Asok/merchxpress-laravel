<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Make sure this is imported

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'first_name' => 'Default',
                'last_name' => 'Admin',
                'username' => 'defaultadmin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                // NO 'name' attribute here
            ]);
        }
        $userId = $user->id;

        $storesData = [
            [
                'name' => "Marielle's SodaHouse",
                'owner' => "Marielle",
                'avatar' => "https://images.unsplash.com/photo-1494790108755-2616c0763c43?w=150&h=150&fit=crop&crop=face",
                'products_count' => 5000,
                'rating' => 4.8,
                'category' => "Beverages",
                'description' => "Premium sodas and refreshing drinks",
                'is_open' => true,
                'is_featured' => true,
                'items' => [
                    [ 'name' => 'Sparkling Berry Blast', 'price' => 2.50, 'image' => 'https://via.placeholder.com/100/FF0000/FFFFFF?text=SodaA', 'stock' => 10, 'description' => 'Refreshing berry flavored soda.' ],
                    [ 'name' => 'Classic Cola', 'price' => 3.00, 'image' => 'https://via.placeholder.com/100/0000FF/FFFFFF?text=SodaB', 'stock' => 5, 'description' => 'Timeless cola flavor.' ],
                ],
            ],
            [
                'name' => "DenSlay Fits",
                'owner' => "Denzey",
                'avatar' => "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face",
                'products_count' => 1500,
                'rating' => 4.5,
                'category' => "Fashion",
                'description' => "Trendy clothing and accessories",
                'is_open' => true,
                'is_featured' => false,
                'items' => [
                    [ 'name' => 'Urban Tee', 'price' => 25.00, 'image' => 'https://via.placeholder.com/100/00FF00/FFFFFF?text=ShirtX', 'stock' => 20, 'description' => 'Comfortable cotton t-shirt.' ],
                    [ 'name' => 'Slim Fit Jeans', 'price' => 40.00, 'image' => 'https://via.placeholder.com/100/FFFF00/000000?text=PantsY', 'stock' => 15, 'description' => 'Modern slim fit jeans.' ],
                ],
            ],
            [
                'name' => "Diana's Bookstore",
                'owner' => "Diana",
                'avatar' => "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face",
                'products_count' => 20000,
                'rating' => 4.9,
                'category' => "Books",
                'description' => "Rare books and literary treasures",
                'is_open' => true,
                'is_featured' => true,
                'items' => [
                    [ 'name' => 'The Lorem Ipsum Book', 'price' => 15.00, 'image' => 'https://via.placeholder.com/100/800080/FFFFFF?text=Book1', 'stock' => 30, 'description' => 'A classic novel of adventure.' ],
                    [ 'name' => 'Modern Poetry Collection', 'price' => 20.00, 'image' => 'https://via.placeholder.com/100/FFA500/000000?text=Book2', 'stock' => 25, 'description' => 'Collection of contemporary poems.' ],
                ],
            ],
            [
                'name' => "Pierre's General Store",
                'owner' => "Pierre",
                'avatar' => "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face",
                'products_count' => 900,
                'rating' => 4.2,
                'category' => "General",
                'description' => "Everything you need under one roof",
                'is_open' => true,
                'is_featured' => false,
                'items' => [
                    [ 'name' => 'Utility Knife', 'price' => 5.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 50, 'description' => 'A multi-purpose tool.' ],
                    [ 'name' => 'LED Flashlight', 'price' => 10.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 40, 'description' => 'Bright and durable flashlight.' ],
                ],
            ],
            [
                'name' => "Stardrop Saloon",
                'owner' => "Gus",
                'avatar' => "https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&h=150&fit=crop&crop=face",
                'products_count' => 950,
                'rating' => 4.7,
                'category' => "Food & Drink",
                'description' => "Cozy atmosphere with great food",
                'is_open' => true,
                'is_featured' => true,
                'items' => [
                    [ 'name' => 'Stardew Pizza', 'price' => 12.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 15, 'description' => 'Delicious pizza with local ingredients.' ],
                    [ 'name' => 'Milkshake Supreme', 'price' => 4.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 30, 'description' => 'Creamy milkshake of your choice.' ],
                ],
            ],
            [
                'name' => "TechZone Electronics",
                'owner' => "Alex",
                'avatar' => "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face",
                'products_count' => 3200,
                'rating' => 4.6,
                'category' => "Electronics",
                'description' => "Latest gadgets and tech accessories",
                'is_open' => true,
                'is_featured' => false,
                'items' => [
                    [ 'name' => 'Wireless Earbuds', 'price' => 199.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 10, 'description' => 'High-fidelity audio earbuds.' ],
                    [ 'name' => 'Portable Charger', 'price' => 25.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 25, 'description' => 'Fast charging power bank.' ],
                ],
            ],
            [
                'name' => "Green Thumb Garden",
                'owner' => "Maya",
                'avatar' => "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&h=150&fit=crop&crop=face",
                'products_count' => 2100,
                'rating' => 4.3,
                'category' => "Plants",
                'description' => "Beautiful plants and gardening supplies",
                'is_open' => false,
                'is_featured' => false,
                'items' => [
                    [ 'name' => 'Monstera Plant', 'price' => 18.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 20, 'description' => 'Lush green indoor plant.' ],
                    [ 'name' => 'Potting Soil (5kg)', 'price' => 8.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 35, 'description' => 'Premium blend potting soil.' ],
                ],
            ],
            [
                'name' => "Artisan Crafts Co.",
                'owner' => "Emma",
                'avatar' => "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&h=150&fit=crop&crop=face",
                'products_count' => 1800,
                'rating' => 4.6,
                'category' => "Handmade",
                'description' => "Unique handcrafted items and art",
                'is_open' => true,
                'is_featured' => true,
                'items' => [
                    [ 'name' => 'Hand-woven Basket', 'price' => 35.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 12, 'description' => 'Beautifully crafted storage basket.' ],
                    [ 'name' => 'Abstract Canvas Art', 'price' => 75.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 8, 'description' => 'Unique piece of abstract art.' ],
                ],
            ],
            [
                'name' => "Sports Central",
                'owner' => "Jake",
                'avatar' => "https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?w=150&h=150&fit=crop&crop=face",
                'products_count' => 4500,
                'rating' => 4.1,
                'category' => "Sports",
                'description' => "Athletic gear and equipment",
                'is_open' => true,
                'is_featured' => false,
                'items' => [
                    [ 'name' => 'Resistance Band Set', 'price' => 55.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 18, 'description' => 'Set of versatile resistance bands.' ],
                    [ 'name' => 'Yoga Mat', 'price' => 90.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 10, 'description' => 'Premium non-slip yoga mat.' ],
                ],
            ],
            [
                'name' => "Sweet Dreams Bakery",
                'owner' => "Sophie",
                'avatar' => "https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=150&h=150&fit=crop&crop=face",
                'products_count' => 850,
                'rating' => 4.9,
                'category' => "Bakery",
                'description' => "Fresh baked goods and desserts",
                'is_open' => true,
                'is_featured' => true,
                'items' => [
                    [ 'name' => 'Chocolate Fudge Cake', 'price' => 20.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 25, 'description' => 'Rich and decadent chocolate cake.' ],
                    [ 'name' => 'Assorted Cookies', 'price' => 5.00, 'image' => 'https://via.placeholder.com/100', 'stock' => 40, 'description' => 'Variety pack of delicious cookies.' ],
                ],
            ]
        ];

        foreach ($storesData as $storeData) {
            $items = $storeData['items'];
            unset($storeData['items']);

            $storeData['user_id'] = $userId;

            $store = Store::create($storeData);

            foreach ($items as $itemData) {
                $store->products()->create($itemData);
            }
        }
    }
}