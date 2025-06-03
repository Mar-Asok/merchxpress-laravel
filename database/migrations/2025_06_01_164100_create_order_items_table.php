<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // Changed product_id to foreignId to link to the 'products' table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Kept for historical record, even if product name changes later
            $table->decimal('price', 10, 2); // Price at the time of purchase (important for historical accuracy)
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};