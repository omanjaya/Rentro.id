<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $business = auth()->user();

        // Business customer statistics
        $stats = Cache::remember("business_stats_{$business->id}", 300, function () use ($business) {
            $rentals = $business->rentals();
            
            return [
                'total_rentals' => $rentals->count(),
                'active_rentals' => $rentals->where('status', 'active')->count(),
                'completed_rentals' => $rentals->where('status', 'completed')->count(),
                'pending_rentals' => $rentals->where('status', 'pending')->count(),
                'total_spent' => $rentals->whereIn('status', ['active', 'completed'])->sum('total_price'),
                'this_month_spent' => $this->calculateMonthlySpending($business),
                'this_year_spent' => $this->calculateYearlySpending($business),
                'average_rental_value' => $this->calculateAverageRentalValue($business),
            ];
        });

        // Recent rentals for business
        $recentRentals = Cache::remember("business_recent_rentals_{$business->id}", 120, function () use ($business) {
            return Rental::with(['product:id,name,price_per_day,image', 'product.category:id,name'])
                ->where('user_id', $business->id)
                ->select('id', 'product_id', 'rental_code', 'start_date', 'end_date', 'total_price', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // Upcoming rentals
        $upcomingRentals = Cache::remember("business_upcoming_rentals_{$business->id}", 120, function () use ($business) {
            return Rental::with(['product:id,name,price_per_day', 'product.category:id,name'])
                ->where('user_id', $business->id)
                ->where('status', 'active')
                ->where('start_date', '>', Carbon::now())
                ->select('id', 'product_id', 'rental_code', 'start_date', 'end_date', 'total_price', 'status')
                ->orderBy('start_date', 'asc')
                ->limit(3)
                ->get();
        });

        // Monthly spending trend (last 6 months)
        $monthlyTrend = $this->getMonthlySpendingTrend($business);

        return view('business.dashboard', compact('stats', 'recentRentals', 'upcomingRentals', 'business', 'monthlyTrend'));
    }

    private function calculateMonthlySpending($business)
    {
        return Rental::where('user_id', $business->id)
            ->whereIn('status', ['active', 'completed'])
            ->whereRaw("strftime('%Y-%m', created_at) = ?", [Carbon::now()->format('Y-m')])
            ->sum('total_price');
    }

    private function calculateYearlySpending($business)
    {
        return Rental::where('user_id', $business->id)
            ->whereIn('status', ['active', 'completed'])
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');
    }

    private function calculateAverageRentalValue($business)
    {
        $totalRentals = Rental::where('user_id', $business->id)
            ->whereIn('status', ['active', 'completed'])
            ->count();

        if ($totalRentals === 0) {
            return 0;
        }

        $totalSpent = Rental::where('user_id', $business->id)
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');

        return $totalSpent / $totalRentals;
    }

    private function getMonthlySpendingTrend($business)
    {
        $months = [];
        $spending = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $months[] = $date->format('M Y');
            $spending[] = Rental::where('user_id', $business->id)
                ->whereIn('status', ['active', 'completed'])
                ->whereRaw("strftime('%Y-%m', created_at) = ?", [$monthKey])
                ->sum('total_price');
        }

        return [
            'months' => $months,
            'spending' => $spending,
        ];
    }
}
