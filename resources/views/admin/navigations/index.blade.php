<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Navigation</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Manage the public facing hierarchical navigation structure.</p>
        </div>
        <div>
            <button x-data x-on:click="$dispatch('open-modal', 'create-navigation')" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                Add Menu Item
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

    <!-- Navigation List with SortableJS via Alpine -->
    <div 
        class="bg-white border border-gray-100 p-8"
        x-data="navigationManager()"
        x-init="initSortable()"
    >
        <ul id="navigation-list" class="space-y-3">
            @forelse($navigations as $nav)
                <li data-id="{{ $nav->id }}" class="border-b border-gray-100 pb-3 last:border-0 group cursor-move">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span class="font-serif text-lg text-gray-900">{{ $nav->name }}</span>
                            <span class="text-sm text-gray-400 font-sans">{{ $nav->url }}</span>
                            @if($nav->is_external)
                                <span class="text-xs text-blue-500 px-2 py-1 bg-blue-50 rounded">External</span>
                            @endif
                            @if(!$nav->is_active)
                                <span class="text-xs text-red-500 px-2 py-1 bg-red-50 rounded">Hidden</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.navigations.edit', $nav) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                            <form action="{{ route('admin.navigations.destroy', $nav) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this menu item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                    @if($nav->children && $nav->children->count())
                        <ul class="ml-12 mt-3 space-y-2">
                            @foreach($nav->children as $child)
                                <li class="flex items-center justify-between group/child">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-gray-300">└</span>
                                        <span class="font-sans text-sm text-gray-700">{{ $child->name }}</span>
                                        <span class="text-xs text-gray-400 font-sans">{{ $child->url }}</span>
                                        @if(!$child->is_active)
                                            <span class="text-xs text-red-500 px-2 py-1 bg-red-50 rounded">Hidden</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3 opacity-0 group-hover/child:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.navigations.edit', $child) }}" class="text-xs text-gray-500 hover:text-gray-900">Edit</a>
                                        <form action="{{ route('admin.navigations.destroy', $child) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub-item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 font-serif italic">No navigation items crafted yet.</p>
                </div>
            @endforelse
        </ul>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-navigation" maxWidth="md">
        <form method="POST" action="{{ route('admin.navigations.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-serif text-gray-900 mb-6">New Item</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Label</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. About Us" required>
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">URL / Path</label>
                <input type="text" name="url" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="/about" required>
            </div>

            <div class="mb-5">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="is_external" value="0">
                    <input type="checkbox" name="is_external" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5">
                    <span class="text-sm font-medium text-gray-700">External Link</span>
                </label>
            </div>

            <div class="mb-6">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" checked>
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" x-on:click="$dispatch('close')" class="text-sm text-gray-500 hover:text-gray-900">Cancel</button>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Save</button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Modal (shown when editing) -->
    @if(isset($navigation))
    <x-modal name="edit-navigation" maxWidth="md" :show="true">
        <form method="POST" action="{{ route('admin.navigations.update', $navigation) }}" class="p-8">
            @csrf
            @method('PUT')
            <h2 class="text-2xl font-serif text-gray-900 mb-6">Edit Item</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Label</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="{{ old('name', $navigation->name) }}" required>
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">URL / Path</label>
                <input type="text" name="url" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="{{ old('url', $navigation->url) }}" required>
            </div>

            <div class="mb-5">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="is_external" value="0">
                    <input type="checkbox" name="is_external" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ $navigation->is_external ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">External Link</span>
                </label>
            </div>

            <div class="mb-6">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ $navigation->is_active ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.navigations.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Cancel</a>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Update</button>
            </div>
        </form>
    </x-modal>
    @endif

    <!-- SortableJS and Alpine logic -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigationManager', () => ({
                initSortable() {
                    let el = document.getElementById('navigation-list');
                    if(el) {
                        Sortable.create(el, {
                            animation: 150,
                            ghostClass: 'bg-gray-50',
                            onEnd: (evt) => {
                                let items = [];
                                el.querySelectorAll(':scope > li').forEach((item, index) => {
                                    items.push({
                                        id: item.dataset.id,
                                        order: index,
                                        parent_id: null
                                    });
                                });
                                
                                fetch('{{ route('admin.navigations.reorder') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ items: items })
                                });
                            }
                        });
                    }
                }
            }));
        });
    </script>
    @endpush
</x-admin-layout>
