<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Rental Details</h1>
                <p class="text-gray-600 mt-1">Rental Code: {{ $rental->rental_code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.rentals.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Rentals
                </a>
                @if(in_array($rental->status, ['pending', 'approved']))
                    <a href="{{ route('admin.rentals.edit', $rental) }}" 
                       class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Edit Rental
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Rental Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rental Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <p class="text-gray-900">{{ $rental->start_date->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <p class="text-gray-900">{{ $rental->end_date->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <p class="text-gray-900">{{ $rental->total_days }} days</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Daily Rate</label>
                            <p class="text-gray-900">Rp {{ number_format($rental->price_per_day) }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($rental->total_price) }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($rental->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($rental->status === 'active') bg-green-100 text-green-800
                                @elseif($rental->status === 'completed') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($rental->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $rental->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Details</h3>
                    
                    <div class="flex items-start space-x-4">
                        @if($rental->product->image)
                            <img src="{{ $rental->product->image_url }}" 
                                 alt="{{ $rental->product->name }}" 
                                 class="w-24 h-24 object-cover rounded-lg">
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">{{ $rental->product->name }}</h4>
                            <p class="text-gray-600 mb-2">{{ $rental->product->category->name }}</p>
                            
                            @if($rental->product->description)
                                <p class="text-gray-700 mb-3">{{ $rental->product->description }}</p>
                            @endif
                            
                            <a href="{{ route('products.show', $rental->product) }}" 
                               target="_blank"
                               class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View Product Details →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-gray-900">{{ $rental->user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $rental->user->email }}</p>
                        </div>
                        
                        @if($rental->user->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <p class="text-gray-900">{{ $rental->user->phone }}</p>
                            </div>
                        @endif
                        
                        @if($rental->user->address)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <p class="text-gray-900">{{ $rental->user->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Management -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Management</h3>
                    
                    <form action="{{ route('admin.rentals.update-status', $rental) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="pending" {{ $rental->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $rental->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="active" {{ $rental->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $rental->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $rental->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                      placeholder="Add any notes about this status change...">{{ old('notes', $rental->notes) }}</textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-gray-400 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900">Rental Created</p>
                                <p class="text-gray-500">{{ $rental->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($rental->updated_at != $rental->created_at)
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Last Updated</p>
                                    <p class="text-gray-500">{{ $rental->updated_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>