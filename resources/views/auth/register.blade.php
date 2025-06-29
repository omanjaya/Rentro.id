<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Account Type -->
        <div class="mt-4">
            <x-input-label for="user_type" value="Tipe Akun" />
            <select id="user_type" name="user_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="toggleBusinessFields()">
                <option value="">Pilih tipe akun Anda</option>
                <option value="individual" {{ old('user_type') === 'individual' ? 'selected' : '' }}>Pelanggan Individu</option>
                <option value="business" {{ old('user_type') === 'business' ? 'selected' : '' }}>Pelanggan Bisnis</option>
                <option value="vendor" {{ old('user_type') === 'vendor' ? 'selected' : '' }}>Penyedia Peralatan</option>
            </select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
            
            <!-- Account Type Descriptions -->
            <div class="mt-2 text-sm text-gray-600">
                <div id="desc-individual" class="account-desc hidden">
                    <strong>Individu:</strong> Sempurna untuk penyewaan pribadi, mahasiswa, freelancer
                </div>
                <div id="desc-business" class="account-desc hidden">
                    <strong>Bisnis:</strong> Akun perusahaan dengan opsi penyewaan massal dan penagihan
                </div>
                <div id="desc-vendor" class="account-desc hidden">
                    <strong>Penyedia:</strong> Daftarkan peralatan Anda untuk disewakan dan dapatkan uang
                </div>
            </div>
        </div>

        <!-- Business Fields (shown for business and vendor types) -->
        <div id="business-fields" class="hidden">
            <div class="mt-4">
                <x-input-label for="business_name" value="Nama Bisnis/Perusahaan" />
                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="business_description" value="Deskripsi Bisnis" />
                <textarea id="business_description" name="business_description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('business_description') }}</textarea>
                <x-input-error :messages="$errors->get('business_description')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500">Ceritakan tentang bisnis Anda (diperlukan untuk verifikasi)</p>
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Sudah terdaftar?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleBusinessFields() {
            const userType = document.getElementById('user_type').value;
            const businessFields = document.getElementById('business-fields');
            const businessName = document.getElementById('business_name');
            const businessDesc = document.getElementById('business_description');
            
            // Hide all descriptions first
            document.querySelectorAll('.account-desc').forEach(desc => {
                desc.classList.add('hidden');
            });
            
            // Show relevant description
            if (userType) {
                const desc = document.getElementById(`desc-${userType}`);
                if (desc) desc.classList.remove('hidden');
            }
            
            // Show/hide business fields
            if (userType === 'business' || userType === 'vendor') {
                businessFields.classList.remove('hidden');
                businessName.required = true;
                businessDesc.required = true;
            } else {
                businessFields.classList.add('hidden');
                businessName.required = false;
                businessDesc.required = false;
            }
        }

        // Initialize form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleBusinessFields();
        });
    </script>
</x-guest-layout>
