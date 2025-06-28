<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VendorApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'vendor');
        
        // Filter by verification status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('business_name', 'like', '%' . $search . '%');
            });
        }
        
        $vendors = $query->select(
            'id', 'name', 'email', 'business_name', 'business_description', 
            'verification_status', 'verified_at', 'commission_rate', 'featured_vendor',
            'phone', 'address', 'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        // Statistics for dashboard cards
        $stats = Cache::remember('vendor_approval_stats', 300, function () {
            return [
                'total_vendors' => User::where('user_type', 'vendor')->count(),
                'pending_approval' => User::where('user_type', 'vendor')->where('verification_status', 'pending')->count(),
                'verified_vendors' => User::where('user_type', 'vendor')->where('verification_status', 'verified')->count(),
                'rejected_vendors' => User::where('user_type', 'vendor')->where('verification_status', 'rejected')->count(),
            ];
        });
        
        return view('admin.vendor-approvals.index', compact('vendors', 'stats'));
    }

    public function show(User $vendor)
    {
        if ($vendor->user_type !== 'vendor') {
            abort(404, 'Vendor not found');
        }
        
        // Load vendor's products for review
        $vendor->load(['vendorProducts' => function($query) {
            $query->select('id', 'vendor_id', 'name', 'price_per_day', 'listing_status', 'status', 'created_at')
                  ->orderBy('created_at', 'desc')
                  ->limit(10);
        }]);
        
        // Get vendor's rental statistics
        $rentalStats = [
            'total_rentals' => $vendor->vendorProducts()->withCount('rentals')->get()->sum('rentals_count'),
            'total_revenue' => $this->calculateVendorRevenue($vendor),
            'avg_commission' => $vendor->commission_rate,
        ];
        
        return view('admin.vendor-approvals.show', compact('vendor', 'rentalStats'));
    }

    public function approve(User $vendor)
    {
        if ($vendor->user_type !== 'vendor') {
            abort(404, 'Vendor not found');
        }
        
        $vendor->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
        
        // Auto-approve pending products from verified vendors
        $vendor->vendorProducts()
               ->where('listing_status', 'pending')
               ->update(['listing_status' => 'approved']);
        
        // Clear related caches
        Cache::forget('vendor_approval_stats');
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->back()->with('success', "Vendor {$vendor->business_name} has been approved successfully!");
    }

    public function reject(Request $request, User $vendor)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        if ($vendor->user_type !== 'vendor') {
            abort(404, 'Vendor not found');
        }
        
        $vendor->update([
            'verification_status' => 'rejected',
            'verification_notes' => $request->rejection_reason,
        ]);
        
        // Reject all pending products from rejected vendors
        $vendor->vendorProducts()
               ->where('listing_status', 'pending')
               ->update(['listing_status' => 'rejected']);
        
        // Clear related caches
        Cache::forget('vendor_approval_stats');
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->back()->with('success', "Vendor {$vendor->business_name} has been rejected.");
    }

    public function toggleFeatured(User $vendor)
    {
        if ($vendor->user_type !== 'vendor' || $vendor->verification_status !== 'verified') {
            abort(404, 'Vendor not found or not verified');
        }
        
        $vendor->update([
            'featured_vendor' => !$vendor->featured_vendor,
        ]);
        
        $status = $vendor->featured_vendor ? 'featured' : 'unfeatured';
        
        return redirect()->back()->with('success', "Vendor {$vendor->business_name} has been {$status}!");
    }

    private function calculateVendorRevenue($vendor)
    {
        return $vendor->vendorProducts()
                     ->with(['rentals' => function($query) {
                         $query->whereIn('status', ['active', 'completed']);
                     }])
                     ->get()
                     ->sum(function ($product) {
                         return $product->rentals->sum(function ($rental) use ($product) {
                             $commissionAmount = $product->getCommissionAmount($rental->total_price);
                             return $rental->total_price - $commissionAmount;
                         });
                     });
    }
}
