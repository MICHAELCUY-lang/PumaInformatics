<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.aspirations.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Inbox</a>
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">{{ $aspiration->subject }}</h1>
            <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $aspiration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                {{ str_replace('_', ' ', $aspiration->status) }}
            </span>
        </div>
        <p class="mt-2 text-sm text-gray-500 font-sans">Submitted on {{ $aspiration->created_at->format('M d, Y') }} &mdash; 
            @if($aspiration->is_anonymous) 
                <span class="text-gray-400 italic">Anonymous</span>
            @else 
                {{ $aspiration->user?->name ?? 'Unknown User' }} 
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="md:col-span-2">
            <div class="bg-white border border-gray-100 p-8 space-y-6">
                <h3 class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Payload</h3>
                <div class="prose prose-sm text-gray-800 font-sans leading-relaxed">
                    {{ $aspiration->payload }}
                </div>
            </div>
            
            @if($aspiration->getMedia('attachments')->count() > 0)
            <div class="mt-8 bg-gray-50 p-6 border border-gray-100">
                <h3 class="text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-4">Attachments</h3>
                <ul class="space-y-2">
                    @foreach($aspiration->getMedia('attachments') as $media)
                        <li class="text-sm"><a href="{{ $media->getUrl() }}" class="text-museum-black underline" target="_blank">{{ $media->file_name }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        
        <div>
            <form method="POST" action="{{ route('admin.aspirations.update', $aspiration) }}" class="bg-white border border-gray-100 p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Update Status</label>
                    <select name="status" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                        @foreach(['pending', 'under_review', 'responded', 'resolved', 'archived', 'rejected'] as $status)
                            <option value="{{ $status }}" {{ $aspiration->status === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Admin Notes (Internal)</label>
                    <textarea name="admin_notes" rows="4" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="Internal moderation notes...">{{ $aspiration->admin_notes }}</textarea>
                </div>
                
                <button type="submit" class="w-full bg-museum-black text-white px-4 py-3 text-sm font-medium hover:bg-gray-800 transition-colors">
                    Update Aspiration
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
