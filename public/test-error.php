<?php
// Temporary error diagnostic script
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    echo "✅ Laravel booted successfully\n\n";
    
    // Check database connection
    try {
        $pdo = DB::connection()->getPdo();
        echo "✅ Database connected\n";
        echo "Database: " . DB::connection()->getDatabaseName() . "\n\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    }
    
    // Check admin user
    try {
        $admin = \App\Models\User::where('user_type', 'admin')->first();
        if ($admin) {
            echo "✅ Admin user exists: " . $admin->email . "\n\n";
        } else {
            echo "❌ No admin user found\n\n";
        }
    } catch (Exception $e) {
        echo "❌ Error checking admin user: " . $e->getMessage() . "\n\n";
    }
    
    // Check Vite manifest
    $manifest = public_path('build/manifest.json');
    if (file_exists($manifest)) {
        echo "✅ Vite manifest exists\n";
        echo "Manifest content: " . substr(file_get_contents($manifest), 0, 200) . "...\n\n";
    } else {
        echo "❌ Vite manifest missing at: " . $manifest . "\n\n";
    }
    
    // Check environment
    echo "Environment: " . app()->environment() . "\n";
    echo "Debug: " . (config('app.debug') ? 'ON' : 'OFF') . "\n";
    echo "URL: " . config('app.url') . "\n";
    
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString();
}