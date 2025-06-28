<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = \Illuminate\Support\Facades\Cache::remember('categories_with_count', 3600, function () {
        return Category::withCount('products')->orderBy('name')->get();
    });
    
    $featuredProducts = \Illuminate\Support\Facades\Cache::remember('featured_products', 1800, function () {
        return Product::with('category:id,name')
            ->select('id', 'category_id', 'name', 'slug', 'price_per_day', 'image', 'stock')
            ->where('status', 'active')
            ->where(function($q) {
                // Show platform products (no vendor) or approved vendor products
                $q->whereNull('vendor_id')
                  ->orWhere('listing_status', 'approved');
            })
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    });
    
    return view('welcome', compact('categories', 'featuredProducts'));
});

// Products public routes
Route::get('/products', function () {
    $query = Product::with('category:id,name')
        ->select('id', 'category_id', 'name', 'slug', 'description', 'price_per_day', 'image', 'stock')
        ->where('status', 'active')
        ->where(function($q) {
            // Show platform products (no vendor) or approved vendor products
            $q->whereNull('vendor_id')
              ->orWhere('listing_status', 'approved');
        });
    
    if (request()->filled('category')) {
        $query->where('category_id', request('category'));
    }
    
    if (request()->filled('search')) {
        $query->where(function($q) {
            $search = request('search');
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
    
    $products = $query->orderBy('created_at', 'desc')->paginate(12);
    $categories = \Illuminate\Support\Facades\Cache::remember('categories_with_count', 3600, function () {
        return Category::withCount('products')->orderBy('name')->get();
    });
    
    return view('products.index', compact('products', 'categories'));
})->name('products.index');

Route::get('/products/{product:slug}', function (Product $product) {
    $product->load('category');
    $relatedProducts = Product::with('category:id,name')
                             ->select('id', 'category_id', 'name', 'slug', 'price_per_day', 'image')
                             ->where('category_id', $product->category_id)
                             ->where('id', '!=', $product->id)
                             ->where('status', 'active')
                             ->orderBy('created_at', 'desc')
                             ->limit(4)
                             ->get();
    return view('products.show', compact('product', 'relatedProducts'));
})->name('products.show');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User rental management
    Route::get('/my-rentals', [DashboardController::class, 'rentals'])->name('user.rentals');
    
    // Booking routes
    Route::get('/book/{product:slug}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/book/{product:slug}', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/book/{product:slug}/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
    Route::get('/rental/{rental}', [BookingController::class, 'showRental'])->name('booking.rental');
    Route::post('/rental/{rental}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Products routes
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Categories routes (to be implemented)
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Rentals routes
    Route::resource('rentals', \App\Http\Controllers\Admin\RentalController::class);
    Route::post('rentals/{rental}/status', [\App\Http\Controllers\Admin\RentalController::class, 'updateStatus'])->name('rentals.update-status');
    Route::post('rentals/bulk-action', [\App\Http\Controllers\Admin\RentalController::class, 'bulkAction'])->name('rentals.bulk-action');
    
    // Users routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Vendor approval routes
    Route::get('vendor-approvals', [\App\Http\Controllers\Admin\VendorApprovalController::class, 'index'])->name('vendor-approvals.index');
    Route::get('vendor-approvals/{vendor}', [\App\Http\Controllers\Admin\VendorApprovalController::class, 'show'])->name('vendor-approvals.show');
    Route::post('vendor-approvals/{vendor}/approve', [\App\Http\Controllers\Admin\VendorApprovalController::class, 'approve'])->name('vendor-approvals.approve');
    Route::post('vendor-approvals/{vendor}/reject', [\App\Http\Controllers\Admin\VendorApprovalController::class, 'reject'])->name('vendor-approvals.reject');
    Route::post('vendor-approvals/{vendor}/featured', [\App\Http\Controllers\Admin\VendorApprovalController::class, 'toggleFeatured'])->name('vendor-approvals.featured');
});

// Vendor routes
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');
    
    // Vendor product management
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class);
    
    // Vendor rental management
    Route::get('/rentals', [\App\Http\Controllers\Vendor\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [\App\Http\Controllers\Vendor\RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/status', [\App\Http\Controllers\Vendor\RentalController::class, 'updateStatus'])->name('rentals.update-status');
    
    // Vendor profile and verification
    Route::get('/profile', [\App\Http\Controllers\Vendor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Vendor\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\Vendor\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/request-verification', [\App\Http\Controllers\Vendor\ProfileController::class, 'requestVerification'])->name('profile.request-verification');
    
    // Additional vendor product routes
    Route::post('/products/{product}/toggle-status', [\App\Http\Controllers\Vendor\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('/products/{product}/submit-approval', [\App\Http\Controllers\Vendor\ProductController::class, 'submitForApproval'])->name('products.submit-approval');
});

// Business routes
Route::middleware(['auth', 'business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Business\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';

// Debug performance route (temporary)
Route::get('/debug-performance', function() {
    $start = microtime(true);
    
    // Test basic operations
    $userCount = \App\Models\User::count();
    $productCount = \App\Models\Product::count();
    $categoryCount = \App\Models\Category::count();
    
    $dbTime = microtime(true);
    
    // Test query performance
    $products = \App\Models\Product::with('category')->take(5)->get();
    
    $end = microtime(true);
    
    return [
        'basic_query_time' => round(($dbTime - $start) * 1000, 2) . 'ms',
        'with_relations_time' => round(($end - $dbTime) * 1000, 2) . 'ms',
        'total_time' => round(($end - $start) * 1000, 2) . 'ms',
        'counts' => compact('userCount', 'productCount', 'categoryCount'),
        'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB'
    ];
});
