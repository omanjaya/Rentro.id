<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="text-gray-600 mt-1">{{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Users
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Edit User
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                            @if($user->email_verified_at)
                                <span class="text-xs text-green-600">✓ Verified</span>
                            @else
                                <span class="text-xs text-red-600">✗ Not verified</span>
                            @endif
                        </div>
                        
                        @if($user->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-gray-900">{{ $user->phone }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($user->role === 'admin') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                            <p class="text-gray-900">{{ $user->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $user->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($user->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $user->address }}</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Rentals -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Rentals</h3>
                    
                    @if($recentRentals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rental Code</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentRentals as $rental)
                                        <tr>
                                            <td class="px-3 py-2 text-sm">
                                                <a href="{{ route('admin.rentals.show', $rental) }}" class="text-primary-600 hover:text-primary-800">
                                                    {{ $rental->rental_code }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2 text-sm">
                                                <div class="font-medium">{{ $rental->product->name }}</div>
                                                <div class="text-gray-500 text-xs">{{ $rental->product->category->name }}</div>
                                            </td>
                                            <td class="px-3 py-2 text-sm">{{ $rental->total_days }} days</td>
                                            <td class="px-3 py-2 text-sm">Rp {{ number_format($rental->total_price) }}</td>
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
                                            <td class="px-3 py-2 text-sm text-gray-500">
                                                {{ $rental->created_at->format('M j, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($user->rentals()->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.rentals.index', ['search' => $user->email]) }}" 
                                   class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    View all {{ $user->rentals()->count() }} rentals →
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">No rentals yet for this user.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Avatar -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h3>
                    
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 class="mx-auto h-24 w-24 rounded-full">
                        @else
                            <div class="mx-auto h-24 w-24 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-800 font-medium text-2xl">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        <p class="mt-2 text-sm text-gray-600">{{ $user->name }}</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Rentals</span>
                            <span class="font-medium text-gray-900">{{ $user->rentals_count }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Rentals</span>
                            <span class="font-medium text-gray-900">{{ $user->active_rentals_count }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Completed Rentals</span>
                            <span class="font-medium text-gray-900">{{ $user->completed_rentals_count }}</span>
                        </div>
                        
                        <div class="flex justify-between border-t pt-4">
                            <span class="text-gray-600">Total Spent</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($totalSpent) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md font-medium text-center block transition-colors">
                            Edit User
                        </a>
                        
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user) }}" 
                                  method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full @if($user->status === 'active') bg-orange-600 hover:bg-orange-700 @else bg-green-600 hover:bg-green-700 @endif text-white px-4 py-2 rounded-md font-medium transition-colors">
                                    @if($user->status === 'active')
                                        Deactivate User
                                    @else
                                        Activate User
                                    @endif
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition-colors"
                                        @if($user->rentals()->whereIn('status', ['active', 'pending', 'approved'])->exists())
                                            disabled 
                                            title="Cannot delete user with active rentals"
                                        @endif>
                                    Delete User
                                </button>
                            </form>
                        @else
                            <div class="text-center text-gray-500 text-sm">
                                You cannot manage your own account
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>