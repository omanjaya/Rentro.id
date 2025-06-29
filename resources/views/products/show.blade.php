<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div class="space-y-4">
                    <!-- Main Image -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-96 object-cover"
                                 id="main-image">
                        @else
                            <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                                <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Gallery Images -->
                    @if($product->gallery_urls && count($product->gallery_urls) > 0)
                        <div class="grid grid-cols-4 gap-2">
                            @if($product->image)
                                <button onclick="changeMainImage('{{ $product->image_url }}')" 
                                        class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-md overflow-hidden border-2 border-primary-600">
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-20 object-cover">
                                </button>
                            @endif
                            
                            @foreach($product->gallery_urls as $imageUrl)
                                <button onclick="changeMainImage('{{ $imageUrl }}')" 
                                        class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-md overflow-hidden border-2 border-transparent hover:border-primary-400">
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-20 object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div>
                        <div class="mb-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <div>
                                <p class="text-3xl font-bold text-primary-600">
                                    Rp {{ number_format($product->price_per_day) }}
                                </p>
                                <p class="text-sm text-gray-500">per hari</p>
                            </div>
                            
                            <div>
                                @if($product->stock > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $product->stock }} tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Stok habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    <!-- Specifications -->
                    @if($product->specifications)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Spesifikasi</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @php
                                    $specs = is_string($product->specifications) ? json_decode($product->specifications, true) : $product->specifications;
                                @endphp
                                @if($specs && is_array($specs))
                                    <dl class="grid grid-cols-1 gap-3">
                                        @foreach($specs as $key => $value)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $value }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @else
                                    <p class="text-sm text-gray-600">{{ $product->specifications }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Rental Actions -->
                    <div class="border-t pt-6">
                        @auth
                            @if($product->stock > 0)
                                <div class="space-y-4">
                                    <a href="{{ route('booking.show', $product->slug) }}" 
                                       class="w-full bg-primary-600 text-white text-center py-3 px-6 rounded-lg hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-medium inline-block">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Pesan Produk Ini
                                    </a>
                                    
                                    <p class="text-xs text-gray-500 text-center">
                                        Pemesanan aman • Periode sewa fleksibel • Dukungan profesional
                                    </p>
                                </div>
                            @else
                                <div class="text-center">
                                    <button disabled 
                                            class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg cursor-not-allowed font-medium">
                                        Saat Ini Tidak Tersedia
                                    </button>
                                    <p class="text-sm text-gray-500 mt-2">Produk ini saat ini stok habis. Periksa kembali nanti atau hubungi kami untuk update ketersediaan.</p>
                                </div>
                            @endif
                        @else
                            <div class="space-y-4">
                                <a href="{{ route('login') }}" 
                                   class="w-full bg-primary-600 text-white text-center py-3 px-6 rounded-lg hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-medium inline-block">
                                    Masuk untuk Pesan Produk Ini
                                </a>
                                
                                <p class="text-center text-sm text-gray-600">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-500 font-medium">Daftar di sini</a>
                                </p>
                            </div>
                        @endauth
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Informasi Penyewaan</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Periode sewa minimum: 1 hari</li>
                            <li>• Pengiriman gratis dalam kota</li>
                            <li>• Dukungan teknis 24/7</li>
                            <li>• Perlindungan kerusakan termasuk</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                    @if($relatedProduct->image)
                                        <img src="{{ $relatedProduct->image_url }}" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="hover:text-primary-600">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    <p class="text-lg font-bold text-primary-600 mb-2">
                                        Rp {{ number_format($relatedProduct->price_per_day) }}/day
                                    </p>
                                    @if($relatedProduct->stock > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
            
            // Update border styles for gallery thumbnails
            document.querySelectorAll('[onclick^="changeMainImage"]').forEach(btn => {
                btn.classList.remove('border-primary-600');
                btn.classList.add('border-transparent');
            });
            
            // Add border to clicked thumbnail
            event.target.closest('button').classList.remove('border-transparent');
            event.target.closest('button').classList.add('border-primary-600');
        }
    </script>
    @endpush
</x-app-layout>