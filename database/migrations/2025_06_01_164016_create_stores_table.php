<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            // Added user_id foreign key to link store to its owner
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('owner'); // You might eventually remove this if owner name is pulled from User model
            $table->string('avatar')->nullable();
            // Changed to integer for proper numerical storage
            $table->integer('products_count')->default(0);
            // Changed to decimal for proper numerical rating storage
            $table->decimal('rating', 3, 2)->default(0.00); // e.g., 4.50
            $table->string('category'); // Consider a category_id foreign key to a 'categories' table
            $table->text('description')->nullable();
            $table->boolean('is_open')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};