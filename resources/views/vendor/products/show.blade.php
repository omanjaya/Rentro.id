<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Product details and performance analytics
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('vendor.products.edit', $product) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Edit Product
                </a>
                <a href="{{ route('vendor.products.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Product Status Alert -->
            @if($product->listing_status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Product Rejected</h3>
                            <p class="mt-1 text-sm text-red-700">
                                This product was rejected by admin. Please review and make necessary changes before resubmitting.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($product->listing_status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Awaiting Approval</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                This product is currently under admin review for approval.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($product->listing_status === 'draft')
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Draft Product</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    This product is saved as a draft. Submit for approval to make it available to customers.
                                </p>
                            </div>
                            @if(auth()->user()->isVerified())
                                <form action="{{ route('vendor.products.submit-approval', $product) }}" method="POST" class="ml-4">
                                    @csrf
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Submit for Approval
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Images -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                            
                            @if($product->image)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Main Image</h4>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         class="w-full h-64 object-cover rounded-lg border border-gray-300">
                                </div>
                            @endif

                            @if($product->gallery)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gallery</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach(json_decode($product->gallery, true) as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" 
                                                 class="w-full h-32 object-cover rounded border border-gray-300">
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!$product->image && !$product->gallery)
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No images uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price per Day</dt>
                                <dd class="mt-1 text-sm font-bold text-primary-600">Rp {{ number_format($product->price_per_day) }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Available Stock</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->stock }} units</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($product->status === 'active') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Listing Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($product->listing_status === 'approved') bg-green-100 text-green-800
                                        @elseif($product->listing_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($product->listing_status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($product->listing_status) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>

                        @if($product->description)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                                <dd class="text-sm text-gray-900 leading-relaxed">{{ $product->description }}</dd>
                            </div>
                        @endif
                    </div>

                    <!-- Specifications -->
                    @if($product->specifications)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Specifications</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(json_decode($product->specifications, true) as $spec)
                                    @if(!empty($spec['key']) && !empty($spec['value']))
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">{{ $spec['key'] }}</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $spec['value'] }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Performance Statistics -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Performance</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Rentals</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($rentalStats['total_rentals']) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Active Rentals</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($rentalStats['active_rentals']) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                                <span class="text-sm text-gray-500">Total Revenue</span>
                                <span class="text-sm font-bold text-primary-600">Rp {{ number_format($rentalStats['total_revenue']) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('vendor.products.edit', $product) }}" 
                               class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                Edit Product
                            </a>
                            
                            <form action="{{ route('vendor.products.toggle-status', $product) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full {{ $product->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    {{ $product->status === 'active' ? 'Deactivate' : 'Activate' }} Product
                                </button>
                            </form>

                            @if($product->listing_status === 'draft' && auth()->user()->isVerified())
                                <form action="{{ route('vendor.products.submit-approval', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Submit for Approval
                                    </button>
                                </form>
                            @endif

                            <!-- Public Product Link -->
                            @if($product->listing_status === 'approved')
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   target="_blank"
                                   class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                    View Public Page
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Product URL -->
                    @if($product->listing_status === 'approved')
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product URL</h3>
                            
                            <div class="bg-gray-50 rounded p-3">
                                <code class="text-xs text-gray-600 break-all">{{ route('products.show', $product->slug) }}</code>
                            </div>
                            
                            <button onclick="copyToClipboard('{{ route('products.show', $product->slug) }}')" 
                                    class="mt-2 text-sm text-primary-600 hover:text-primary-500 font-medium">
                                Copy Link
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link copied to clipboard!');
            });
        }
    </script>
</x-app-layout>