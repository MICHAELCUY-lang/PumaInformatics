<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.cabinets.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Cabinet Periods</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Edit Cabinet Period</h1>
    </div>

    <form method="POST" action="{{ route('admin.cabinets.update', $cabinet) }}" class="max-w-2xl" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Cabinet Name</span>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('name', $cabinet->name) }}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Term Year</span>
                <input type="text" name="term_year" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('term_year', $cabinet->term_year) }}" required>
                <x-input-error :messages="$errors->get('term_year')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Generation</span>
                    <input type="number" name="generation" min="1" max="99" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('generation', $cabinet->generation) }}">
                    <p class="mt-2 text-xs text-gray-400">Orders the lineage strip on the public site.</p>
                    <x-input-error :messages="$errors->get('generation')" class="mt-2" />
                </div>

                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Tagline</span>
                    <input type="text" name="tagline" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('tagline', $cabinet->tagline) }}">
                    <x-input-error :messages="$errors->get('tagline')" class="mt-2" />
                </div>
            </div>

            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Cabinet Logo</span>
                @if($cabinet->getFirstMedia('logo'))
                    <div class="flex items-center gap-4 mb-3">
                        <img src="{{ $cabinet->logoUrl() }}" alt="" class="w-16 h-16 object-contain border border-gray-200 bg-white p-1">
                        <span class="text-xs text-gray-400">Uploading a new file replaces this one.</span>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full border border-gray-200 rounded-none p-3 text-sm">
                <p class="mt-2 text-xs text-gray-400">Shown in the lineage strip under the homepage hero. Square works best; max 2&nbsp;MB.</p>
                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
            </div>

            <div class="flex items-end pb-1">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_active', $cabinet->is_active) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-gray-900">Set as Active Period</span>
                </label>
                <p class="text-xs text-gray-400 ml-4">Activating this period will deactivate all others.</p>
            </div>

            <div class="flex justify-end pt-8 border-t border-gray-100">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Update Period
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
