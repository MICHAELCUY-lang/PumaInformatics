<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.partners.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Network</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Edit Partner</h1>
    </div>

    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data" class="max-w-5xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-12">
            <!-- Name -->
            <div>
                <input type="text" name="name" placeholder="Partner Name" class="w-full text-5xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('name', $partner->name) }}">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-12">
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Category (Tier)</span>
                        <select name="category_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $partner->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Website URL</span>
                        <input type="url" name="website_url" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="https://" value="{{ old('website_url', $partner->website_url) }}">
                        <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Contact Email</span>
                        <input type="email" name="contact_email" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="partner@example.com" value="{{ old('contact_email', $partner->contact_email) }}">
                        <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Logo</span>
                        @if($partner->getFirstMediaUrl('logo'))
                            <div class="w-32 h-32 mb-4 bg-gray-50 flex items-center justify-center p-2 border border-gray-200">
                                <img src="{{ $partner->getFirstMediaUrl('logo') }}" class="max-w-full max-h-full object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                        <p class="text-xs text-gray-400 mt-1">Recommended: PNG with transparent background.</p>
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-4">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_featured', $partner->is_featured) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Featured Partner</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_active', $partner->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-3">Description</span>
                <textarea name="description" rows="4" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-4 text-sm font-sans" placeholder="Partner description...">{{ old('description', $partner->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Update Partner
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
