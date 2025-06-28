<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rental::with(['user', 'product.category']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rental_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $rentals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $rental->load(['user', 'product.category']);
        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Update rental status
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,active,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $rental->status;
        $newStatus = $request->status;

        // Business logic for status transitions
        if ($oldStatus === 'cancelled' || $oldStatus === 'completed') {
            return back()->with('error', 'Cannot change status of cancelled or completed rentals.');
        }

        if ($newStatus === 'active' && $oldStatus !== 'approved') {
            return back()->with('error', 'Rental must be approved before it can be activated.');
        }

        // Stock management logic
        $product = $rental->product;
        
        // When rental becomes active, decrease stock
        if ($newStatus === 'active' && $oldStatus !== 'active') {
            if ($product->stock < 1) {
                return back()->with('error', 'Product is out of stock and cannot be activated.');
            }
            $product->decrement('stock');
        }
        
        // When rental is completed or cancelled, restore stock (if it was previously active)
        if (in_array($newStatus, ['completed', 'cancelled']) && $oldStatus === 'active') {
            $product->increment('stock');
        }

        $rental->update([
            'status' => $newStatus,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Rental status updated to {$newStatus}.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        return view('admin.rentals.edit', compact('rental'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if dates can be modified (only if rental is pending or approved)
        if (!in_array($rental->status, ['pending', 'approved'])) {
            return back()->with('error', 'Cannot modify dates for active, completed, or cancelled rentals.');
        }

        // Recalculate total price
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $days * $rental->product->price_per_day;

        $rental->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.rentals.show', $rental)
            ->with('success', 'Rental updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        // Only allow deletion of pending rentals
        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rentals can be deleted.');
        }

        // If somehow an active rental needs to be deleted, restore stock first
        if ($rental->status === 'active') {
            $rental->product->increment('stock');
        }

        $rental->delete();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental deleted successfully.');
    }

    /**
     * Bulk actions for rentals
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'rental_ids' => 'required|array',
            'rental_ids.*' => 'exists:rentals,id'
        ]);

        $rentals = Rental::whereIn('id', $request->rental_ids)->get();

        foreach ($rentals as $rental) {
            switch ($request->action) {
                case 'approve':
                    if ($rental->status === 'pending') {
                        $rental->update(['status' => 'approved']);
                    }
                    break;
                case 'reject':
                    if ($rental->status === 'pending') {
                        $rental->update(['status' => 'cancelled']);
                    }
                    break;
                case 'delete':
                    if ($rental->status === 'pending') {
                        $rental->delete();
                    }
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully.');
    }
}
