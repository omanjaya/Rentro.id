<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Product</h1>
                <p class="text-gray-600 mt-1">Add a new product to your rental inventory</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Products
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                
                <!-- Debug Information -->
                @if(app()->environment('local'))
                    <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 text-yellow-800 text-xs">
                        <strong>Debug Info:</strong><br>
                        Request Data: {{ json_encode(request()->all()) }}<br>
                        Old Input: {{ json_encode(old()) }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
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
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                <select name="category_id" id="category_id" required
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Price per Day (Rp) *</label>
                                <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" 
                                       min="0" step="1000" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('price_per_day') border-red-500 @enderror">
                                @error('price_per_day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" 
                                       min="0" required
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="4"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                            <div class="flex gap-3 specification-row">
                                <input type="text" name="specifications[0][key]" placeholder="Specification name (e.g., Processor)" 
                                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="text" name="specifications[0][value]" placeholder="Specification value (e.g., Intel i7)" 
                                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-600 hover:text-red-800 bg-red-50 rounded-md">
                                    Remove
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" onclick="addSpecification()" class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium bg-blue-50 px-3 py-1 rounded">
                            + Add Specification
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Main Image -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Main Image</h3>
                        
                        <div class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewMainImage(this)">
                                <div id="main-image-preview" class="hidden">
                                    <img id="main-image-preview-img" class="mx-auto h-32 w-32 object-cover rounded-lg shadow">
                                    <button type="button" onclick="removeMainImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm bg-red-50 px-2 py-1 rounded">Remove</button>
                                </div>
                                <div id="main-image-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Click to upload main image</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            <label for="image" class="cursor-pointer block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Choose Main Image
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
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple class="hidden" onchange="previewGalleryImages(this)">
                                <div id="gallery-preview" class="hidden grid grid-cols-2 gap-2">
                                </div>
                                <div id="gallery-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Click to upload gallery images</p>
                                    <p class="text-xs text-gray-500">Multiple images, PNG, JPG, GIF up to 2MB each</p>
                                </div>
                            </div>
                            <label for="gallery" class="cursor-pointer block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Choose Gallery Images
                            </label>
                        </div>
                        @error('gallery.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            🚀 Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="mt-3 block w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let specificationIndex = 1;

        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-3 specification-row';
            newRow.innerHTML = `
                <input type="text" name="specifications[${specificationIndex}][key]" placeholder="Specification name" 
                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="text" name="specifications[${specificationIndex}][value]" placeholder="Specification value" 
                       class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 text-red-600 hover:text-red-800 bg-red-50 rounded-md">
                    Remove
                </button>
            `;
            container.appendChild(newRow);
            specificationIndex++;
        }

        function removeSpecification(button) {
            const container = document.getElementById('specifications-container');
            button.closest('.specification-row').remove();
            
            // Ensure at least one specification row exists
            if (container.children.length === 0) {
                addSpecification();
            }
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
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover rounded shadow">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>