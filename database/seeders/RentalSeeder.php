<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Create sample rentals with various statuses
        $rentals = [
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(25),
                'status' => 'completed',
                'notes' => 'Great product, worked perfectly for my project.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(15),
                'status' => 'completed',
                'notes' => 'Excellent condition, fast delivery.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(5),
                'status' => 'completed',
                'notes' => 'Perfect for my photography session.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(2),
                'status' => 'active',
                'notes' => 'Currently using for university project.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(4),
                'status' => 'active',
                'notes' => 'Gaming tournament this weekend.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(7),
                'status' => 'approved',
                'notes' => 'Conference presentation next week.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(10),
                'status' => 'approved',
                'notes' => 'Video production project.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(12),
                'status' => 'pending',
                'notes' => 'Need for upcoming presentation.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(14),
                'status' => 'pending',
                'notes' => 'Music production setup.',
            ],
            [
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(17),
                'status' => 'pending',
                'notes' => 'Mobile app development testing.',
            ],
        ];

        foreach ($rentals as $rentalData) {
            $product = Product::find($rentalData['product_id']);
            $startDate = Carbon::parse($rentalData['start_date']);
            $endDate = Carbon::parse($rentalData['end_date']);
            $totalDays = $startDate->diffInDays($endDate) + 1;
            
            Rental::create([
                'user_id' => $rentalData['user_id'],
                'product_id' => $rentalData['product_id'],
                'rental_code' => 'RNT' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'start_date' => $rentalData['start_date'],
                'end_date' => $rentalData['end_date'],
                'total_days' => $totalDays,
                'price_per_day' => $product->price_per_day,
                'total_price' => $totalDays * $product->price_per_day,
                'status' => $rentalData['status'],
                'notes' => $rentalData['notes'],
            ]);
        }

        $this->command->info('Created ' . count($rentals) . ' sample rentals');
    }
}
