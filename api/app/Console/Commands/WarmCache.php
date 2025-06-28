<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:warm-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up application cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Warming up application cache...');

        // Warm up categories cache
        $this->info('Caching categories...');
        \Illuminate\Support\Facades\Cache::remember('categories_with_count', 3600, function () {
            return \App\Models\Category::withCount('products')->orderBy('name')->get();
        });

        // Warm up featured products cache
        $this->info('Caching featured products...');
        \Illuminate\Support\Facades\Cache::remember('featured_products', 1800, function () {
            return \App\Models\Product::with('category:id,name')
                ->select('id', 'category_id', 'name', 'slug', 'price_per_day', 'image')
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });

        // Warm up admin dashboard cache
        $this->info('Caching admin dashboard data...');
        \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_products' => \App\Models\Product::count(),
                'total_categories' => \App\Models\Category::count(),
                'total_customers' => \App\Models\User::where('role', 'customer')->count(),
                'total_rentals' => \App\Models\Rental::count(),
                'active_rentals' => \App\Models\Rental::where('status', 'active')->count(),
                'pending_rentals' => \App\Models\Rental::where('status', 'pending')->count(),
                'completed_rentals' => \App\Models\Rental::where('status', 'completed')->count(),
            ];
        });

        $this->info('Cache warming completed successfully!');
        return Command::SUCCESS;
    }
}
