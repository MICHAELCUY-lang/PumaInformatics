<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Institutional Network</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Curate official partners, sponsors, and academic relationships.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.partner-categories.index') }}" class="text-gray-500 hover:text-gray-900 px-5 py-2.5 text-sm font-medium transition-colors border border-gray-200">
                Manage Tiers
            </a>
            <a href="{{ route('admin.partners.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Add Partner
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Partner</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Category</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($partners as $partner)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6 w-1/3">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-50 border border-gray-200">
                                    @if($partner->getFirstMediaUrl('logo'))
                                        <img src="{{ $partner->getFirstMediaUrl('logo') }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain p-1">
                                    @else
                                        <span class="text-xs text-gray-400">No Logo</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-serif text-xl text-gray-900">{{ $partner->name }}</p>
                                    @if($partner->is_featured)
                                        <span class="inline-block mt-1 text-[10px] uppercase tracking-widest text-institutional-gold font-bold">Featured</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-5 pr-6">
                            <p class="text-sm text-gray-900 font-sans">{{ $partner->category->name ?? 'None' }}</p>
                        </td>
                        <td class="py-5 pr-6">
                            @if($partner->is_active)
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Active</span>
                            @else
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove partner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No partners added yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $partners->links() }}
        </div>
    </div>
</x-admin-layout>
