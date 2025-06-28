<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Rentals Management</h1>
                <p class="text-gray-600 mt-1">Manage and track all rental bookings</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.rentals.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}" 
                           placeholder="Search by rental code, customer name, or product..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div class="md:w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" 
                            id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.rentals.index') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Rentals Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rental Code
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rentals as $rental)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $rental->rental_code }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $rental->created_at->format('M j, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $rental->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $rental->user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($rental->product->image)
                                            <img class="h-8 w-8 rounded object-cover mr-3" 
                                                 src="{{ $rental->product->image_url }}" 
                                                 alt="{{ $rental->product->name }}">
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $rental->product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $rental->product->category->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $rental->start_date->format('M j') }} - {{ $rental->end_date->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $rental->total_days }} days
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($rental->total_price) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Rp {{ number_format($rental->price_per_day) }}/day
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($rental->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($rental->status === 'active') bg-green-100 text-green-800
                                        @elseif($rental->status === 'completed') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.rentals.show', $rental) }}" 
                                           class="text-primary-600 hover:text-primary-900">
                                            View
                                        </a>
                                        @if(in_array($rental->status, ['pending', 'approved']))
                                            <a href="{{ route('admin.rentals.edit', $rental) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-1">No rentals found</p>
                                        <p class="text-gray-500">
                                            @if(request()->hasAny(['search', 'status']))
                                                Try adjusting your search criteria.
                                            @else
                                                Rentals will appear here when customers make bookings.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($rentals->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $rentals->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>