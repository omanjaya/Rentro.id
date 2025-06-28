# Rentro.id - Spesifikasi Lengkap Project

## 1. Project Overview
- **Nama**: Rentro.id
- **Tipe**: Website Sewa Barang Elektronik
- **Framework**: Laravel 12
- **Frontend**: Tailwind CSS + shadcn/ui
- **Database**: MySQL
- **Authentication**: Laravel Breeze

## 2. Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    avatar VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Categories Table
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Products Table
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    category_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    specifications JSON,
    price_per_day DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    gallery JSON,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

### Rentals Table
```sql
CREATE TABLE rentals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    product_id BIGINT UNSIGNED,
    rental_code VARCHAR(20) UNIQUE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## 3. Laravel Models

### User Model (app/Models/User.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'role', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
```

### Category Model (app/Models/Category.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

### Product Model (app/Models/Product.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'specifications',
        'price_per_day', 'stock', 'image', 'gallery', 'status'
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery' => 'array',
        'price_per_day' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function isAvailable($startDate, $endDate)
    {
        $conflictingRentals = $this->rentals()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })->count();

        return $conflictingRentals < $this->stock;
    }
}
```

### Rental Model (app/Models/Rental.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'rental_code', 'start_date', 'end_date',
        'total_days', 'price_per_day', 'total_price', 'status', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_day' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rental) {
            $rental->rental_code = 'RNT' . strtoupper(uniqid());
            $rental->total_days = Carbon::parse($rental->start_date)
                ->diffInDays(Carbon::parse($rental->end_date)) + 1;
            $rental->total_price = $rental->total_days * $rental->price_per_day;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

## 4. Controllers Structure

### HomeController (app/Http/Controllers/HomeController.php)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->take(6)->get();
        $featuredProducts = Product::with('category')
            ->where('status', 'active')
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
```

### ProductController (app/Http/Controllers/ProductController.php)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
```

### RentalController (app/Http/Controllers/RentalController.php)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isAvailable($request->start_date, $request->end_date)) {
            return back()->with('error', 'Produk tidak tersedia pada tanggal tersebut');
        }

        Rental::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price_per_day' => $product->price_per_day,
            'notes' => $request->notes,
        ]);

        return redirect()->route('rentals.index')
            ->with('success', 'Pesanan sewa berhasil dibuat');
    }

    public function index()
    {
        $rentals = Auth::user()->rentals()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rentals.index', compact('rentals'));
    }
}
```

### Admin Controllers (app/Http/Controllers/Admin/)

#### AdminDashboardController
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rental;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_users' => User::where('role', 'customer')->count(),
            'pending_rentals' => Rental::where('status', 'pending')->count(),
            'active_rentals' => Rental::where('status', 'active')->count(),
        ];

        $recentRentals = Rental::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRentals'));
    }
}
```

## 5. Routes (routes/web.php)
```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminRentalController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Auth routes
require __DIR__.'/auth.php';

// Customer protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/my-rentals', [RentalController::class, 'index'])->name('rentals.index');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('rentals', AdminRentalController::class);
});
```

## 6. Middleware (app/Http/Middleware/AdminMiddleware.php)
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
```

## 7. Seeders

### DatabaseSeeder (database/seeders/DatabaseSeeder.php)
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
```

### CategorySeeder (database/seeders/CategorySeeder.php)
```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop & Komputer', 'description' => 'Laptop, desktop, dan aksesoris komputer'],
            ['name' => 'Kamera & Fotografi', 'description' => 'Kamera DSLR, mirrorless, dan aksesoris fotografi'],
            ['name' => 'Audio & Speaker', 'description' => 'Speaker, headphone, dan peralatan audio'],
            ['name' => 'Gaming', 'description' => 'Konsol game, VR, dan aksesoris gaming'],
            ['name' => 'Smartphone & Tablet', 'description' => 'Smartphone, tablet, dan aksesoris mobile'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
```

## 8. Blade Templates Structure

### Layout (resources/views/layouts/app.blade.php)
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rentro.id') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        
        <main>
            {{ $slot }}
        </main>
        
        @include('layouts.footer')
    </div>
</body>
</html>
```

### Navigation (resources/views/layouts/navigation.blade.php)
```html
<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                        Rentro.id
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Products
                    </a>
                </div>
            </div>
            
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('rentals.index') }}" class="text-gray-500 hover:text-gray-700">
                                My Rentals
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                                    Admin
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
```

## 9. Package Requirements (composer.json dependencies)
```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0",
        "laravel/sanctum": "^4.0",
        "laravel/tinker": "^2.9",
        "laravel/breeze": "^2.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.26",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

## 10. Installation Commands
```bash
# Create Laravel project
composer create-project laravel/laravel rentro

# Install Breeze for authentication
composer require laravel/breeze --dev
php artisan breeze:install blade

# Install Tailwind CSS (already included with Breeze)
npm install

# Build assets
npm run build

# Setup database
php artisan migrate:fresh --seed

# Create admin user
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@rentro.id', 'password' => bcrypt('password'), 'role' => 'admin']);

# Start development server
php artisan serve
```

## 11. Key Features Implementation

### Search & Filter (Frontend)
- Product search by name
- Filter by category
- Price range filter (optional)
- Date availability check

### Rental System
- Date picker for rental period
- Automatic price calculation
- Stock availability check
- Rental status tracking

### Admin Panel
- CRUD operations for products/categories
- Rental management (approve/reject)
- Simple dashboard with statistics
- User management

### Security Features
- CSRF protection
- Input validation
- Role-based access control
- File upload validation

## 12. File Upload Configuration
```php
// config/filesystems.php - ensure public disk is configured
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

Run: `php artisan storage:link`

This specification provides everything needed for Claude Code to build the complete Rentro.id application.