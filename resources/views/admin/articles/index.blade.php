<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Newsroom</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Curate and manage articles, press releases, and editorial content.</p>
        </div>
        <div>
            <a href="{{ route('admin.articles.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Compose Article
            </a>
        </div>
    </div>

    <!-- Articles Table (Museum style) -->
    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Title</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Author</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Date</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($articles as $article)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6">
                            <div class="flex items-center space-x-4">
                                @if($article->is_featured)
                                    <span class="w-1.5 h-1.5 rounded-full bg-museum-black"></span>
                                @endif
                                <div>
                                    <p class="font-serif text-lg text-gray-900">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-400 font-sans mt-0.5">{{ $article->reading_time_minutes }} min read</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 pr-6">
                            @if($article->status === 'published')
                                <span class="text-xs text-gray-900 uppercase tracking-widest font-medium border-b border-gray-300">Published</span>
                            @elseif($article->status === 'draft')
                                <span class="text-xs text-gray-400 uppercase tracking-widest font-medium border-b border-gray-200">Draft</span>
                            @else
                                <span class="text-xs text-blue-500 uppercase tracking-widest font-medium border-b border-blue-200">Scheduled</span>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-600 font-sans">{{ $article->author->name }}</span>
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-sm text-gray-500 font-sans">{{ $article->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this article?');">
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
                            <p class="text-gray-500 font-serif italic">The archive is empty.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</x-admin-layout>
