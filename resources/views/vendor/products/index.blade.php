<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    My Products
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Manage your product listings and track their performance
                </p>
            </div>
            
            <a href="{{ route('vendor.products.create') }}" 
               class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Add New Product
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Active</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_products']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_approval']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Approved</p>
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['approved_products']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Draft</p>
                            <p class="text-2xl font-bold text-gray-600">{{ number_format($stats['draft_products']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Product name or description..."
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="listing_status" class="block text-sm font-medium text-gray-700">Listing Status</label>
                        <select name="listing_status" id="listing_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">All Listing Statuses</option>
                            <option value="draft" {{ request('listing_status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('listing_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('listing_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('listing_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <!-- Product Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="flex items-center justify-center h-48 bg-gray-300">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                                    <p class="text-lg font-bold text-primary-600">Rp {{ number_format($product->price_per_day) }}/day</p>
                                </div>
                            </div>

                            <!-- Status Badges -->
                            <div class="flex items-center space-x-2 mt-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($product->status === 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($product->status) }}
                                </span>
                                
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($product->listing_status === 'approved') bg-green-100 text-green-800
                                    @elseif($product->listing_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($product->listing_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($product->listing_status) }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>

                            <!-- Actions -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendor.products.show', $product) }}" 
                                       class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('vendor.products.edit', $product) }}" 
                                       class="text-gray-600 hover:text-gray-500 text-sm font-medium">
                                        Edit
                                    </a>
                                </div>
                                
                                <div class="flex space-x-1">
                                    <!-- Status Toggle -->
                                    <form action="{{ route('vendor.products.toggle-status', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-xs px-2 py-1 rounded {{ $product->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                            {{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <!-- Submit for Approval -->
                                    @if($product->listing_status === 'draft' && auth()->user()->isVerified())
                                        <form action="{{ route('vendor.products.submit-approval', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-600 hover:bg-blue-200">
                                                Submit
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first product.</p>
                        <div class="mt-6">
                            <a href="{{ route('vendor.products.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                Add Product
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>