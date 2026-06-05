<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-3xl text-gray-900 leading-tight">
                {{ __('Media Exhibition') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Asset Archive
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-900 p-4 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-900 p-4 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Minimalist Filters -->
            <div class="mb-8 flex space-x-4 border-b border-gray-200 pb-4">
                <a href="{{ route('admin.media.index') }}" class="text-sm {{ !request('type') ? 'text-black font-semibold' : 'text-gray-500 hover:text-black' }}">All Media</a>
                <a href="{{ route('admin.media.index', ['type' => 'image']) }}" class="text-sm {{ request('type') === 'image' ? 'text-black font-semibold' : 'text-gray-500 hover:text-black' }}">Images</a>
                <a href="{{ route('admin.media.index', ['type' => 'application/pdf']) }}" class="text-sm {{ request('type') === 'application/pdf' ? 'text-black font-semibold' : 'text-gray-500 hover:text-black' }}">Documents</a>
            </div>

            <!-- Museum Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($media as $item)
                    <div class="group relative bg-white border border-gray-200 overflow-hidden aspect-[4/3] flex flex-col justify-end transition-all hover:shadow-lg">
                        
                        <!-- Visual Asset -->
                        <div class="absolute inset-0 bg-gray-50 flex items-center justify-center p-4">
                            @if (str_starts_with($item->mime_type, 'image/'))
                                <img src="{{ $item->getUrl('thumbnail') ?: $item->getUrl() }}" alt="{{ $item->file_name }}" class="object-contain w-full h-full">
                            @else
                                <div class="text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs uppercase tracking-widest font-semibold">{{ explode('/', $item->mime_type)[1] ?? 'File' }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info Overlay -->
                        <div class="relative bg-white/90 backdrop-blur-sm border-t border-gray-100 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h3 class="text-xs font-semibold text-gray-900 truncate" title="{{ $item->file_name }}">{{ $item->file_name }}</h3>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[10px] text-gray-500 uppercase tracking-wider">{{ number_format($item->size / 1024, 0) }} KB</span>
                                
                                @if ($item->model_type === \App\Models\GlobalMedia::class)
                                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete this orphaned/temporary media?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                    </form>
                                @else
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-1 py-0.5 rounded" title="Bound to {{ class_basename($item->model_type) }}">Bound</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $media->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
