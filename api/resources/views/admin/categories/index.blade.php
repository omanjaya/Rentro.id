<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-600 mt-1">Manage product categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Add New Category
            </a>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search categories..." 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                    Search
                </button>
            </form>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="text-3xl">{{ $category->icon ?? '📦' }}</div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="text-primary-600 hover:text-primary-900 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if($category->description)
                        <p class="mt-3 text-gray-600 text-sm">{{ Str::limit($category->description, 100) }}</p>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.categories.show', $category) }}" 
                           class="text-primary-600 hover:text-primary-900 text-sm font-medium">
                            View Details →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.categories.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                Add New Category
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>