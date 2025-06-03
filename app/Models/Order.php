<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'shipping_street',
        'shipping_barangay',
        'shipping_city',
        'shipping_province',
        'shipping_zip_code',
        'payment_method',
        'subtotal',
        'delivery_fee',
        'total_amount', // This matches the column name
        'order_notes',
        'status',
        // Add other fillable fields if your orders table has them (e.g., payment details)
        'card_number',
        'card_expiry',
        'card_cvc',
        'card_name',
        'gcash_number',
    ];

    /**
     * Get the order items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the user (customer) that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}