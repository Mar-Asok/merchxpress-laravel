<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation - Updated to match the frontend structure
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0.01',
            'items.*.quantity' => 'required|integer|min:1',

            // Customer Info validation (matching frontend structure)
            'customer_info.first_name' => 'required|string|max:255',
            'customer_info.last_name' => 'required|string|max:255',
            'customer_info.email' => 'required|email|max:255',
            'customer_info.phone' => 'required|string|max:20',

            // Shipping Address validation (matching frontend structure)
            'shipping_address.street' => 'required|string|max:255',
            'shipping_address.barangay' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.province' => 'required|string|max:255',
            'shipping_address.zip_code' => 'required|string|max:10',

            'payment_method' => 'required|string|in:cod,card,gcash',
            'subtotal' => 'required|numeric|min:0',
            'delivery_fee' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // Payment details validation based on payment method
            'card_number' => 'required_if:payment_method,card|string|max:255',
            'card_expiry' => 'required_if:payment_method,card|string|max:10',
            'card_cvc' => 'required_if:payment_method,card|string|max:4',
            'card_name' => 'required_if:payment_method,card|string|max:255',
            'gcash_number' => 'required_if:payment_method,gcash|string|max:20',

            'order_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Get authenticated user
            $userId = auth()->id();

            // 4. Create the Order with corrected field mapping
            $order = Order::create([
                'user_id' => $userId,
                'customer_first_name' => $request->input('customer_info.first_name'),
                'customer_last_name' => $request->input('customer_info.last_name'),
                'customer_email' => $request->input('customer_info.email'),
                'customer_phone' => $request->input('customer_info.phone'),
                'shipping_street' => $request->input('shipping_address.street'),
                'shipping_barangay' => $request->input('shipping_address.barangay'),
                'shipping_city' => $request->input('shipping_address.city'),
                'shipping_province' => $request->input('shipping_address.province'),
                'shipping_zip_code' => $request->input('shipping_address.zip_code'),
                'payment_method' => $request->input('payment_method'),
                'order_notes' => $request->input('order_notes'),
                'subtotal' => $request->input('subtotal'),
                'delivery_fee' => $request->input('delivery_fee'),
                'total_amount' => $request->input('total'),
                'status' => 'pending',
                // Payment details (if applicable)
                'card_number' => $request->input('card_number'),
                'card_expiry' => $request->input('card_expiry'),
                'card_cvc' => $request->input('card_cvc'),
                'card_name' => $request->input('card_name'),
                'gcash_number' => $request->input('gcash_number'),
            ]);

            // 5. Create Order Items and update product stock
            foreach ($request->input('items') as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Product with ID {$item['id']} not found.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $item['name'],
                    'price' => $item['price'], // Updated to match OrderItem model
                    'quantity' => $item['quantity'],
                ]);

                // Decrement stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // 6. Return success response
            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('items')
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to place order. An internal server error occurred.'], 500);
        }
    }

    /**
     * Display a listing of the orders for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()->with('items.product')->get();
        return response()->json($orders);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order)
    {
        if ($request->user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($order->load('items.product'));
    }
}