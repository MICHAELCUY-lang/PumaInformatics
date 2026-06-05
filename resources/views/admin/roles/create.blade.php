<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Create Role</h1>
            <p class="text-sm text-gray-500">Define a new permission group.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Directory</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="p-6">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Role Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Assign Permissions</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($permissions as $group => $perms)
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                            <h4 class="font-semibold text-gray-700 capitalize mb-3 border-b border-gray-200 pb-2">{{ $group }}</h4>
                            <div class="space-y-2">
                                @foreach($perms as $permission)
                                    <label class="flex items-start">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="mt-1 rounded border-gray-300 text-museum-black shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ str_replace($group . '.', '', $permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-100">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black mr-3">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-museum-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black">
                    Create Role
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
