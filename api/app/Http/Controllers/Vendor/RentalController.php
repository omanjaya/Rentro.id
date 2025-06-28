<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth()->user();
        
        $query = Rental::with(['user:id,name,email,user_type', 'product:id,name,vendor_id'])
                      ->whereHas('product', function ($productQuery) use ($vendor) {
                          $productQuery->where('vendor_id', $vendor->id);
                      });
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rental_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $rentals = $query->select(
            'id', 'user_id', 'product_id', 'rental_code', 'start_date', 'end_date',
            'total_days', 'price_per_day', 'total_price', 'status', 'notes', 'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        // Calculate revenue for each rental (vendor's share after commission)
        $rentals->getCollection()->transform(function ($rental) {
            $rental->vendor_revenue = $rental->total_price - $rental->product->getCommissionAmount($rental->total_price);
            return $rental;
        });
        
        // Statistics for dashboard cards
        $stats = Cache::remember("vendor_rental_stats_{$vendor->id}", 300, function () use ($vendor) {
            $rentals = Rental::whereHas('product', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            });
            
            return [
                'total_rentals' => $rentals->count(),
                'pending_rentals' => $rentals->where('status', 'pending')->count(),
                'active_rentals' => $rentals->where('status', 'active')->count(),
                'completed_rentals' => $rentals->where('status', 'completed')->count(),
                'this_month_rentals' => $rentals->whereRaw("strftime('%Y-%m', created_at) = ?", [Carbon::now()->format('Y-m')])->count(),
                'total_revenue' => $this->calculateTotalRevenue($vendor),
                'this_month_revenue' => $this->calculateMonthlyRevenue($vendor),
            ];
        });
        
        return view('vendor.rentals.index', compact('rentals', 'stats'));
    }

    public function show(Rental $rental)
    {
        $this->authorizeVendorRental($rental);
        
        $rental->load([
            'user:id,name,email,phone,address,user_type,business_name',
            'product:id,name,description,price_per_day,vendor_id,image,specifications'
        ]);
        
        // Calculate vendor revenue (after platform commission)
        $rental->vendor_revenue = $rental->total_price - $rental->product->getCommissionAmount($rental->total_price);
        $rental->commission_amount = $rental->product->getCommissionAmount($rental->total_price);
        $rental->commission_rate = $rental->product->vendor->commission_rate ?? 15;
        
        // Parse specifications if available
        if ($rental->product->specifications) {
            $rental->product->parsed_specifications = json_decode($rental->product->specifications, true);
        }
        
        return view('vendor.rentals.show', compact('rental'));
    }

    public function updateStatus(Request $request, Rental $rental)
    {
        $this->authorizeVendorRental($rental);
        
        $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $oldStatus = $rental->status;
        
        // Business logic for status transitions
        $allowedTransitions = [
            'pending' => ['active', 'cancelled'],
            'active' => ['completed'],
            'completed' => [], // Completed rentals cannot be changed
            'cancelled' => [], // Cancelled rentals cannot be changed
        ];
        
        if (!in_array($request->status, $allowedTransitions[$oldStatus] ?? [])) {
            return redirect()->back()
                           ->with('error', "Cannot change status from {$oldStatus} to {$request->status}");
        }
        
        $updateData = ['status' => $request->status];
        
        // Add notes if provided
        if ($request->filled('notes')) {
            $existingNotes = $rental->notes ? $rental->notes . "\n\n" : '';
            $updateData['notes'] = $existingNotes . "[" . now()->format('Y-m-d H:i') . "] Vendor update: " . $request->notes;
        }
        
        // Stock management logic (same as Admin controller)
        $product = $rental->product;
        
        // When rental becomes active, decrease stock
        if ($request->status === 'active' && $oldStatus !== 'active') {
            if ($product->stock < 1) {
                return redirect()->back()->with('error', 'Product is out of stock and cannot be activated.');
            }
            $product->decrement('stock');
        }
        
        // When rental is completed or cancelled, restore stock (if it was previously active)
        if (in_array($request->status, ['completed', 'cancelled']) && $oldStatus === 'active') {
            $product->increment('stock');
        }
        
        $rental->update($updateData);
        
        // Clear vendor cache
        $vendor = auth()->user();
        Cache::forget("vendor_rental_stats_{$vendor->id}");
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->back()
                        ->with('success', "Rental status updated to {$request->status} successfully!");
    }

    private function authorizeVendorRental(Rental $rental)
    {
        if ($rental->product->vendor_id !== auth()->id()) {
            abort(403, 'You can only manage rentals for your own products.');
        }
    }

    private function calculateTotalRevenue($vendor)
    {
        return Rental::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->whereIn('status', ['active', 'completed'])
        ->get()
        ->sum(function ($rental) {
            $commissionAmount = $rental->product->getCommissionAmount($rental->total_price);
            return $rental->total_price - $commissionAmount;
        });
    }

    private function calculateMonthlyRevenue($vendor)
    {
        return Rental::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->whereIn('status', ['active', 'completed'])
        ->whereRaw("strftime('%Y-%m', created_at) = ?", [Carbon::now()->format('Y-m')])
        ->get()
        ->sum(function ($rental) {
            $commissionAmount = $rental->product->getCommissionAmount($rental->total_price);
            return $rental->total_price - $commissionAmount;
        });
    }
}
