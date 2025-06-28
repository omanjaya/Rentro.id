<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
                <p class="text-gray-600 mt-1">{{ $product->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Products
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Edit Product
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <p class="text-gray-900">{{ $product->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-gray-900">{{ $product->category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price per Day</label>
                            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($product->price_per_day) }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Available</label>
                            <p class="text-gray-900">{{ $product->stock }} units</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($product->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                            <p class="text-gray-900">{{ $product->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($product->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Specifications -->
                @if($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                        
                        <div class="space-y-3">
                            @foreach($product->specifications as $key => $value)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="text-gray-900">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Rental History -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Rentals</h3>
                    
                    @if($product->rentals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rental Code</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($product->rentals()->with('user')->latest()->take(5)->get() as $rental)
                                        <tr>
                                            <td class="px-3 py-2 text-sm">
                                                <a href="{{ route('admin.rentals.show', $rental) }}" class="text-primary-600 hover:text-primary-800">
                                                    {{ $rental->rental_code }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-sm">{{ $rental->user->name }}</td>
                                            <td class="px-3 py-2 text-sm">{{ $rental->total_days }} days</td>
                                            <td class="px-3 py-2 text-sm">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($rental->status === 'active') bg-green-100 text-green-800
                                                    @elseif($rental->status === 'completed') bg-gray-100 text-gray-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($rental->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No rentals yet for this product.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Product Image -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>
                    
                    @if($product->image)
                        <img src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}" 
                             loading="lazy"
                             onerror="this.src='https://via.placeholder.com/500x300?text=No+Image'"
                             class="w-full rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    @if($product->gallery && is_array($product->gallery) && count($product->gallery) > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Gallery Images</h4>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($product->gallery_urls as $galleryImage)
                                    <img src="{{ $galleryImage }}" 
                                         alt="{{ $product->name }}" 
                                         loading="lazy"
                                         onerror="this.src='https://via.placeholder.com/150x150?text=No+Image'"
                                         class="w-full h-20 object-cover rounded">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Rentals</span>
                            <span class="font-medium text-gray-900">{{ $product->rentals->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Rentals</span>
                            <span class="font-medium text-gray-900">
                                {{ $product->rentals->where('status', 'active')->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Revenue</span>
                            <span class="font-medium text-gray-900">
                                Rp {{ number_format($product->rentals->sum('total_price')) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md font-medium text-center block transition-colors">
                            Edit Product
                        </a>
                        
                        <a href="{{ route('products.show', $product) }}" 
                           target="_blank"
                           class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md font-medium text-center block transition-colors">
                            View Public Page
                        </a>
                        
                        @if($product->status === 'active')
                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to deactivate this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                                    Deactivate Product
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>