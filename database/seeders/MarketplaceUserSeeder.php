<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MarketplaceUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create vendor users
        User::create([
            'name' => 'TechRent Store',
            'email' => 'vendor@techrent.com',
            'password' => Hash::make('password'),
            'user_type' => 'vendor',
            'business_name' => 'TechRent Electronics',
            'business_description' => 'Professional electronics rental service specializing in cameras, laptops, and audio equipment. Serving photographers and content creators.',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'commission_rate' => 12.00, // Lower commission for established vendor
            'featured_vendor' => true,
            'phone' => '+62 812 3456 7890',
            'address' => 'Jl. Teknologi No. 123, Jakarta Selatan',
        ]);

        User::create([
            'name' => 'GamerHub Rental',
            'email' => 'vendor@gamerhub.com',
            'password' => Hash::make('password'),
            'user_type' => 'vendor',
            'business_name' => 'GamerHub Equipment',
            'business_description' => 'Gaming equipment rental specialist. We provide gaming consoles, VR headsets, and high-end gaming accessories.',
            'verification_status' => 'pending',
            'commission_rate' => 15.00,
            'featured_vendor' => false,
            'phone' => '+62 813 9876 5432',
            'address' => 'Jl. Gaming Center No. 456, Bandung',
        ]);

        // Create business users
        User::create([
            'name' => 'StartupTech Solutions',
            'email' => 'business@startuptech.com',
            'password' => Hash::make('password'),
            'user_type' => 'business',
            'business_name' => 'StartupTech Solutions',
            'business_description' => 'Technology startup focusing on software development and digital solutions.',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'phone' => '+62 821 1111 2222',
            'address' => 'Jl. Startup Hub No. 789, Jakarta Pusat',
        ]);

        User::create([
            'name' => 'Creative Agency Pro',
            'email' => 'business@creativeagency.com',
            'password' => Hash::make('password'),
            'user_type' => 'business',
            'business_name' => 'Creative Agency Pro',
            'business_description' => 'Full-service creative agency specializing in video production, photography, and digital marketing.',
            'verification_status' => 'pending',
            'phone' => '+62 822 3333 4444',
            'address' => 'Jl. Creative District No. 321, Yogyakarta',
        ]);

        // Add more individual customers
        User::create([
            'name' => 'Ahmad Photographer',
            'email' => 'ahmad@photographer.com',
            'password' => Hash::make('password'),
            'user_type' => 'individual',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'phone' => '+62 831 5555 6666',
            'address' => 'Jl. Fotografi No. 111, Surabaya',
        ]);

        User::create([
            'name' => 'Sarah Content Creator',
            'email' => 'sarah@content.com',
            'password' => Hash::make('password'),
            'user_type' => 'individual',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'phone' => '+62 832 7777 8888',
            'address' => 'Jl. Content Creator No. 222, Bali',
        ]);
    }
}