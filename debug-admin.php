<?php
/**
 * Debug script to check admin access
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;

echo "=== Admin User Check ===\n";

// Check if admin user exists
$admin = User::where('user_type', 'admin')->first();

if ($admin) {
    echo "✅ Admin user found:\n";
    echo "- ID: {$admin->id}\n";
    echo "- Name: {$admin->name}\n";
    echo "- Email: {$admin->email}\n";
    echo "- User Type: {$admin->user_type}\n";
    echo "- Is Admin: " . ($admin->isAdmin() ? 'Yes' : 'No') . "\n";
} else {
    echo "❌ No admin user found!\n";
    echo "Creating admin user...\n";
    
    $admin = User::create([
        'name' => 'Admin Rentro',
        'email' => 'admin@rentro.id',
        'password' => bcrypt('password'),
        'user_type' => 'admin',
        'verification_status' => 'verified',
        'verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin user created!\n";
}

echo "\n=== Route Check ===\n";
echo "Admin dashboard route: " . route('admin.dashboard') . "\n";

echo "\n=== Middleware Check ===\n";
try {
    $middleware = app(\App\Http\Middleware\AdminMiddleware::class);
    echo "✅ AdminMiddleware class exists\n";
} catch (Exception $e) {
    echo "❌ AdminMiddleware error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";