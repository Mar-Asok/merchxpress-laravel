<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index()
    {
        // Eager load products relationship
        $stores = Store::with('products')->get();
        return response()->json($stores);
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        // Eager load products relationship
        $store->load('products');
        return response()->json($store);
    }
}