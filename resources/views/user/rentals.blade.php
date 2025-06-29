<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penyewaan Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('user.rentals') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Penyewaan</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}" 
                                   placeholder="Cari berdasarkan nama produk atau kode sewa..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        
                        <div class="md:w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" 
                                    id="status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('user.rentals') }}" 
                                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    Bersihkan
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rentals List -->
            @if($rentals->count() > 0)
                <div class="space-y-6">
                    @foreach($rentals as $rental)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <!-- Product Image -->
                                    <div class="lg:col-span-1">
                                        @if($rental->product->image)
                                            <img src="{{ $rental->product->image_url }}" 
                                                 alt="{{ $rental->product->name }}" 
                                                 class="w-full h-32 lg:h-24 object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-32 lg:h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Rental Details -->
                                    <div class="lg:col-span-2 space-y-2">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <a href="{{ route('products.show', $rental->product->slug) }}" class="hover:text-primary-600">
                                                        {{ $rental->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $rental->product->category->name }}</p>
                                            </div>
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                                @if($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($rental->status === 'approved') bg-blue-100 text-blue-800
                                                @elseif($rental->status === 'active') bg-green-100 text-green-800
                                                @elseif($rental->status === 'completed') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                @if($rental->status === 'pending') Menunggu
                                                @elseif($rental->status === 'approved') Disetujui
                                                @elseif($rental->status === 'active') Aktif
                                                @elseif($rental->status === 'completed') Selesai
                                                @else Dibatalkan
                                                @endif
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-600">Kode Sewa:</span>
                                                <p class="text-gray-900 font-mono">{{ $rental->rental_code }}</p>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Durasi:</span>
                                                <p class="text-gray-900">{{ $rental->total_days }} hari</p>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Tanggal Mulai:</span>
                                                <p class="text-gray-900">{{ $rental->start_date->format('M d, Y') }}</p>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600">Tanggal Selesai:</span>
                                                <p class="text-gray-900">{{ $rental->end_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>

                                        @if($rental->notes)
                                            <div>
                                                <span class="font-medium text-gray-600 text-sm">Catatan:</span>
                                                <p class="text-gray-700 text-sm">{{ $rental->notes }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Pricing & Actions -->
                                    <div class="lg:col-span-1 space-y-4">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Total Biaya</p>
                                            <p class="text-2xl font-bold text-primary-600">
                                                Rp {{ number_format($rental->total_price) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                (Rp {{ number_format($rental->price_per_day) }}/hari)
                                            </p>
                                        </div>

                                        <div class="space-y-2">
                                            <a href="{{ route('booking.rental', $rental) }}" 
                                               class="w-full bg-primary-600 text-white text-center py-2 px-4 rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-sm inline-block">
                                                Lihat Detail
                                            </a>

                                            @if($rental->status === 'pending')
                                                <form method="POST" action="{{ route('booking.cancel', $rental) }}" class="w-full">
                                                    @csrf
                                                    <button type="submit" 
                                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')"
                                                            class="w-full bg-red-100 text-red-700 text-center py-2 px-4 rounded-md hover:bg-red-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm">
                                                        Batalkan Penyewaan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div class="text-xs text-gray-500 text-right">
                                            <p>Dipesan: {{ $rental->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $rentals->appends(request()->query())->links() }}
                </div>
            @else
                <!-- No Rentals Found -->
                <div class="bg-white rounded-lg shadow-sm p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada penyewaan ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request()->hasAny(['search', 'status']))
                                Tidak ada penyewaan yang sesuai dengan filter Anda. Coba sesuaikan kriteria pencarian.
                            @else
                                Anda belum melakukan penyewaan. Mulai jelajahi produk kami untuk melakukan penyewaan pertama.
                            @endif
                        </p>
                        <div class="mt-6">
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('user.rentals') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 mr-3">
                                    Hapus Filter
                                </a>
                            @endif
                            <a href="{{ route('products.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Jelajahi Semua Produk
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>