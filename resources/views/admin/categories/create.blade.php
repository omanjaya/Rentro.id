<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create New Category
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Add a new product category to organize your marketplace
                </p>
            </div>
            
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Category Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                <p class="text-sm text-gray-500 mt-1">Choose a clear, descriptive name for this category</p>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="icon" :value="__('Icon (Optional)')" />
                                <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" :value="old('icon')" placeholder="e.g., fas fa-laptop, heroicon-laptop" />
                                <p class="text-sm text-gray-500 mt-1">CSS class for icon (FontAwesome, Heroicons, etc.)</p>
                                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" 
                                      class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                      placeholder="Describe what types of products belong in this category...">{{ old('description') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">This will help users understand what products fit in this category</p>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
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
                                            <i id="previewIcon" class="text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <p id="previewName" class="font-medium text-gray-900">Category Name</p>
                                            <p id="previewDescription" class="text-sm text-gray-500">Category description...</p>
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

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.categories.index') }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        
                        <x-primary-button>
                            {{ __('Create Category') }}
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