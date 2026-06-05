<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">User Profile</h1>
            <p class="text-sm text-gray-500">Detailed institutional access record.</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Directory</a>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-museum-black border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-museum-black focus:ring-offset-2 transition ease-in-out duration-150">
                Edit User
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 sm:p-10 flex flex-col sm:flex-row gap-8">
            <div class="flex-shrink-0 flex flex-col items-center">
                <div class="h-32 w-32 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-4xl font-bold border-4 border-white shadow-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="mt-4 text-center">
                    @if($user->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active Account
                        </span>
                    @elseif($user->status === 'suspended')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Suspended
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="flex-1 space-y-6">
                <div>
                    <h2 class="text-3xl font-serif text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->email }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Assigned Roles</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-medium bg-blue-50 border border-blue-100 text-blue-800">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 italic">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Account Details</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between">
                                <span class="text-gray-500">Created:</span>
                                <span class="text-gray-900 font-medium">{{ $user->created_at->format('M d, Y H:i') }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-500">Last Updated:</span>
                                <span class="text-gray-900 font-medium">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-500">Email Verified:</span>
                                <span class="text-gray-900 font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Unverified' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Future: Add Activity Logs table specific to this user here -->
</x-admin-layout>
