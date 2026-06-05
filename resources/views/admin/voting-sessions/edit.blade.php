<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Voting Session: {{ $voting_session->title }}</h1>
            <p class="text-sm text-gray-500">Update election configuration.</p>
        </div>
        <a href="{{ route('admin.voting-sessions.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Directory</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.voting-sessions.update', $voting_session) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Session Details</h3>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Session Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $voting_session->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">{{ old('description', $voting_session->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Configuration -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Configuration</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                                <option value="draft" {{ old('status', $voting_session->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $voting_session->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $voting_session->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="archived" {{ old('status', $voting_session->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="results_visibility" class="block text-sm font-medium text-gray-700">Results Visibility <span class="text-red-500">*</span></label>
                            <select name="results_visibility" id="results_visibility" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50" required>
                                <option value="private" {{ old('results_visibility', $voting_session->results_visibility) === 'private' ? 'selected' : '' }}>Private (Admins Only)</option>
                                <option value="voters_only" {{ old('results_visibility', $voting_session->results_visibility) === 'voters_only' ? 'selected' : '' }}>Voters Only</option>
                                <option value="public" {{ old('results_visibility', $voting_session->results_visibility) === 'public' ? 'selected' : '' }}>Public</option>
                            </select>
                            @error('results_visibility') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $voting_session->start_date ? $voting_session->start_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $voting_session->end_date ? $voting_session->end_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-100">
                <a href="{{ route('admin.voting-sessions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black mr-3">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-museum-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-museum-black">
                    Update Session
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
