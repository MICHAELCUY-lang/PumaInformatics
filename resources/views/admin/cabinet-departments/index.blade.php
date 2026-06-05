<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <a href="{{ route('admin.cabinet-members.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Roster</a>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Cabinet Departments</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Manage organizational divisions and departments.</p>
        </div>
        <a href="{{ route('admin.cabinet-departments.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
            Add Department
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-8 px-6 py-4 bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-8 px-6 py-4 bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 w-16">Order</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Department</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Members</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($departments as $dept)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-600 font-mono">{{ $dept->order }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            <p class="font-serif text-xl text-gray-900">{{ $dept->name }}</p>
                            @if($dept->description)
                                <p class="text-xs text-gray-500 font-sans mt-1">{{ Str::limit($dept->description, 80) }}</p>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-600">{{ $dept->members_count }} {{ Str::plural('member', $dept->members_count) }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            @if($dept->is_active)
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Active</span>
                            @else
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.cabinet-departments.edit', $dept) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                @if($dept->members_count === 0)
                                    <form action="{{ route('admin.cabinet-departments.destroy', $dept) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No departments created yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
