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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Added user_id foreign key to link order to the customer who placed it
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Customer Information (kept for now, but often replaced by an 'addresses' table link)
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Shipping Address (kept for now, but often replaced by an 'addresses' table link)
            $table->string('shipping_street');
            $table->string('shipping_barangay');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_zip_code');

            // Payment Information
            $table->string('payment_method'); // 'cod', 'gcash', 'card'
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->text('order_notes')->nullable();
            $table->string('status')->default('pending'); // e.g., pending, processing, shipped, completed, cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};