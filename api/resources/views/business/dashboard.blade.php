<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Business Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Welcome back, {{ $business->business_name ?? $business->name }}!
                </p>
            </div>
            
            <!-- Verification Status -->
            <div class="flex items-center space-x-3">
                @if($business->verification_status === 'verified')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Verified Business
                    </span>
                @elseif($business->verification_status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pending Verification
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Verification Required
                    </span>
                @endif
                
                <a href="{{ route('products.index') }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Browse Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if($business->verification_status === 'pending')
                <!-- Verification Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Business Account Verification Pending</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                Your business account is currently under review. You can still rent equipment, but some premium features may be limited until verification is complete.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Rentals</dt>
                                    <dd class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_rentals']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Rentals</dt>
                                    <dd class="text-3xl font-bold text-green-600">{{ number_format($stats['active_rentals']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Month Spent -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                                    <dd class="text-3xl font-bold text-yellow-600">Rp {{ number_format($stats['this_month_spent']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Rental Value -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg Rental Value</dt>
                                    <dd class="text-3xl font-bold text-purple-600">Rp {{ number_format($stats['average_rental_value']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('products.index') }}" 
                           class="bg-primary-600 hover:bg-primary-700 text-white p-4 rounded-lg text-center transition-colors">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div class="font-medium">Browse Products</div>
                        </a>
                        
                        <a href="{{ route('user.rentals') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded-lg text-center transition-colors">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div class="font-medium">My Rentals</div>
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition-colors">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div class="font-medium">Business Profile</div>
                        </a>
                        
                        <div class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-lg text-center transition-colors cursor-pointer"
                             onclick="alert('Coming soon: Bulk rental management for business customers!')">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <div class="font-medium">Bulk Orders</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Rentals</h3>
                        <div class="space-y-4">
                            @forelse($recentRentals as $rental)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $rental->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $rental->product->category->name ?? 'General' }}</p>
                                        <p class="text-xs text-gray-400">{{ $rental->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($rental->status === 'active') bg-blue-100 text-blue-800
                                            @elseif($rental->status === 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            Rp {{ number_format($rental->total_price) }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $rental->rental_code }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No rentals yet</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.rentals') }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                                View all rentals →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Rentals</h3>
                        <div class="space-y-4">
                            @forelse($upcomingRentals as $rental)
                                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $rental->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $rental->product->category->name ?? 'General' }}</p>
                                        <p class="text-xs text-blue-600 font-medium">Starts: {{ $rental->start_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($rental->total_price) }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $rental->rental_code }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No upcoming rentals</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spending Trend Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Spending Trend</h3>
                    <div class="text-sm text-gray-600 mb-4">Track your business equipment rental spending over the last 6 months</div>
                    
                    <!-- Simple bar chart representation -->
                    <div class="grid grid-cols-6 gap-2 h-40">
                        @foreach($monthlyTrend['spending'] as $index => $amount)
                            @php
                                $maxAmount = max($monthlyTrend['spending']) ?: 1;
                                $height = $maxAmount > 0 ? ($amount / $maxAmount) * 100 : 0;
                            @endphp
                            <div class="flex flex-col justify-end">
                                <div class="bg-primary-500 rounded-t" style="height: {{ $height }}%"></div>
                                <div class="text-xs text-center mt-2 text-gray-600">{{ $monthlyTrend['months'][$index] }}</div>
                                <div class="text-xs text-center text-gray-500">Rp {{ number_format($amount / 1000) }}k</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>