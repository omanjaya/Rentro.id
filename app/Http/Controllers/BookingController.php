<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Models\Product;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Middleware is handled in routes/web.php

    /**
     * Show booking form for a product
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('booking.show', compact('product'));
    }

    /**
     * Check availability for specific dates
     */
    public function checkAvailability(Request $request, Product $product)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $isAvailable = $product->isAvailable($startDate, $endDate);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $days * $product->price_per_day;

        return response()->json([
            'available' => $isAvailable,
            'days' => $days,
            'price_per_day' => $product->price_per_day,
            'total_price' => $totalPrice,
            'formatted_total' => 'Rp ' . number_format($totalPrice),
        ]);
    }

    /**
     * Store a new rental booking
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Check availability
        if (!$product->isAvailable($startDate, $endDate)) {
            return back()->with('error', 'Product is not available for the selected dates.');
        }

        // Calculate rental details
        $days = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $days * $product->price_per_day;

        // Generate unique rental code
        $rentalCode = 'RNT-' . strtoupper(Str::random(8));
        while (Rental::where('rental_code', $rentalCode)->exists()) {
            $rentalCode = 'RNT-' . strtoupper(Str::random(8));
        }

        // Create rental
        $rental = Rental::create([
            'rental_code' => $rentalCode,
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $days,
            'price_per_day' => $product->price_per_day,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'phone' => $request->phone ?: auth()->user()->phone,
            'address' => $request->address ?: auth()->user()->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Rental booking submitted successfully! Your booking code is: {$rentalCode}");
    }

    /**
     * Show rental details
     */
    public function showRental(Rental $rental)
    {
        // Ensure user can only view their own rentals
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        $rental->load(['product.category']);
        return view('booking.rental', compact('rental'));
    }

    /**
     * Cancel a rental (only if pending)
     */
    public function cancel(Rental $rental)
    {
        // Ensure user can only cancel their own rentals
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rentals can be cancelled.');
        }

        $rental->update(['status' => 'cancelled']);

        return back()->with('success', 'Rental cancelled successfully.');
    }
}
