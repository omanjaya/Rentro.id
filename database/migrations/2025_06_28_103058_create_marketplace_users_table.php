<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the users table already has the marketplace columns
        if (!Schema::hasColumn('users', 'user_type')) {
            // Add marketplace columns to existing users table
            Schema::table('users', function (Blueprint $table) {
                // Add user type column
                $table->enum('user_type', ['individual', 'business', 'vendor', 'admin'])->default('individual')->after('address');
                
                // Business-related fields
                $table->string('business_name')->nullable()->after('avatar');
                $table->string('business_license')->nullable()->after('business_name');
                $table->text('business_description')->nullable()->after('business_license');
                
                // Verification system
                $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('business_description');
                $table->timestamp('verified_at')->nullable()->after('verification_status');
                $table->text('verification_notes')->nullable()->after('verified_at');
                
                // Additional vendor fields
                $table->decimal('commission_rate', 5, 2)->default(15.00)->after('verification_notes');
                $table->boolean('featured_vendor')->default(false)->after('commission_rate');
            });
            
            // Update existing data
            DB::statement("UPDATE users SET user_type = 'admin' WHERE role = 'admin'");
            DB::statement("UPDATE users SET user_type = 'individual' WHERE role = 'customer'");
            DB::statement("UPDATE users SET verification_status = 'verified' WHERE role = 'admin'");
            
            // Drop the old role column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back role column
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('address');
            
            // Drop marketplace columns
            $table->dropColumn([
                'user_type',
                'business_name',
                'business_license', 
                'business_description',
                'verification_status',
                'verified_at',
                'verification_notes',
                'commission_rate',
                'featured_vendor'
            ]);
        });
        
        // Restore role data
        DB::statement("UPDATE users SET role = 'admin' WHERE user_type = 'admin'");
        DB::statement("UPDATE users SET role = 'customer' WHERE user_type != 'admin'");
    }
};