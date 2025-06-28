<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Product: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Update your product information and settings
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('vendor.products.show', $product) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    View Product
                </a>
                <a href="{{ route('vendor.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Status Alert -->
                    @if($product->listing_status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Product Rejected</h3>
                                    <p class="mt-1 text-sm text-red-700">
                                        This product was rejected by admin. Please review the feedback and make necessary changes before resubmitting.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Basic Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Product Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" 
                                      class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                      placeholder="Describe your product in detail...">{{ old('description', $product->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing & Inventory</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="price_per_day" :value="__('Price per Day (Rp)')" />
                                <x-text-input id="price_per_day" name="price_per_day" type="number" class="mt-1 block w-full" 
                                              :value="old('price_per_day', $product->price_per_day)" min="1000" step="1000" required />
                                <p class="text-sm text-gray-500 mt-1">Minimum: Rp 1,000</p>
                                <x-input-error class="mt-2" :messages="$errors->get('price_per_day')" />
                            </div>

                            <div>
                                <x-input-label for="stock" :value="__('Available Stock')" />
                                <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" 
                                              :value="old('stock', $product->stock)" min="1" required />
                                <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                            </div>
                        </div>
                    </div>

                    <!-- Current Images -->
                    @if($product->image || $product->gallery)
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Images</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($product->image)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                             class="w-full h-48 object-cover rounded-lg border border-gray-300">
                                    </div>
                                @endif

                                @if($product->gallery)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach(json_decode($product->gallery, true) as $image)
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" 
                                                     class="w-full h-24 object-cover rounded border border-gray-300">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Update Images -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Images (Optional)</h3>
                        <p class="text-sm text-gray-600 mb-4">Upload new images to replace existing ones</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="image" :value="__('Main Image')" />
                                <input id="image" name="image" type="file" accept="image/*" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                                <p class="text-sm text-gray-500 mt-1">JPG, PNG, GIF up to 5MB</p>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div>
                                <x-input-label for="gallery" :value="__('Gallery Images')" />
                                <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                                <p class="text-sm text-gray-500 mt-1">Multiple images, JPG, PNG, GIF up to 5MB each</p>
                                <x-input-error class="mt-2" :messages="$errors->get('gallery')" />
                            </div>
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Specifications</h3>
                        
                        <div id="specifications-container">
                            @php
                                $specifications = $product->specifications ? json_decode($product->specifications, true) : [];
                                if (empty($specifications)) {
                                    $specifications = [['key' => '', 'value' => '']];
                                }
                            @endphp
                            
                            @foreach($specifications as $index => $spec)
                                <div class="specification-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-text-input name="specifications[{{ $index }}][key]" type="text" 
                                                      placeholder="Specification name" class="w-full" 
                                                      :value="old('specifications.' . $index . '.key', $spec['key'] ?? '')" />
                                    </div>
                                    <div class="flex">
                                        <x-text-input name="specifications[{{ $index }}][value]" type="text" 
                                                      placeholder="Specification value" class="w-full" 
                                                      :value="old('specifications.' . $index . '.value', $spec['value'] ?? '')" />
                                        <button type="button" onclick="removeSpecification(this)" class="ml-2 px-3 py-2 text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addSpecification()" class="mt-2 text-primary-600 hover:text-primary-500 text-sm font-medium">
                            + Add Specification
                        </button>
                    </div>

                    <!-- Status Settings -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="status" :value="__('Product Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Update Notice</h3>
                                        <p class="mt-1 text-sm text-blue-700">
                                            @if(auth()->user()->isVerified() && $product->listing_status === 'approved')
                                                Significant changes to approved products may require re-approval from admin.
                                            @elseif(!auth()->user()->isVerified())
                                                Your product will remain as draft until your vendor account is verified.
                                            @else
                                                Save your changes and submit for admin approval when ready.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('vendor.products.show', $product) }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        
                        <x-primary-button>
                            {{ __('Update Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let specificationIndex = {{ count($specifications) }};

        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newRow = document.createElement('div');
            newRow.className = 'specification-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4';
            newRow.innerHTML = `
                <div>
                    <input name="specifications[${specificationIndex}][key]" type="text" placeholder="Specification name" 
                           class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm w-full" />
                </div>
                <div class="flex">
                    <input name="specifications[${specificationIndex}][value]" type="text" placeholder="Specification value" 
                           class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm w-full" />
                    <button type="button" onclick="removeSpecification(this)" class="ml-2 px-3 py-2 text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            specificationIndex++;
        }

        function removeSpecification(button) {
            button.closest('.specification-row').remove();
        }
    </script>
</x-app-layout>