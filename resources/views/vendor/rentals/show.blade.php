<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Rental Details: {{ $rental->rental_code }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Comprehensive rental information and management
                </p>
            </div>
            
            <a href="{{ route('vendor.rentals.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Rentals
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Status Alert -->
            @if($rental->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Pending Approval</h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    This rental is waiting for your approval. Review the details and approve or cancel the rental.
                                </p>
                            </div>
                            <div class="ml-4 flex space-x-2">
                                <form action="{{ route('vendor.rentals.update-status', $rental) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Approve Rental
                                    </button>
                                </form>
                                <form action="{{ route('vendor.rentals.update-status', $rental) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                            onclick="return confirm('Are you sure you want to cancel this rental?')">
                                        Cancel Rental
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($rental->status === 'active')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Active Rental</h3>
                                <p class="mt-1 text-sm text-blue-700">
                                    This rental is currently active. Mark as completed when the customer returns the item.
                                </p>
                            </div>
                            <div class="ml-4">
                                <form action="{{ route('vendor.rentals.update-status', $rental) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                            onclick="return confirm('Mark this rental as completed?')">
                                        Mark Completed
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Rental Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Information</h3>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rental Code</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-900">{{ $rental->rental_code }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($rental->status === 'active') bg-blue-100 text-blue-800
                                        @elseif($rental->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->start_date->format('F d, Y') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->end_date->format('F d, Y') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->total_days }} {{ Str::plural('day', $rental->total_days) }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->created_at->format('F d, Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->user->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $rental->user->email }}" class="text-primary-600 hover:text-primary-500">
                                        {{ $rental->user->email }}
                                    </a>
                                </dd>
                            </div>
                            
                            @if($rental->user->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="tel:{{ $rental->user->phone }}" class="text-primary-600 hover:text-primary-500">
                                            {{ $rental->user->phone }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Type</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($rental->user->user_type === 'business') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($rental->user->user_type) }}
                                        @if($rental->user->user_type === 'business') Customer @endif
                                    </span>
                                </dd>
                            </div>
                            
                            @if($rental->user->business_name)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Business Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->user->business_name }}</dd>
                                </div>
                            @endif
                            
                            @if($rental->user->address)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->user->address }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Product Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                        
                        <div class="flex items-start space-x-4">
                            @if($rental->product->image)
                                <img src="{{ asset('storage/' . $rental->product->image) }}" alt="{{ $rental->product->name }}" 
                                     class="w-24 h-24 object-cover rounded-lg border border-gray-300">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ $rental->product->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $rental->product->description }}</p>
                                
                                <!-- Product Specifications -->
                                @if($rental->product->parsed_specifications)
                                    <div class="mt-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Specifications</h5>
                                        <dl class="grid grid-cols-2 gap-2">
                                            @foreach($rental->product->parsed_specifications as $spec)
                                                @if(!empty($spec['key']) && !empty($spec['value']))
                                                    <div>
                                                        <dt class="text-xs text-gray-500">{{ $spec['key'] }}</dt>
                                                        <dd class="text-xs text-gray-900">{{ $spec['value'] }}</dd>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </dl>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($rental->notes)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes & Updates</h3>
                            <div class="bg-gray-50 rounded p-4">
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ $rental->notes }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Financial Summary -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Price per Day</span>
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($rental->price_per_day) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Duration</span>
                                <span class="text-sm font-medium text-gray-900">{{ $rental->total_days }} {{ Str::plural('day', $rental->total_days) }}</span>
                            </div>
                            
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm text-gray-500">Total Price</span>
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($rental->total_price) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Platform Fee ({{ $rental->commission_rate }}%)</span>
                                <span class="text-sm text-red-600">- Rp {{ number_format($rental->commission_amount) }}</span>
                            </div>
                            
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm font-medium text-gray-900">Your Revenue</span>
                                <span class="text-sm font-bold text-green-600">Rp {{ number_format($rental->vendor_revenue) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    @if(in_array($rental->status, ['pending', 'active']))
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Management</h3>
                            
                            <form action="{{ route('vendor.rentals.update-status', $rental) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Update Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                        @if($rental->status === 'pending')
                                            <option value="active">Approve & Activate</option>
                                            <option value="cancelled">Cancel Rental</option>
                                        @elseif($rental->status === 'active')
                                            <option value="completed">Mark Completed</option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Add Note (Optional)</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              placeholder="Add any notes about this status update..."
                                              class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            <a href="mailto:{{ $rental->user->email }}?subject=Rental {{ $rental->rental_code }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                Email Customer
                            </a>
                            
                            @if($rental->user->phone)
                                <a href="tel:{{ $rental->user->phone }}" 
                                   class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                    Call Customer
                                </a>
                            @endif
                            
                            <a href="{{ route('vendor.products.show', $rental->product) }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                View Product
                            </a>
                        </div>
                    </div>

                    <!-- Rental Timeline -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Timeline</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Rental Created</p>
                                    <p class="text-sm text-gray-500">{{ $rental->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $rental->status !== 'pending' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Rental Start</p>
                                    <p class="text-sm text-gray-500">{{ $rental->start_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $rental->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 8.707 7.621a1 1 0 00-1.414 1.414l2.5 2.5a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L10.5 9.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Rental End</p>
                                    <p class="text-sm text-gray-500">{{ $rental->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>