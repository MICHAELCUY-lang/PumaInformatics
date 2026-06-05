<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.partners.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Network</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Add Partner</h1>
    </div>

    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="max-w-5xl">
        @csrf
        
        <div class="space-y-12">
            <!-- Name -->
            <div>
                <input type="text" name="name" placeholder="Partner Name" class="w-full text-5xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('name') }}">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-12">
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Category (Tier)</span>
                        <select name="category_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Save Partner
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
