<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Events Exhibition</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Curate the cultural and academic programs.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.event-categories.index') }}" class="text-gray-500 hover:text-gray-900 px-5 py-2.5 text-sm font-medium transition-colors border border-gray-200">
                Manage Categories
            </a>
            <a href="{{ route('admin.events.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Curate Event
            </a>
        </div>
    </div>

    <!-- Events Table (Cinematic style) -->
    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Event</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Date & Location</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Category</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($events as $event)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6 w-1/3">
                            <div class="flex items-start space-x-4">
                                @if($event->is_featured)
                                    <span class="w-1.5 h-1.5 rounded-full bg-museum-black mt-2.5"></span>
                                @endif
                                <div>
                                    <p class="font-serif text-xl text-gray-900">{{ $event->title }}</p>
                                    @if($event->internal_rsvp_enabled)
                                        <span class="inline-block mt-1 text-[10px] font-sans font-bold uppercase tracking-widest text-museum-black border border-museum-black px-1.5 py-0.5">RSVP Active</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-5 pr-6">
                            <p class="text-sm text-gray-900 font-sans font-medium">{{ $event->start_date->format('M d, Y • h:i A') }}</p>
                            <p class="text-xs text-gray-500 font-sans mt-0.5">{{ $event->location_name ?? 'TBA' }}</p>
                        </td>
                        <td class="py-5 pr-6">
                            @if($event->category)
                                <span class="text-xs font-sans text-gray-600 border px-2 py-1 rounded-sm" style="border-color: {{ $event->category->color_accent ?? '#e5e7eb' }}">
                                    {{ $event->category->name }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Uncategorized</span>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            @if($event->status === 'published')
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Published</span>
                            @elseif($event->status === 'draft')
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Draft</span>
                            @else
                                <span class="text-xs text-blue-500 uppercase tracking-widest font-medium border-b border-blue-200">Scheduled</span>
                            @endif
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.events.edit', $event) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this event?');">
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
                            <p class="text-gray-500 font-serif italic">No events curated yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </div>
</x-admin-layout>
