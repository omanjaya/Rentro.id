<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics (cache for 5 minutes)
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'total_users' => User::count(),
                'new_users_this_month' => $this->getNewUsersThisMonth(),
                'total_rentals' => Rental::count(),
                'active_rentals' => Rental::where('status', 'active')->count(),
                'pending_rentals' => Rental::where('status', 'pending')->count(),
                'completed_rentals' => Rental::where('status', 'completed')->count(),
                'revenue_this_month' => $this->getRevenueThisMonth(),
                'revenue_today' => $this->getRevenueToday(),
            ];
        });

        // Get recent rentals (cache for 2 minutes)
        $recentRentals = Cache::remember('admin_recent_rentals', 120, function () {
            return Rental::with(['user:id,name,email', 'product:id,name,image'])
                ->select('id', 'user_id', 'product_id', 'rental_code', 'total_price', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // Get top products (cache for 30 minutes)
        $topProducts = Cache::remember('admin_top_products', 1800, function () {
            return Product::with('category:id,name')
                ->withCount('rentals')
                ->select('id', 'category_id', 'name', 'price_per_day')
                ->orderBy('rentals_count', 'desc')
                ->limit(5)
                ->get();
        });

        // Get recent users (cache for 2 minutes)
        $recentUsers = Cache::remember('admin_recent_users', 120, function () {
            return User::select('id', 'name', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // Get rental statistics by month (cache for 1 hour, with fallback)
        try {
            $rentalStats = Cache::remember('admin_rental_stats_' . Carbon::now()->year, 3600, function () {
                return $this->getRentalStatsByMonth();
            });
        } catch (\Exception $e) {
            $rentalStats = collect();
        }

        // Get revenue statistics by month (cache for 1 hour, with fallback)
        try {
            $revenueStats = Cache::remember('admin_revenue_stats_' . Carbon::now()->year, 3600, function () {
                return $this->getRevenueStatsByMonth();
            });
        } catch (\Exception $e) {
            $revenueStats = collect();
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recentRentals', 
            'recentUsers',
            'topProducts', 
            'rentalStats', 
            'revenueStats'
        ));
    }

    private function getRevenueThisMonth()
    {
        return Rental::whereRaw("strftime('%Y-%m', created_at) = ?", [Carbon::now()->format('Y-m')])
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');
    }

    private function getRevenueToday()
    {
        return Rental::whereRaw("strftime('%Y-%m-%d', created_at) = ?", [Carbon::today()->format('Y-m-d')])
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');
    }

    private function getNewUsersThisMonth()
    {
        return User::whereRaw("strftime('%Y-%m', created_at) = ?", [Carbon::now()->format('Y-m')])
            ->count();
    }

    private function getRentalStatsByMonth()
    {
        return Rental::select(
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw('COUNT(*) as count')
            )
            ->whereRaw("strftime('%Y', created_at) = ?", [Carbon::now()->year])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('M') => $item->count];
            });
    }

    private function getRevenueStatsByMonth()
    {
        return Rental::select(
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw('SUM(total_price) as revenue')
            )
            ->whereRaw("strftime('%Y', created_at) = ?", [Carbon::now()->year])
            ->whereIn('status', ['active', 'completed'])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('M') => $item->revenue];
            });
    }
}
