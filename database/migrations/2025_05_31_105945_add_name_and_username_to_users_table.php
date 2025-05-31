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
        Schema::table('users', function (Blueprint $table) {
            // Add new columns (before 'email' is a good spot for these)
            $table->string('first_name')->nullable()->after('id'); // Make nullable initially if existing users might not have it
            $table->string('last_name')->nullable()->after('first_name'); // Make nullable initially
            $table->string('username')->unique()->after('email'); // Ensure username is unique

            // If you want to remove the default 'name' column:
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert changes if you rollback
            $table->dropColumn(['first_name', 'last_name', 'username']);
            // Add back the default 'name' column if you dropped it
            $table->string('name')->after('id');
        });
    }
};