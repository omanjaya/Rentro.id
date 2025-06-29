<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Penyewaan: {{ $rental->rental_code }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Lihat informasi dan status penyewaan Anda
                </p>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('my-rentals') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Kembali ke Penyewaan Saya
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Status Banner -->
            @if($rental->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Menunggu Persetujuan</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Permintaan penyewaan Anda sedang ditinjau oleh vendor. Anda akan diberitahu setelah disetujui.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($rental->status === 'active')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Penyewaan Aktif</h3>
                            <p class="mt-1 text-sm text-blue-700">
                                Penyewaan Anda aktif! Harap rawat peralatan dengan baik dan kembalikan tepat waktu.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($rental->status === 'completed')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Penyewaan Selesai</h3>
                            <p class="mt-1 text-sm text-green-700">
                                Terima kasih telah menggunakan layanan kami! Kami harap Anda mendapat pengalaman yang luar biasa.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($rental->status === 'cancelled')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Penyewaan Dibatalkan</h3>
                            <p class="mt-1 text-sm text-red-700">
                                Penyewaan ini telah dibatalkan. Jika Anda memiliki pertanyaan, silakan hubungi dukungan.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Rental Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Information -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Produk</h3>
                        
                        <div class="flex items-start space-x-4">
                            @if($rental->product->image)
                                <img src="{{ asset('storage/' . $rental->product->image) }}" alt="{{ $rental->product->name }}" 
                                     class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            @else
                                <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="text-xl font-semibold text-gray-900">{{ $rental->product->name }}</h4>
                                <p class="text-gray-600 mt-2">{{ $rental->product->description }}</p>
                                
                                <!-- Category and Vendor Info -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $rental->product->category->name }}
                                    </span>
                                    
                                    @if($rental->product->vendor)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $rental->product->vendor->business_name ?: $rental->product->vendor->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Produk Platform
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Product Specifications -->
                                @if($rental->product->parsed_specifications)
                                    <div class="mt-4">
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Spesifikasi</h5>
                                        <dl class="grid grid-cols-2 gap-2">
                                            @foreach($rental->product->parsed_specifications as $spec)
                                                @if(!empty($spec['key']) && !empty($spec['value']))
                                                    <div>
                                                        <dt class="text-xs text-gray-500">{{ $spec['key'] }}</dt>
                                                        <dd class="text-xs text-gray-900">{{ $spec['value'] }}</dd>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </dl>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Rental Details -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penyewaan</h3>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kode Penyewaan</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-900 bg-gray-50 px-2 py-1 rounded">{{ $rental->rental_code }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($rental->status === 'active') bg-blue-100 text-blue-800
                                        @elseif($rental->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($rental->status === 'pending') Menunggu
                                        @elseif($rental->status === 'active') Aktif
                                        @elseif($rental->status === 'completed') Selesai
                                        @else Dibatalkan
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->start_date->format('F d, Y') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->end_date->format('F d, Y') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->total_days }} hari</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Pemesanan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $rental->created_at->format('F d, Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Vendor Contact (if vendor product) -->
                    @if($rental->product->vendor)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak Vendor</h3>
                            
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-primary-500 flex items-center justify-center">
                                        <span class="text-lg font-medium text-white">
                                            {{ substr($rental->product->vendor->business_name ?: $rental->product->vendor->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">
                                        {{ $rental->product->vendor->business_name ?: $rental->product->vendor->name }}
                                    </h4>
                                    
                                    @if($rental->product->vendor->business_description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $rental->product->vendor->business_description }}</p>
                                    @endif
                                    
                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <a href="mailto:{{ $rental->product->vendor->email }}" class="text-primary-600 hover:text-primary-500">
                                                {{ $rental->product->vendor->email }}
                                            </a>
                                        </div>
                                        
                                        @if($rental->product->vendor->phone)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                <a href="tel:{{ $rental->product->vendor->phone }}" class="text-primary-600 hover:text-primary-500">
                                                    {{ $rental->product->vendor->phone }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($rental->notes)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan & Pembaruan</h3>
                            <div class="bg-gray-50 rounded p-4">
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ $rental->notes }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Pricing Summary -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Harga</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Harga per Hari</span>
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($rental->price_per_day) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Durasi</span>
                                <span class="text-sm font-medium text-gray-900">{{ $rental->total_days }} hari</span>
                            </div>
                            
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-base font-medium text-gray-900">Total Biaya</span>
                                <span class="text-base font-bold text-gray-900">Rp {{ number_format($rental->total_price) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rental Timeline -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline Penyewaan</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Pemesanan Dibuat</p>
                                    <p class="text-sm text-gray-500">{{ $rental->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $rental->status !== 'pending' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Mulai Penyewaan</p>
                                    <p class="text-sm text-gray-500">{{ $rental->start_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $rental->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 8.707 7.621a1 1 0 00-1.414 1.414l2.5 2.5a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L10.5 9.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Selesai Penyewaan</p>
                                    <p class="text-sm text-gray-500">{{ $rental->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('products.show', $rental->product->slug) }}" 
                               class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                Lihat Detail Produk
                            </a>
                            
                            @if($rental->product->vendor)
                                <a href="mailto:{{ $rental->product->vendor->email }}?subject=Rental {{ $rental->rental_code }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                    Hubungi Vendor
                                </a>
                                
                                @if($rental->product->vendor->phone)
                                    <a href="tel:{{ $rental->product->vendor->phone }}" 
                                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                        Telepon Vendor
                                    </a>
                                @endif
                            @endif
                            
                            <a href="{{ route('dashboard') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                                Kembali ke Dasbor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>