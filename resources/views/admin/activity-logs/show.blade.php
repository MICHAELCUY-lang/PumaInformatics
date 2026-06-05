<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.activity-logs.index') }}" class="text-gray-400 hover:text-gray-600">&larr; Back to Ledger</a>
            <h2 class="font-serif text-3xl text-gray-900 leading-tight">
                {{ __('Inspect Audit Record') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    
                    <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100">
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-1">Actor</h3>
                            <div class="font-medium text-gray-900">
                                {{ $activity->causer ? $activity->causer->name : 'System / Anonymous' }}
                            </div>
                            @if ($activity->causer)
                                <div class="text-sm text-gray-500 font-mono mt-1">{{ $activity->causer->email }}</div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-1">Timestamp</h3>
                            <div class="font-mono text-gray-900">
                                {{ $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s T') : 'Unknown' }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-1">Event Type</h3>
                            <span class="px-2 py-1 text-[10px] uppercase tracking-wider font-semibold rounded bg-gray-100 text-gray-800">
                                {{ $activity->event }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-1">Subject Entity</h3>
                            <div class="font-medium text-gray-900">
                                {{ $activity->subject_type }} <span class="text-gray-400 font-mono">#{{ $activity->subject_id }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-4">Payload & Diff</h3>
                        <div class="bg-gray-900 rounded-lg p-6 overflow-x-auto">
                            <pre class="text-green-400 font-mono text-sm"><code>{{ json_encode($redactedProperties, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
