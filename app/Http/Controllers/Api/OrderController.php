<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Import Product model to check stock
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException; // Import for explicit exception

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id', // Ensure product exists
            'items.*.name' => 'required|string', // Name is sent for historical record
            'items.*.price' => 'required|numeric|min:0.01',
            'items.*.quantity' => 'required|integer|min:1',

            // Customer Info validation (now nested)
            'customerInfo.firstName' => 'required|string|max:255',
            'customerInfo.lastName' => 'required|string|max:255',
            'customerInfo.email' => 'required|email|max:255',
            'customerInfo.phone' => 'required|string|max:20', // Adjust max length as needed

            // Shipping Address validation (now nested)
            'shippingAddress.street' => 'required|string|max:255',
            'shippingAddress.barangay' => 'required|string|max:255',
            'shippingAddress.city' => 'required|string|max:255',
            'shippingAddress.province' => 'required|string|max:255',
            'shippingAddress.zipCode' => 'required|string|max:10', // Adjust max length as needed

            'paymentMethod' => 'required|string|in:cod,card,gcash', // Ensure it's one of these
            'subtotal' => 'required|numeric|min:0', // Added validation for subtotal
            'deliveryFee' => 'required|numeric|min:0', // Added validation for deliveryFee
            'total' => 'required|numeric|min:0', // Added validation for total

            // Payment details validation based on payment method
            'cardNumber' => 'required_if:paymentMethod,card|string|max:255',
            'cardExpiry' => 'required_if:paymentMethod,card|string|max:10',
            'cardCVC' => 'required_if:paymentMethod,card|string|max:4',
            'cardName' => 'required_if:paymentMethod,card|string|max:255',
            'gcashNumber' => 'required_if:paymentMethod,gcash|string|max:20',

            'orderNotes' => 'nullable|string|max:1000', // Optional notes
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Assume user is authenticated and retrieve user_id
            $userId = auth()->id(); // Or get from $request->user()->id if using Sanctum

            // 4. Create the Order
            $order = Order::create([
                'user_id' => $userId, // Associate with the authenticated user
                'customer_first_name' => $request->input('customerInfo.firstName'), // Map from nested input
                'customer_last_name' => $request->input('customerInfo.lastName'),   // Map from nested input
                'customer_email' => $request->input('customerInfo.email'),
                'customer_phone' => $request->input('customerInfo.phone'),
                'shipping_street' => $request->input('shippingAddress.street'),     // Map from nested input
                'shipping_barangay' => $request->input('shippingAddress.barangay'),
                'shipping_city' => $request->input('shippingAddress.city'),
                'shipping_province' => $request->input('shippingAddress.province'),
                'shipping_zip_code' => $request->input('shippingAddress.zipCode'),
                'payment_method' => $request->input('paymentMethod'),
                'order_notes' => $request->input('orderNotes'), // Corrected to match frontend and model
                'subtotal' => $request->input('subtotal'),
                'delivery_fee' => $request->input('deliveryFee'),
                'total_amount' => $request->input('total'), // Corrected to match model's 'total_amount'
                'status' => 'pending', // Initial status
                // Add card/gcash details if your table has them and you want to store them
                'card_number' => $request->input('cardNumber'),
                'card_expiry' => $request->input('cardExpiry'),
                'card_cvc' => $request->input('cardCVC'),
                'card_name' => $request->input('cardName'),
                'gcash_number' => $request->input('gcashNumber'),
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
                    'product_id' => $product->id, // Link to actual product
                    'product_name' => $item['name'], // Store name for historical purposes
                    'price_per_unit' => $item['price'], // Adjusted to match common column name
                    'quantity' => $item['quantity'],
                ]);

                // Decrement stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // 6. Return success response
            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('items') // Load order items for response
            ], 201); // 201 Created

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
        $orders = $user->orders()->with('orderItems.product')->get(); // Eager load order items and their products
        return response()->json($orders);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order)
    {
        // Ensure the authenticated user owns the order (or is an admin/merchant etc.)
        if ($request->user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($order->load('orderItems.product')); // Load order items and product details
    }
}