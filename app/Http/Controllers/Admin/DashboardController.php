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
        try {
            // Get basic statistics (cache for 5 minutes)
            $stats = Cache::remember('admin_dashboard_stats', 300, function () {
                return [
                    'total_products' => Product::count(),
                    'total_categories' => Category::count(),
                    'total_customers' => User::whereIn('user_type', ['individual', 'business'])->count(),
                    'total_admins' => User::where('user_type', 'admin')->count(),
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
        } catch (\Exception $e) {
            // Fallback stats in case of database errors
            $stats = [
                'total_products' => 0,
                'total_categories' => 0,
                'total_customers' => 0,
                'total_admins' => 0,
                'total_users' => 0,
                'new_users_this_month' => 0,
                'total_rentals' => 0,
                'active_rentals' => 0,
                'pending_rentals' => 0,
                'completed_rentals' => 0,
                'revenue_this_month' => 0,
                'revenue_today' => 0,
            ];
            \Log::error('Admin dashboard stats error: ' . $e->getMessage());
        }

        // Get recent rentals (cache for 2 minutes)
        try {
            $recentRentals = Cache::remember('admin_recent_rentals', 120, function () {
                return Rental::with(['user:id,name,email', 'product:id,name,image'])
                    ->select('id', 'user_id', 'product_id', 'rental_code', 'total_price', 'status', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            });
        } catch (\Exception $e) {
            $recentRentals = collect();
            \Log::error('Admin recent rentals error: ' . $e->getMessage());
        }

        // Get top products (cache for 30 minutes)
        try {
            $topProducts = Cache::remember('admin_top_products', 1800, function () {
                return Product::with('category:id,name')
                    ->withCount('rentals')
                    ->select('id', 'category_id', 'name', 'price_per_day')
                    ->orderBy('rentals_count', 'desc')
                    ->limit(5)
                    ->get();
            });
        } catch (\Exception $e) {
            $topProducts = collect();
            \Log::error('Admin top products error: ' . $e->getMessage());
        }

        // Get recent users (cache for 2 minutes)
        try {
            $recentUsers = Cache::remember('admin_recent_users', 120, function () {
                return User::select('id', 'name', 'email', 'user_type', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            });
        } catch (\Exception $e) {
            $recentUsers = collect();
            \Log::error('Admin recent users error: ' . $e->getMessage());
        }

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
        return Rental::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [Carbon::now()->format('Y-m')])
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');
    }

    private function getRevenueToday()
    {
        return Rental::whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ?", [Carbon::today()->format('Y-m-d')])
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');
    }

    private function getNewUsersThisMonth()
    {
        return User::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [Carbon::now()->format('Y-m')])
            ->count();
    }

    private function getRentalStatsByMonth()
    {
        return Rental::select(
                DB::raw("DATE_FORMAT(created_at, '%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?", [Carbon::now()->year])
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
                DB::raw("DATE_FORMAT(created_at, '%m') as month"),
                DB::raw('SUM(total_price) as revenue')
            )
            ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?", [Carbon::now()->year])
            ->whereIn('status', ['active', 'completed'])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('M') => $item->revenue];
            });
    }
}
