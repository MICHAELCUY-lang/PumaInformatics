<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.cabinet-departments.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Departments</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Edit Department</h1>
    </div>

    <form method="POST" action="{{ route('admin.cabinet-departments.update', $cabinetDepartment) }}" class="max-w-2xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Cabinet</span>
                <select name="cabinet_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" required>
                    <option value="">— choose a cabinet —</option>
                    @foreach($cabinets as $cabinet)
                        <option value="{{ $cabinet->id }}" @selected(old('cabinet_id', $cabinetDepartment->cabinet_id) == $cabinet->id)>
                            {{ $cabinet->name }} ({{ $cabinet->term_year }})@if($cabinet->is_active) — current @endif
                        </option>
                    @endforeach
                </select>
                @error('cabinet_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <p class="mt-2 text-xs text-gray-400">Moving a department to another cabinet takes its members with it.</p>
            </div>

            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Department Name</span>
                <input type="text" name="name" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('name', $cabinetDepartment->name) }}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Description</span>
                <textarea name="description" rows="3" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">{{ old('description', $cabinetDepartment->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Display Order</span>
                    <input type="number" name="order" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('order', $cabinetDepartment->order) }}" min="0">
                    <p class="text-xs text-gray-400 mt-1">Lower number = appears first</p>
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_active', $cabinetDepartment->is_active) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-900">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-8 border-t border-gray-100">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Update Department
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
