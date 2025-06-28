<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
                <p class="text-gray-600 mt-1">Update product information</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Products
            </a>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Product Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                <select name="category_id" id="category_id" required
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('category_id') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" required
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Price per Day (Rp) *</label>
                                <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $product->price_per_day) }}" 
                                       min="0" step="1000" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('price_per_day') border-red-500 @enderror">
                                @error('price_per_day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" 
                                       min="0" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="4"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Specifications</h3>
                        
                        <div id="specifications-container" class="space-y-3">
                            @php
                                $specifications = old('specifications') ?: $product->specifications;
                                $specIndex = 0;
                            @endphp
                            @if($specifications && is_array($specifications) && count($specifications) > 0)
                                @foreach($specifications as $key => $value)
                                    <div class="flex gap-3 specification-row">
                                        <input type="text" name="specifications[{{ $specIndex }}][key]" placeholder="Specification name" 
                                               value="{{ is_array($value) ? ($value['key'] ?? $key) : $key }}"
                                               class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <input type="text" name="specifications[{{ $specIndex }}][value]" placeholder="Specification value" 
                                               value="{{ is_array($value) ? ($value['value'] ?? $value) : $value }}"
                                               class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @php $specIndex++; @endphp
                                @endforeach
                            @else
                                <div class="flex gap-3 specification-row">
                                    <input type="text" name="specifications[0][key]" placeholder="Specification name" 
                                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <input type="text" name="specifications[0][value]" placeholder="Specification value" 
                                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" onclick="addSpecification()" class="mt-3 text-primary-600 hover:text-primary-800 text-sm font-medium">
                            + Add Specification
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Current Image -->
                    @if($product->image)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Image</h3>
                            <div class="text-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="mx-auto h-32 w-32 object-cover rounded-lg">
                            </div>
                        </div>
                    @endif

                    <!-- Main Image -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $product->image ? 'Update Main Image' : 'Main Image' }}</h3>
                        
                        <div class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewMainImage(this)">
                                <div id="main-image-preview" class="hidden">
                                    <img id="main-image-preview-img" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeMainImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div id="main-image-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Click to upload {{ $product->image ? 'new' : '' }} main image</p>
                                </div>
                            </div>
                            <label for="image" class="cursor-pointer block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Choose File
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gallery Images -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Gallery Images</h3>
                        
                        <div class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple class="hidden" onchange="previewGalleryImages(this)">
                                <div id="gallery-preview" class="hidden grid grid-cols-2 gap-2">
                                </div>
                                <div id="gallery-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Click to upload gallery images</p>
                                </div>
                            </div>
                            <label for="gallery" class="cursor-pointer block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Choose Files
                            </label>
                        </div>
                        @error('gallery.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let specificationIndex = @json($product->specifications && is_array($product->specifications) ? count($product->specifications) : 1);

        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-3 specification-row';
            newRow.innerHTML = `
                <input type="text" name="specifications[${specificationIndex}][key]" placeholder="Specification name" 
                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <input type="text" name="specifications[${specificationIndex}][value]" placeholder="Specification value" 
                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            `;
            container.appendChild(newRow);
            specificationIndex++;
        }

        function removeSpecification(button) {
            button.closest('.specification-row').remove();
        }

        function previewMainImage(input) {
            const preview = document.getElementById('main-image-preview');
            const placeholder = document.getElementById('main-image-placeholder');
            const img = document.getElementById('main-image-preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeMainImage() {
            document.getElementById('image').value = '';
            document.getElementById('main-image-preview').classList.add('hidden');
            document.getElementById('main-image-placeholder').classList.remove('hidden');
        }

        function previewGalleryImages(input) {
            const preview = document.getElementById('gallery-preview');
            const placeholder = document.getElementById('gallery-placeholder');

            if (input.files && input.files.length > 0) {
                preview.innerHTML = '';
                
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover rounded">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-admin-layout>