<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Vendor Profile
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Manage your vendor account and business information
                </p>
            </div>
            
            <a href="{{ route('vendor.dashboard') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Verification Status -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Verification Status</h3>
                        <p class="text-sm text-gray-600 mt-1">Your account verification determines product listing capabilities</p>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if($user->verification_status === 'verified')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Verified
                            </span>
                            @if($profileStats['verification_date'])
                                <span class="text-sm text-gray-500">Since {{ $profileStats['verification_date'] }}</span>
                            @endif
                        @elseif($user->verification_status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Pending Review
                            </span>
                        @elseif($user->verification_status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Rejected
                            </span>
                            @if(!$user->isVerified())
                                <form action="{{ route('vendor.profile.request-verification') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded text-sm font-medium">
                                        Request Re-verification
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Not Verified
                            </span>
                            <form action="{{ route('vendor.profile.request-verification') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded text-sm font-medium">
                                    Request Verification
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($user->verification_notes)
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <h4 class="text-sm font-medium text-red-800">Admin Notes:</h4>
                        <p class="mt-1 text-sm text-red-700">{{ $user->verification_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Profile Statistics -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Overview</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($profileStats['total_products']) }}</p>
                        <p class="text-sm text-gray-500">Total Products</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ number_format($profileStats['approved_products']) }}</p>
                        <p class="text-sm text-gray-500">Approved Products</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($profileStats['total_rentals']) }}</p>
                        <p class="text-sm text-gray-500">Total Rentals</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="text-lg font-medium text-gray-900">{{ $profileStats['member_since'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="bg-white shadow-sm rounded-lg">
                <form method="post" action="{{ route('vendor.profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div>
                                        <p class="text-sm mt-2 text-gray-800">
                                            {{ __('Your email address is unverified.') }}

                                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 font-medium text-sm text-green-600">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div>
                                <x-input-label for="commission_rate" :value="__('Commission Rate')" />
                                <x-text-input id="commission_rate" name="commission_rate" type="text" class="mt-1 block w-full bg-gray-100" :value="$user->commission_rate . '%'" readonly />
                                <p class="text-sm text-gray-500 mt-1">Commission rate is set by admin</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" rows="3" 
                                      class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                      placeholder="Your complete address...">{{ old('address', $user->address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="business_name" :value="__('Business Name')" />
                                <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $user->business_name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                            </div>

                            <div>
                                <x-input-label for="business_description" :value="__('Business Description')" />
                                <textarea id="business_description" name="business_description" rows="4" 
                                          class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
                                          placeholder="Describe your business, services, and specialties...">{{ old('business_description', $user->business_description) }}</textarea>
                                <p class="text-sm text-gray-500 mt-1">This description will be visible to customers and admin during verification</p>
                                <x-input-error class="mt-2" :messages="$errors->get('business_description')" />
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white shadow-sm rounded-lg">
                <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Update Password</h3>
                        <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
                    </div>

                    <div>
                        <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                        <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password" :value="__('New Password')" />
                        <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Account</h3>
                        <p class="text-sm text-gray-600">
                            Once your account is deleted, all of your resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                    </div>

                    <div class="pt-6">
                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        >{{ __('Delete Account') }}</x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('vendor.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of your resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>

    <!-- Email Verification Form -->
    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    @endif
</x-app-layout>