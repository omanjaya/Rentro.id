<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
                <p class="text-gray-600 mt-1">Update user information and settings</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('phone') border-red-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" 
                                id="role" 
                                required
                                @if($user->id === auth()->id()) disabled @endif
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('role') border-red-300 @enderror">
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @if($user->id === auth()->id())
                            <p class="mt-1 text-sm text-gray-500">You cannot change your own role.</p>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                @if($user->id === auth()->id()) disabled @endif
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @if($user->id === auth()->id())
                            <p class="mt-1 text-sm text-gray-500">You cannot change your own status.</p>
                            <input type="hidden" name="status" value="{{ $user->status }}">
                        @endif
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Address
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('address') border-red-300 @enderror">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                    <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Update User
                    </button>
                </div>
            </form>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">User ID:</span>
                    <span class="text-gray-900 ml-2">{{ $user->id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Created:</span>
                    <span class="text-gray-900 ml-2">{{ $user->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="text-gray-900 ml-2">{{ $user->updated_at->format('M j, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Email Verified:</span>
                    <span class="text-gray-900 ml-2">
                        @if($user->email_verified_at)
                            <span class="text-green-600">Yes</span> ({{ $user->email_verified_at->format('M j, Y') }})
                        @else
                            <span class="text-red-600">No</span>
                        @endif
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Total Rentals:</span>
                    <span class="text-gray-900 ml-2">{{ $user->rentals()->count() }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Active Rentals:</span>
                    <span class="text-gray-900 ml-2">{{ $user->rentals()->where('status', 'active')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>