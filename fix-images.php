<?php
/**
 * Fix placeholder images in the database
 * Run this script to replace any placeholder images with real Unsplash images
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Set up database connection
use App\Models\Product;

// Real images by category
$realImages = [
    1 => [ // Laptop & Computer
        'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1587614203976-365c74645e83?w=500&h=300&fit=crop'
    ],
    2 => [ // Camera & Photography
        'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1581591524425-c7e0978865fc?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1567450156085-b5ecfad4c3e3?w=500&h=300&fit=crop'
    ],
    3 => [ // Audio & Sound
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1558618666-fbd647c5cd82?w=500&h=300&fit=crop'
    ],
    4 => [ // Gaming
        'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1592840062661-eb5d9bc05499?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1601656002819-0d27847c3c82?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=500&h=300&fit=crop'
    ],
    5 => [ // Mobile & Tablet
        'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=500&h=300&fit=crop',
        'https://images.unsplash.com/photo-1512499617640-c74ae3a79d37?w=500&h=300&fit=crop'
    ]
];

// Update products with placeholder images
$products = Product::where('image', 'LIKE', '%placeholder%')->get();

echo "Found " . $products->count() . " products with placeholder images.\n";

foreach ($products as $product) {
    $categoryId = $product->category_id ?? 1;
    $images = $realImages[$categoryId] ?? $realImages[1];
    $randomImage = $images[array_rand($images)];
    
    $product->update(['image' => $randomImage]);
    echo "Updated product: {$product->name}\n";
}

echo "All placeholder images have been replaced!\n";