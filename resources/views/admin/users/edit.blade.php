<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit User: {{ $user->name }}</h1>
            <p class="text-sm text-gray-500">Update account details and access levels.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Directory</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Profile Details</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Account Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($user->hasRole('Super Admin'))
                            <p class="mt-1 text-xs text-orange-500">Note: Super Admin cannot be suspended.</p>
                        @endif
                    </div>
                </div>

                <!-- Security & Roles -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Security & Access</h3>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (Leave blank to keep current)</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                    </div>

                    <div class="pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label>
                        <div class="space-y-2 bg-gray-50 p-4 rounded-md border border-gray-200">
                            @foreach($roles as $role)
                                <label class="inline-flex items-center mr-6 mb-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-museum-black shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black mr-3">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-museum-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black">
                    Update User
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
