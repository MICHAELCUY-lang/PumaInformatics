<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Users</a>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Invitations</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Manage pending user invitations and access tokens.</p>
        </div>
        <div>
            <button x-data x-on:click="$dispatch('open-modal', 'create-invitation')" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                Send Invitation
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-8 px-6 py-4 bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-8 px-6 py-4 bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
    @endif
    @if(session('invitation_link'))
        <div class="mb-8 px-6 py-4 bg-blue-50 border border-blue-200 text-blue-800 text-sm">
            <p class="font-medium mb-1">Invitation Link (share with recipient):</p>
            <code class="block bg-white p-3 border border-blue-100 text-xs font-mono break-all select-all">{{ session('invitation_link') }}</code>
        </div>
    @endif

    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Email</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Role</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Invited By</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Expires</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($invitations as $invitation)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6">
                            <span class="font-serif text-lg text-gray-900">{{ $invitation->email }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-600">{{ $invitation->role?->name ?? '—' }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-500">{{ $invitation->inviter?->name ?? '—' }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            @if($invitation->accepted_at)
                                <span class="text-xs text-green-700 uppercase tracking-widest font-medium border-b border-green-300">Accepted</span>
                            @elseif($invitation->expires_at && $invitation->expires_at->isPast())
                                <span class="text-xs text-red-500 uppercase tracking-widest font-medium border-b border-red-200">Expired</span>
                            @else
                                <span class="text-xs text-amber-600 uppercase tracking-widest font-medium border-b border-amber-300">Pending</span>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-500">{{ $invitation->expires_at?->format('M d, Y') ?? '—' }}</span>
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if(!$invitation->accepted_at)
                                    <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" class="inline-block" onsubmit="return confirm('Revoke this invitation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">Revoke</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No invitations sent yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $invitations->links() }}
        </div>
    </div>

    <!-- Create Invitation Modal -->
    <x-modal name="create-invitation" maxWidth="md">
        <form method="POST" action="{{ route('admin.invitations.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-serif text-gray-900 mb-6">Send Invitation</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Email Address</label>
                <input type="email" name="email" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="user@example.com" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Assign Role</label>
                <select name="role_id" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" required>
                    <option value="">Select a role...</option>
                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" x-on:click="$dispatch('close')" class="text-sm text-gray-500 hover:text-gray-900">Cancel</button>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Send</button>
            </div>
        </form>
    </x-modal>
</x-admin-layout>
