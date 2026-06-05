<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <a href="{{ route('admin.partners.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Network</a>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Partner Categories</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Manage hierarchical partner tiers and groups.</p>
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

    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 w-16">Order</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Name</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Parent</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-600 font-mono">{{ $category->order }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            <p class="font-serif text-lg text-gray-900">{{ $category->name }}</p>
                            @if($category->description)
                                <p class="text-xs text-gray-500 font-sans mt-0.5">{{ Str::limit($category->description, 60) }}</p>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-500">{{ $category->parent?->name ?? '—' }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            @if($category->is_active)
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Active</span>
                            @else
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.partner-categories.edit', $category) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.partner-categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No partner categories created yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-category" maxWidth="md">
        <form method="POST" action="{{ route('admin.partner-categories.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-serif text-gray-900 mb-6">New Category</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Name</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. Gold Partner" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" placeholder="Brief description..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Order</label>
                    <input type="number" name="order" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="0" min="0">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" checked>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" x-on:click="$dispatch('close')" class="text-sm text-gray-500 hover:text-gray-900">Cancel</button>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Save</button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Modal -->
    @if(isset($editing))
    <x-modal name="edit-category" maxWidth="md" :show="true">
        <form method="POST" action="{{ route('admin.partner-categories.update', $editing) }}" class="p-8">
            @csrf
            @method('PUT')
            <h2 class="text-2xl font-serif text-gray-900 mb-6">Edit Category</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Name</label>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="{{ old('name', $editing->name) }}" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm">{{ old('description', $editing->description) }}</textarea>
            </div>

            @if(isset($parentOptions))
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Parent Category</label>
                <select name="parent_id" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm">
                    <option value="">None (Top Level)</option>
                    @foreach($parentOptions as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $editing->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 font-sans mb-1">Order</label>
                    <input type="number" name="order" class="w-full border-gray-200 focus:border-gray-900 focus:ring-0 rounded-none p-3 text-sm" value="{{ old('order', $editing->order) }}" min="0">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ $editing->is_active ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.partner-categories.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Cancel</a>
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Update</button>
            </div>
        </form>
    </x-modal>
    @endif
</x-admin-layout>
