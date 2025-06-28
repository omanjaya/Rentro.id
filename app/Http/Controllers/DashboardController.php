<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Middleware is handled in routes/web.php, no need for constructor

    /**
     * Show user dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirect users to their appropriate dashboards
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->isVendor()) {
            return redirect()->route('vendor.dashboard');
        }
        
        if ($user->isBusiness()) {
            return redirect()->route('business.dashboard');
        }

        // Get user's rental statistics
        $stats = [
            'total_rentals' => $user->rentals()->count(),
            'active_rentals' => $user->rentals()->where('status', 'active')->count(),
            'pending_rentals' => $user->rentals()->where('status', 'pending')->count(),
            'completed_rentals' => $user->rentals()->where('status', 'completed')->count(),
        ];

        // Get recent rentals
        $recentRentals = $user->rentals()
            ->with(['product.category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active rentals (currently being rented)
        $activeRentals = $user->rentals()
            ->with(['product.category'])
            ->where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('dashboard', compact('stats', 'recentRentals', 'activeRentals'));
    }

    /**
     * Show all user rentals
     */
    public function rentals(Request $request)
    {
        $query = auth()->user()->rentals()->with(['product.category']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by product name or rental code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rental_code', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $rentals = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('user.rentals', compact('rentals'));
    }
}
