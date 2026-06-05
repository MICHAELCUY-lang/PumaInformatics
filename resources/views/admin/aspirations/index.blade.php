<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Student Inbox</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Review, moderate, and respond to student aspirations.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.aspiration-categories.index') }}" class="text-gray-500 hover:text-gray-900 px-5 py-2.5 text-sm font-medium transition-colors border border-gray-200">
                Manage Categories
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-100 p-8">
        <p class="text-gray-500 italic">Aspirations List (Placeholder for tests)</p>
        @if(isset($aspirations) && count($aspirations) > 0)
            <ul class="mt-4">
                @foreach($aspirations as $aspiration)
                    <li><a href="{{ route('admin.aspirations.show', $aspiration) }}">{{ $aspiration->subject }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
</x-admin-layout>
