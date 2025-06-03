<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store; // IMPORTANT: Make sure to import the Store model
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Get all products for a specific store.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsByStore(Store $store)
    {
        // This fetches all products related to the given store
        // It assumes you have a 'products' relationship defined in your Store model.
        // For example, in app/Models/Store.php:
        // public function products() { return $this->hasMany(Product::class); }
        $products = $store->products;

        return response()->json($products);
    }
}