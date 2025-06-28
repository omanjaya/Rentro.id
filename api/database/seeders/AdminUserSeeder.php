<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Rentro',
            'email' => 'admin@rentro.id',
            'password' => Hash::make('password'),
            'phone' => '+62812345678',
            'address' => 'Jl. Admin No. 1, Jakarta, Indonesia',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample customer users
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'phone' => '+62811234567',
                'address' => 'Jl. Customer No. 1, Jakarta, Indonesia',
                'role' => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'phone' => '+62812345679',
                'address' => 'Jl. Customer No. 2, Bandung, Indonesia',
                'role' => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'phone' => '+62813456789',
                'address' => 'Jl. Customer No. 3, Surabaya, Indonesia',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
