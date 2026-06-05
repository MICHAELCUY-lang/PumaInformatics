<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.cabinet-members.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Roster</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Induct Member</h1>
    </div>

    <form method="POST" action="{{ route('admin.cabinet-members.store') }}" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        
        <div class="space-y-12">
            <!-- Name & Basic Info -->
            <div>
                <input type="text" name="name" placeholder="Full Name" class="w-full text-5xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('name') }}">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Configuration Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-12">
                <!-- Role -->
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Role Title</span>
                        <input type="text" name="role_title" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. Vice President" value="{{ old('role_title') }}" required>
                        <x-input-error :messages="$errors->get('role_title')" class="mt-2" />
                    </div>

                    <div class="space-y-6">
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Department</span>
                            <select name="department_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                                <option value="">Executive / Independent</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                        </div>
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Cabinet</span>
                            <select name="cabinet_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                                <option value="">Select Cabinet</option>
                                @foreach($cabinets as $cab)
                                    <option value="{{ $cab->id }}" {{ old('cabinet_id') == $cab->id ? 'selected' : '' }}>{{ $cab->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cabinet_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Term & Hierarchy -->
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Term Year</span>
                        <input type="text" name="term_year" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. 2026-2027" value="{{ old('term_year', '2026-2027') }}" required>
                        <x-input-error :messages="$errors->get('term_year')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Hierarchy Level (Order)</span>
                        <input type="number" name="role_hierarchy_level" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="10" value="{{ old('role_hierarchy_level', 100) }}">
                        <p class="text-xs text-gray-400 mt-1">Lower number = higher rank (1 = President, 10 = Head, 100 = Staff)</p>
                    </div>
                    
                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Active Member</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="border-b border-gray-100 pb-12">
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Portrait Image</span>
                <input type="file" name="portrait" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                <p class="text-xs text-gray-400 mt-1">Recommended: 3:4 aspect ratio, high resolution. System will automatically generate B&W variations.</p>
            </div>

            <!-- Biography -->
            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-3">Biography</span>
                <textarea 
                    name="biography" 
                    rows="12" 
                    class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-6 text-sm font-sans leading-relaxed bg-white"
                    placeholder="Describe the member's professional background, education, and vision..."
                >{{ old('biography') }}</textarea>
                <x-input-error :messages="$errors->get('biography')" class="mt-2" />
                <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest italic">Tip: Use double line breaks for new paragraphs.</p>
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Induct Member
                </button>
            </div>
        </div>
    </form>
    
    @push('styles')
    <style>
        /* Custom scrollbar for biography textarea */
        textarea::-webkit-scrollbar { width: 4px; }
        textarea::-webkit-scrollbar-track { background: #f9f9f9; }
        textarea::-webkit-scrollbar-thumb { background: #e5e5e5; }
    </style>
    @endpush
</x-admin-layout>
