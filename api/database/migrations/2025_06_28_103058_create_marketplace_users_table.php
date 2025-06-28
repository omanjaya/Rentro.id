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
        // Create new users table with marketplace structure
        Schema::create('users_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('user_type', ['individual', 'business', 'vendor', 'admin'])->default('individual');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('avatar')->nullable();
            
            // Business-related fields
            $table->string('business_name')->nullable();
            $table->string('business_license')->nullable();
            $table->text('business_description')->nullable();
            
            // Verification system
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Additional vendor fields
            $table->decimal('commission_rate', 5, 2)->default(15.00);
            $table->boolean('featured_vendor')->default(false);
            
            $table->rememberToken();
            $table->timestamps();
        });

        // Copy data from old table, converting role to user_type
        DB::statement("
            INSERT INTO users_new (
                id, name, email, email_verified_at, password, phone, address, 
                user_type, status, avatar, verification_status, commission_rate, 
                featured_vendor, remember_token, created_at, updated_at
            )
            SELECT 
                id, name, email, email_verified_at, password, phone, address,
                CASE 
                    WHEN role = 'admin' THEN 'admin'
                    ELSE 'individual'
                END as user_type,
                'active' as status,
                avatar,
                CASE 
                    WHEN role = 'admin' THEN 'verified'
                    ELSE 'pending'
                END as verification_status,
                15.00 as commission_rate,
                0 as featured_vendor,
                remember_token, created_at, updated_at
            FROM users
        ");

        // Drop old table
        Schema::dropIfExists('users');
        
        // Rename new table
        Schema::rename('users_new', 'users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create old users table structure
        Schema::create('users_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Copy data back, converting user_type to role
        DB::statement("
            INSERT INTO users_old (
                id, name, email, email_verified_at, password, phone, address, 
                role, avatar, remember_token, created_at, updated_at
            )
            SELECT 
                id, name, email, email_verified_at, password, phone, address,
                CASE 
                    WHEN user_type = 'admin' THEN 'admin'
                    ELSE 'customer'
                END as role,
                avatar, remember_token, created_at, updated_at
            FROM users
        ");

        // Drop current table and rename old one back
        Schema::dropIfExists('users');
        Schema::rename('users_old', 'users');
    }
};