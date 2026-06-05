<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Session Manager: {{ $session->title }}</h1>
            <p class="text-sm text-gray-500">
                Status: 
                @if($session->status === 'active')
                    <span class="text-green-600 font-medium">Active</span>
                @elseif($session->status === 'draft')
                    <span class="text-gray-600 font-medium">Draft</span>
                @elseif($session->status === 'completed')
                    <span class="text-blue-600 font-medium">Completed</span>
                @else
                    <span class="text-purple-600 font-medium">Archived</span>
                @endif
                | Total Votes Cast: {{ $totalVotes }}
            </p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('admin.voting-sessions.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back to Directory</a>
            <a href="{{ route('admin.voting-sessions.edit', $session) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-xs text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-museum-black focus:ring-offset-2 transition ease-in-out duration-150">
                Edit Settings
            </a>
            <a href="{{ route('admin.candidates.create', ['session' => $session->id]) }}" class="inline-flex items-center px-4 py-2 bg-museum-black border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-museum-black focus:ring-offset-2 transition ease-in-out duration-150">
                Add Candidate
            </a>
        </div>
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

    <!-- Results Dashboard -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-medium text-gray-900">Election Results Dashboard</h3>
            <p class="text-sm text-gray-500">Live tally of all valid votes cast securely via the CSML ledger.</p>
        </div>
        
        <div class="p-6">
            @if($rankedCandidates->count() > 0)
                <div class="space-y-6">
                    @foreach($rankedCandidates as $index => $candidate)
                        @php
                            $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                            $isWinner = $session->status === 'completed' && $index === 0 && $candidate->votes_count > 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $index + 1 }}. {{ $candidate->name }}</span>
                                    @if($isWinner)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            Winner
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-600 font-medium">{{ $candidate->votes_count }} votes ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-museum-black h-2.5 rounded-full {{ $isWinner ? 'bg-institutional-gold' : '' }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 text-sm">
                    No candidates have been added to this session yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Candidate Roster Management -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Candidate Roster Management</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate Name</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($session->candidates as $candidate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center w-16">
                                {{ $candidate->order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $candidate->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($candidate->is_featured)
                                    <span class="text-yellow-500">★</span>
                                @else
                                    <span class="text-gray-300">☆</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.candidates.edit', $candidate) }}" class="text-institutional-navy hover:text-blue-900 transition-colors">Edit</a>
                                    
                                    <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this candidate? This will orphan any associated votes in the ledger.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No candidates enrolled. Click "Add Candidate" to begin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
