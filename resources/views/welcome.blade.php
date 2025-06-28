<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Rentro.id') }} - Electronic Equipment Rental</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Image fallback handler -->
        <script src="{{ asset('js/image-fallback.js') }}"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-primary-600">
                            Rentro.id
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">Products</a>
                        
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-primary-600 to-primary-800 overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                                <span class="block">Rent Electronics</span>
                                <span class="block text-primary-200">Made Simple</span>
                            </h1>
                            <p class="mt-3 text-base text-primary-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Get access to the latest electronic equipment without the hefty purchase price. From cameras to laptops, we have everything you need for your projects.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                        Browse Products
                                    </a>
                                </div>
                                @guest
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600 md:py-4 md:text-lg md:px-10">
                                        Get Started
                                    </a>
                                </div>
                                @endguest
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <div class="h-56 w-full bg-gradient-to-br from-primary-500 to-primary-700 sm:h-72 md:h-96 lg:w-full lg:h-full flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg class="mx-auto h-24 w-24 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium">Professional Equipment</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        @if($categories->count() > 0)
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Popular Categories
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Find the perfect equipment for your needs
                    </p>
                    <p class="mt-2 text-sm text-gray-400">
                        Click on any category to browse available products
                    </p>
                </div>
                
                <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-{{ min($categories->count(), 6) }}">
                    @foreach($categories->take(6) as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="group relative bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <div class="text-center">
                            @if($category->icon)
                                <div class="mx-auto h-10 w-10 text-primary-600 mb-4">
                                    {!! $category->icon !!}
                                </div>
                            @else
                                <div class="mx-auto h-10 w-10 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-primary-600">{{ $category->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Featured Products -->
        @if($featuredProducts->count() > 0)
        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Featured Products
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Our most popular rental items
                    </p>
                </div>
                
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     loading="lazy" 
                                     onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=500&h=300&fit=crop'"
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-lg font-bold text-primary-600">
                                    Rp {{ number_format($product->price_per_day) }}/day
                                </p>
                                @if($product->stock > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="w-full bg-primary-600 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-700 transition-colors inline-block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8 text-center">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-primary-300 text-base font-medium rounded-lg text-primary-700 bg-white hover:bg-primary-50">
                        View All Products
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <footer class="bg-gray-900">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-white">Rentro.id</span>
                        </div>
                        <p class="mt-4 text-gray-300 text-sm">
                            Professional electronic equipment rental service. Get access to the latest technology without the commitment of purchase.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Quick Links</h3>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white block text-sm">Browse Products</a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white block text-sm">My Dashboard</a>
                                <a href="{{ route('user.rentals') }}" class="text-gray-300 hover:text-white block text-sm">My Rentals</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white block text-sm">Login</a>
                                <a href="{{ route('register') }}" class="text-gray-300 hover:text-white block text-sm">Register</a>
                            @endauth
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Contact</h3>
                        <div class="mt-4 space-y-2 text-gray-300 text-sm">
                            <p>Email: info@rentro.id</p>
                            <p>Phone: +62 xxx xxxx xxxx</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-gray-700 pt-8">
                    <p class="text-center text-gray-400 text-sm">
                        © {{ date('Y') }} Rentro.id. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>