<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Enrol Candidate</h1>
            <p class="text-sm text-gray-500">Add a new participant to an election.</p>
        </div>
        <a href="{{ $sessionId ? route('admin.voting-sessions.show', $sessionId) : route('admin.voting-sessions.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Session</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Candidate Profile</h3>
                    
                    <div>
                        <label for="voting_session_id" class="block text-sm font-medium text-gray-700">Voting Session <span class="text-red-500">*</span></label>
                        <select name="voting_session_id" id="voting_session_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('voting_session_id', $sessionId) == $session->id ? 'selected' : '' }}>
                                    {{ $session->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('voting_session_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">Display Order</label>
                            <input type="number" name="order" id="order" value="{{ old('order', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                            @error('order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="flex items-center mt-6">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-museum-black shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Featured Candidate</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Biography</label>
                        <textarea name="biography" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">{{ old('biography') }}</textarea>
                    </div>
                </div>

                <!-- Platform Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Platform Details</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vision Statement</label>
                        <textarea name="vision" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">{{ old('vision') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mission</label>
                        <textarea name="mission" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">{{ old('mission') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Past Achievements</label>
                        <textarea name="achievements" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">{{ old('achievements') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-100">
                <a href="{{ $sessionId ? route('admin.voting-sessions.show', $sessionId) : route('admin.voting-sessions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black mr-3">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-museum-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black">
                    Add Candidate
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
