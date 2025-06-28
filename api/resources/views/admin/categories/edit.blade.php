<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Category: {{ $category->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Update category information and settings
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.show', $category) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    View Category
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Categories
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Category Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                                <p class="text-sm text-gray-500 mt-1">Choose a clear, descriptive name for this category</p>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="icon" :value="__('Icon (Optional)')" />
                                <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" :value="old('icon', $category->icon)" placeholder="e.g., fas fa-laptop, heroicon-laptop" />
                                <p class="text-sm text-gray-500 mt-1">CSS class for icon (FontAwesome, Heroicons, etc.)</p>
                                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" 
                                      class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                      placeholder="Describe what types of products belong in this category...">{{ old('description', $category->description) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">This will help users understand what products fit in this category</p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <!-- Current Category Stats -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category Statistics</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-blue-800">Total Products</p>
                                        <p class="text-2xl font-bold text-blue-900">{{ $category->products()->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">Active Products</p>
                                        <p class="text-2xl font-bold text-green-900">{{ $category->products()->where('status', 'active')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-purple-800">Total Rentals</p>
                                        <p class="text-2xl font-bold text-purple-900">
                                            {{ $category->products()->withCount('rentals')->get()->sum('rentals_count') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Icon Preview -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Icon Preview</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Live Preview</label>
                                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                    <div id="iconPreview" class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-primary-500 rounded flex items-center justify-center">
                                            <i id="previewIcon" class="{{ $category->icon ?: '' }} text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <p id="previewName" class="font-medium text-gray-900">{{ $category->name }}</p>
                                            <p id="previewDescription" class="text-sm text-gray-500">{{ $category->description ?: 'Category description...' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Common Icons</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @php
                                        $commonIcons = [
                                            'fas fa-laptop' => 'Laptop',
                                            'fas fa-camera' => 'Camera',
                                            'fas fa-headphones' => 'Audio',
                                            'fas fa-gamepad' => 'Gaming',
                                            'fas fa-mobile-alt' => 'Mobile',
                                            'fas fa-tv' => 'Electronics'
                                        ];
                                    @endphp
                                    
                                    @foreach($commonIcons as $iconClass => $label)
                                        <button type="button" onclick="setIcon('{{ $iconClass }}')" 
                                                class="p-2 border border-gray-300 rounded text-center hover:bg-gray-50 transition-colors">
                                            <i class="{{ $iconClass }} text-primary-500 mb-1"></i>
                                            <div class="text-xs text-gray-600">{{ $label }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Warning -->
                    @if($category->products()->count() > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Impact Notice</h3>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        This category contains {{ $category->products()->count() }} products. Changes to the category name will be reflected across all associated products and may affect search and filtering.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.categories.show', $category) }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        
                        <x-primary-button>
                            {{ __('Update Category') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Real-time preview updates
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('previewName').textContent = this.value || 'Category Name';
        });

        document.getElementById('description').addEventListener('input', function() {
            document.getElementById('previewDescription').textContent = this.value || 'Category description...';
        });

        document.getElementById('icon').addEventListener('input', function() {
            const iconElement = document.getElementById('previewIcon');
            iconElement.className = this.value ? this.value + ' text-white text-lg' : 'text-white text-lg';
        });

        function setIcon(iconClass) {
            document.getElementById('icon').value = iconClass;
            document.getElementById('previewIcon').className = iconClass + ' text-white text-lg';
        }
    </script>
</x-admin-layout>