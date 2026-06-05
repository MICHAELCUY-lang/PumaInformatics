<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.roles.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Roles</a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-serif text-gray-900 tracking-tight">{{ $role->name }}</h1>
                <p class="mt-2 text-sm text-gray-500 font-sans">Role details and assigned permissions.</p>
            </div>
            <a href="{{ route('admin.roles.edit', $role) }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Edit Role
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Role Info -->
        <div class="bg-white border border-gray-100 p-8">
            <h2 class="text-xl font-serif text-gray-900 mb-6">Details</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest">Name</dt>
                    <dd class="mt-1 text-lg font-serif text-gray-900">{{ $role->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest">Guard</dt>
                    <dd class="mt-1 text-sm text-gray-600 font-mono">{{ $role->guard_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest">Users Assigned</dt>
                    <dd class="mt-1 text-sm text-gray-600">{{ $role->users_count ?? $role->users()->count() }} {{ Str::plural('user', $role->users_count ?? $role->users()->count()) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest">Created</dt>
                    <dd class="mt-1 text-sm text-gray-600">{{ $role->created_at->format('M d, Y \a\t H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Permissions -->
        <div class="bg-white border border-gray-100 p-8">
            <h2 class="text-xl font-serif text-gray-900 mb-6">Permissions ({{ $role->permissions->count() }})</h2>
            @if($role->permissions->count() > 0)
                @php
                    $grouped = $role->permissions->groupBy(function($perm) {
                        return explode('.', $perm->name)[0] ?? 'general';
                    });
                @endphp
                <div class="space-y-6">
                    @foreach($grouped as $group => $perms)
                        <div>
                            <h3 class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">{{ ucfirst($group) }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($perms as $perm)
                                    <span class="text-xs bg-gray-100 text-gray-700 px-3 py-1.5 font-mono">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 font-serif italic">No specific permissions assigned.</p>
                @if($role->name === 'Super Admin')
                    <p class="text-xs text-gray-400 mt-2">Super Admin implicitly has all permissions via gate policy.</p>
                @endif
            @endif
        </div>
    </div>
</x-admin-layout>
