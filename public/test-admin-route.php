<?php
// Test admin dashboard specifically
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    // Simulate admin login
    $admin = \App\Models\User::where('user_type', 'admin')->first();
    if ($admin) {
        auth()->login($admin);
        echo "✅ Logged in as admin: " . $admin->email . "\n\n";
    }
    
    // Test the admin dashboard controller directly
    try {
        $controller = new \App\Http\Controllers\Admin\DashboardController();
        $response = $controller->index();
        
        echo "✅ Admin dashboard controller executed successfully\n";
        echo "Response type: " . get_class($response) . "\n\n";
        
        // Try to render the view
        try {
            $content = $response->render();
            echo "✅ View rendered successfully\n";
            echo "Content length: " . strlen($content) . " bytes\n";
        } catch (Exception $e) {
            echo "❌ View rendering error: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
            
            // Check for missing components
            if (strpos($e->getMessage(), 'component') !== false) {
                echo "Checking component files...\n";
                $componentPath = resource_path('views/components/admin-layout.blade.php');
                echo "Component exists: " . (file_exists($componentPath) ? 'YES' : 'NO') . "\n";
                echo "Component path: " . $componentPath . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Controller error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
    // Check component registration
    echo "\n=== Component Check ===\n";
    $componentClass = 'App\\View\\Components\\AdminLayout';
    if (class_exists($componentClass)) {
        echo "✅ AdminLayout component class exists\n";
    } else {
        echo "❌ AdminLayout component class missing\n";
        echo "Expected at: app/View/Components/AdminLayout.php\n";
    }
    
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}