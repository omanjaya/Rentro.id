<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New User</h1>
                <p class="text-gray-600 mt-1">Add a new user to the system</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
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
                               value="{{ old('email') }}"
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
                               value="{{ old('phone') }}"
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('role') border-red-300 @enderror">
                            <option value="">Select Role</option>
                            <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
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
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Password</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                    
                    <p class="mt-2 text-sm text-gray-500">Password must be at least 8 characters long.</p>
                </div>

                <!-- Additional Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="email_verified" 
                                   id="email_verified" 
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <label for="email_verified" class="ml-2 text-sm text-gray-700">
                                Mark email as verified
                            </label>
                        </div>
                        
                        <p class="text-sm text-gray-500">
                            New users will be created with verified email status by default.
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Create User
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Information -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">User Creation Guidelines</h3>
            <div class="text-sm text-blue-800 space-y-2">
                <p><strong>Admin Users:</strong> Can access the admin panel, manage products, categories, and users.</p>
                <p><strong>Customer Users:</strong> Can browse products, make bookings, and manage their rental history.</p>
                <p><strong>Email Verification:</strong> Users created through admin panel are automatically verified.</p>
                <p><strong>Password:</strong> Users can change their password after first login if needed.</p>
            </div>
        </div>
    </div>
</x-admin-layout>