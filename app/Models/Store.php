<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Added for user relationship

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Added
        'name',
        'owner',
        'avatar',
        'products_count',
        'rating',
        'category',
        'description',
        'is_open',
        'is_featured',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'is_featured' => 'boolean',
        'products_count' => 'integer', // Updated cast
        'rating' => 'decimal:2',       // Updated cast
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the user (owner) that owns the store.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}