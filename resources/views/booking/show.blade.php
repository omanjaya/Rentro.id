<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('products.show', $product->slug) }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pesan {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Summary -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Produk</h3>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                <p class="text-lg font-bold text-primary-600 mt-1">
                                    Rp {{ number_format($product->price_per_day) }}/hari
                                </p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600">{{ $product->description }}</p>
                        </div>

                        <!-- Availability Info -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <h5 class="text-sm font-medium text-blue-900 mb-2">Ketersediaan</h5>
                            <div class="flex items-center space-x-2">
                                @if($product->stock > 0)
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-green-800">{{ $product->stock }} unit tersedia</span>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-red-800">Saat ini stok habis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pemesanan</h3>

                        @if($product->stock > 0)
                            <form method="POST" action="{{ route('booking.store', $product->slug) }}" id="booking-form">
                                @csrf

                                <!-- Date Selection -->
                                <div class="space-y-4 mb-6">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" 
                                               id="start_date" 
                                               name="start_date" 
                                               value="{{ old('start_date') }}" 
                                               min="{{ date('Y-m-d') }}"
                                               required
                                               onchange="updatePricing()"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('start_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                        <input type="date" 
                                               id="end_date" 
                                               name="end_date" 
                                               value="{{ old('end_date') }}" 
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               required
                                               onchange="updatePricing()"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        @error('end_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Additional Notes -->
                                <div class="mb-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Persyaratan khusus atau catatan untuk penyewaan Anda..."
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pricing Summary -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Harga</h4>
                                    <div class="space-y-2" id="pricing-breakdown">
                                        <div class="flex justify-between text-sm">
                                            <span>Harga per hari:</span>
                                            <span>Rp {{ number_format($product->price_per_day) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Durasi:</span>
                                            <span id="duration-display">- hari</span>
                                        </div>
                                        <hr class="border-gray-300">
                                        <div class="flex justify-between font-medium">
                                            <span>Total Biaya:</span>
                                            <span id="total-cost" class="text-lg text-primary-600">Rp 0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Availability Check Button -->
                                <div class="mb-4">
                                    <button type="button" 
                                            id="check-availability" 
                                            onclick="checkAvailability()"
                                            disabled
                                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Periksa Ketersediaan
                                    </button>
                                    <div id="availability-result" class="mt-2"></div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        id="submit-booking"
                                        disabled
                                        class="w-full bg-primary-600 text-white py-3 px-4 rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Konfirmasi Pemesanan
                                </button>

                                <p class="text-xs text-gray-500 mt-3 text-center">
                                    Pemesanan Anda akan menunggu persetujuan dari tim admin kami.
                                </p>
                            </form>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Produk Tidak Tersedia</h3>
                                <p class="mt-1 text-sm text-gray-500">Produk ini saat ini stok habis.</p>
                                <div class="mt-6">
                                    <a href="{{ route('products.index') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                        Jelajahi Produk Lain
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerDay = {{ $product->price_per_day }};
        
        function updatePricing() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const durationDisplay = document.getElementById('duration-display');
            const totalCost = document.getElementById('total-cost');
            const checkBtn = document.getElementById('check-availability');
            const submitBtn = document.getElementById('submit-booking');
            const resultDiv = document.getElementById('availability-result');
            
            if (startDate && endDate && endDate > startDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const timeDiff = end.getTime() - start.getTime();
                const days = Math.floor(timeDiff / (1000 * 3600 * 24)) + 1;
                const total = days * pricePerDay;
                
                durationDisplay.textContent = days + ' ' + (days === 1 ? 'hari' : 'hari');
                totalCost.textContent = 'Rp ' + total.toLocaleString('id-ID');
                checkBtn.disabled = false;
                
                // Update end date minimum
                const nextDay = new Date(start);
                nextDay.setDate(nextDay.getDate() + 1);
                document.getElementById('end_date').min = nextDay.toISOString().split('T')[0];
            } else {
                durationDisplay.textContent = '- hari';
                totalCost.textContent = 'Rp 0';
                checkBtn.disabled = true;
                submitBtn.disabled = true;
                resultDiv.innerHTML = '';
            }
        }
        
        function checkAvailability() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const checkBtn = document.getElementById('check-availability');
            const submitBtn = document.getElementById('submit-booking');
            const resultDiv = document.getElementById('availability-result');
            
            if (!startDate || !endDate) {
                alert('Silakan pilih tanggal mulai dan selesai.');
                return;
            }
            
            // Update button state
            checkBtn.disabled = true;
            checkBtn.textContent = 'Memeriksa...';
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make request
            fetch('{{ route("booking.check-availability", $product->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    resultDiv.innerHTML = '<div class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">✅ Tersedia untuk ' + data.days + ' hari - ' + data.formatted_total + '</div>';
                    submitBtn.disabled = false;
                } else {
                    resultDiv.innerHTML = '<div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md">❌ Tidak tersedia untuk tanggal yang dipilih. Silakan pilih tanggal lain.</div>';
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md">⚠️ Terjadi kesalahan saat memeriksa ketersediaan. Silakan coba lagi.</div>';
            })
            .finally(() => {
                checkBtn.disabled = false;
                checkBtn.textContent = 'Periksa Ketersediaan';
            });
        }
    </script>
</x-app-layout>