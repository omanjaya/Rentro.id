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
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status']);
            $table->index('slug');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['product_id', 'status']);
            $table->index('rental_code');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex(['slug']);
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['product_id', 'status']);
            $table->dropIndex(['rental_code']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
