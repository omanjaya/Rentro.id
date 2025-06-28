<?php
/**
 * Test script to check admin dashboard
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a mock request to admin dashboard
$request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');

// Set up admin authentication manually
$admin = App\Models\User::where('user_type', 'admin')->first();
if ($admin) {
    $request->setUserResolver(function () use ($admin) {
        return $admin;
    });
    
    // Set up auth manually for testing
    auth()->setUser($admin);
}

try {
    echo "Testing admin dashboard...\n";
    
    // Test the controller directly
    $controller = new App\Http\Controllers\Admin\DashboardController();
    $response = $controller->index();
    
    echo "✅ Admin dashboard loaded successfully!\n";
    echo "Response type: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getData')) {
        $data = $response->getData();
        echo "View data keys: " . implode(', ', array_keys($data)) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Admin dashboard error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}