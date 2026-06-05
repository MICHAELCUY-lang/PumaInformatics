<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Cabinet Roster</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Manage the institutional leadership and departments.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.cabinet-departments.index') }}" class="text-gray-500 hover:text-gray-900 px-5 py-2.5 text-sm font-medium transition-colors border border-gray-200">
                Manage Departments
            </a>
            <a href="{{ route('admin.cabinet-members.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Induct Member
            </a>
        </div>
    </div>

    <!-- Cabinet Table (Editorial style) -->
    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Member</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Role & Dept</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Term</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($members as $member)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6 w-1/3">
                            <div class="flex items-center space-x-4">
                                @if($member->hasMedia('portrait'))
                                    <img src="{{ $member->getFirstMediaUrl('portrait', 'thumbnail') }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <span class="text-[10px] font-bold text-gray-400">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-serif text-xl text-gray-900">{{ $member->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 pr-6">
                            <p class="text-sm text-gray-900 font-sans font-medium">{{ $member->role_title }}</p>
                            <p class="text-xs text-gray-500 font-sans mt-0.5">{{ $member->department ? $member->department->name : 'Executive / Independent' }}</p>
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-xs font-sans text-gray-600 border border-gray-200 px-2 py-1 rounded-sm">{{ $member->term_year }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            @if($member->is_active)
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Active</span>
                            @else
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Alumni / Inactive</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.cabinet-members.edit', $member) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.cabinet-members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove member?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No cabinet members inducted yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $members->links() }}
        </div>
    </div>
</x-admin-layout>
