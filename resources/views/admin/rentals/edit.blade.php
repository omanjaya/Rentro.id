<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Rental</h1>
                <p class="text-gray-600 mt-1">Rental Code: {{ $rental->rental_code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.rentals.show', $rental) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Details
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('admin.rentals.update', $rental) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Dates</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="{{ old('start_date', $rental->start_date->format('Y-m-d')) }}" 
                                       required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="{{ old('end_date', $rental->end_date->format('Y-m-d')) }}" 
                                       required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('notes') border-red-500 @enderror"
                                      placeholder="Add any notes about this rental...">{{ old('notes', $rental->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.rentals.show', $rental) }}" 
                               class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                                Update Rental
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Current Details -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Details</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Customer:</span>
                            <p class="text-gray-900">{{ $rental->user->name }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700">Product:</span>
                            <p class="text-gray-900">{{ $rental->product->name }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700">Current Duration:</span>
                            <p class="text-gray-900">{{ $rental->total_days }} days</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700">Daily Rate:</span>
                            <p class="text-gray-900">Rp {{ number_format($rental->price_per_day) }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700">Current Total:</span>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($rental->total_price) }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-gray-700">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm">
                            <h4 class="font-medium text-yellow-800 mb-1">Important</h4>
                            <ul class="text-yellow-700 space-y-1">
                                <li>• Date changes will recalculate the total price</li>
                                <li>• Only pending and approved rentals can be edited</li>
                                <li>• Changes will be logged in the rental history</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>