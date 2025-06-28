<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user();

        // Vendor statistics
        $stats = Cache::remember("vendor_stats_{$vendor->id}", 300, function () use ($vendor) {
            $products = $vendor->vendorProducts();
            
            return [
                'total_products' => $products->count(),
                'active_products' => $products->where('status', 'active')->count(),
                'pending_products' => $products->where('listing_status', 'pending')->count(),
                'approved_products' => $products->where('listing_status', 'approved')->count(),
                'total_rentals' => Rental::whereIn('product_id', $products->pluck('id'))->count(),
                'active_rentals' => Rental::whereIn('product_id', $products->pluck('id'))
                    ->where('status', 'active')->count(),
                'total_revenue' => $this->calculateTotalRevenue($vendor),
                'this_month_revenue' => $this->calculateMonthlyRevenue($vendor),
            ];
        });

        // Recent rentals for vendor's products
        $recentRentals = Cache::remember("vendor_recent_rentals_{$vendor->id}", 120, function () use ($vendor) {
            return Rental::with(['user:id,name,email', 'product:id,name,vendor_id'])
                ->whereHas('product', function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                })
                ->select('id', 'user_id', 'product_id', 'rental_code', 'total_price', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // Vendor's top products
        $topProducts = Cache::remember("vendor_top_products_{$vendor->id}", 1800, function () use ($vendor) {
            return Product::where('vendor_id', $vendor->id)
                ->withCount('rentals')
                ->select('id', 'name', 'price_per_day')
                ->orderBy('rentals_count', 'desc')
                ->limit(5)
                ->get();
        });

        return view('vendor.dashboard', compact('stats', 'recentRentals', 'topProducts', 'vendor'));
    }

    private function calculateTotalRevenue($vendor)
    {
        return Rental::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->whereIn('status', ['active', 'completed'])
        ->get()
        ->sum(function ($rental) {
            // Calculate vendor's share after platform commission
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
        ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [Carbon::now()->format('Y-m')])
        ->get()
        ->sum(function ($rental) {
            $commissionAmount = $rental->product->getCommissionAmount($rental->total_price);
            return $rental->total_price - $commissionAmount;
        });
    }
}