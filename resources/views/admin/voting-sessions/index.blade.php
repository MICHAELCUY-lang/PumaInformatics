<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Elections & Voting</h1>
            <p class="text-sm text-gray-500">Manage CSML elections and student polling.</p>
        </div>
        <a href="{{ route('admin.voting-sessions.create') }}" class="inline-flex items-center px-4 py-2 bg-museum-black border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-museum-black focus:ring-offset-2 transition ease-in-out duration-150">
            Create Session
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-sm border border-gray-100 mb-6">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('admin.voting-sessions.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search sessions..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                </div>
                <div class="w-48">
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-museum-black focus:ring focus:ring-museum-black focus:ring-opacity-50">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="archived" {{ ($filters['status'] ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm hover:bg-gray-200 transition-colors">
                    Filter
                </button>
                @if(array_filter($filters))
                    <a href="{{ route('admin.voting-sessions.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timeline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sessions as $session)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $session->title }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($session->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($session->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @elseif($session->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                                @elseif($session->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Completed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Archived</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $session->start_date ? $session->start_date->format('M d, Y') : 'TBD' }}</div>
                                <div>to {{ $session->end_date ? $session->end_date->format('M d, Y') : 'TBD' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $session->candidates_count ?? 0 }} Candidates</div>
                                <div>{{ $session->votes_count ?? 0 }} Votes</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.voting-sessions.show', $session) }}" class="text-gray-400 hover:text-gray-900 transition-colors">Manage</a>
                                    <a href="{{ route('admin.voting-sessions.edit', $session) }}" class="text-institutional-navy hover:text-blue-900 transition-colors">Edit</a>
                                    
                                    <form action="{{ route('admin.voting-sessions.destroy', $session) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No voting sessions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $sessions->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
