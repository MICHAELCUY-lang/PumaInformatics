<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <a href="{{ route('admin.events.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Events</a>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Event Categories</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Organize events into overarching structured hierarchies.</p>
        </div>
        <div>
            <button x-data x-on:click="$dispatch('open-modal', 'create-category')" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                Add Category
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

    <!-- Category List -->
    <div class="bg-white border border-gray-100 p-8">
        <ul class="space-y-4">
            @forelse($categories as $category)
                <li class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 group">
                    <div class="flex items-center space-x-4">
                        @if($category->color_accent)
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color_accent }}"></div>
                        @else
                            <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                        @endif
                        <span class="font-serif text-lg text-gray-900">{{ $category->name }}</span>
                        <span class="text-xs text-gray-400 font-sans border px-2 py-0.5 rounded-sm">{{ $category->slug }}</span>
                        @if($category->parent)
                            <span class="text-xs text-gray-400 font-sans">→ {{ $category->parent->name }}</span>
                        @endif
                        @if(!$category->is_active)
                            <span class="text-xs text-red-500 px-2 py-1 bg-red-50 rounded">Inactive</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.event-categories.edit', $category) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                        <form action="{{ route('admin.event-categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </li>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 font-serif italic">No categories created yet.</p>
                </div>
            @endforelse
        </ul>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-category" maxWidth="md">
        <form method="POST" action="{{ route('admin.event-categories.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-serif text-gray-900 mb-6">New Category</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Name</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. Technology" required>
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Color Accent (Hex)</label>
                <input type="text" name="color_accent" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm font-mono" placeholder="#000000">
            </div>

            <div class="mb-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900" checked>
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
    @if(isset($editing))
    <x-modal name="edit-category" maxWidth="md" :show="true">
        <form method="POST" action="{{ route('admin.event-categories.update', $editing) }}" class="p-8">
            @csrf
            @method('PUT')
            <h2 class="text-2xl font-serif text-gray-900 mb-6">Edit Category</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Name</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="{{ old('name', $editing->name) }}" required>
            </div>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Color Accent (Hex)</label>
                <input type="text" name="color_accent" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm font-mono" value="{{ old('color_accent', $editing->color_accent) }}" placeholder="#000000">
            </div>

            <div class="mb-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900" {{ $editing->is_active ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.event-categories.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Cancel</a>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Update</button>
            </div>
        </form>
    </x-modal>
    @endif
</x-admin-layout>
