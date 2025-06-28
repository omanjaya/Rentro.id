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
            // Add vendor relationship - null means platform-owned products
            $table->foreignId('vendor_id')->nullable()->after('category_id')->constrained('users')->onDelete('cascade');
            
            // Additional marketplace fields
            $table->enum('listing_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('status');
            $table->text('rejection_reason')->nullable()->after('listing_status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->decimal('vendor_price', 10, 2)->nullable()->after('price_per_day'); // Vendor's earning per day
            $table->boolean('featured')->default(false)->after('vendor_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn([
                'vendor_id', 'listing_status', 'rejection_reason', 
                'approved_at', 'vendor_price', 'featured'
            ]);
        });
    }
};
