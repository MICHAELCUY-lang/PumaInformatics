<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-3xl text-gray-900 leading-tight">
                {{ __('Institutional Ledger') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Activity Audit Trail
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 flex gap-4 border-b border-gray-100 pb-4">
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-sm font-medium {{ !request('log_name') ? 'text-black' : 'text-gray-500' }}">All Activities</a>
                        <a href="{{ route('admin.activity-logs.index', ['log_name' => 'default']) }}" class="text-sm font-medium {{ request('log_name') === 'default' ? 'text-black' : 'text-gray-500' }}">Content</a>
                        @can('view.security_logs')
                            <a href="{{ route('admin.activity-logs.index', ['log_name' => 'security']) }}" class="text-sm font-medium {{ request('log_name') === 'security' ? 'text-red-600' : 'text-red-400' }}">Security Events</a>
                            <a href="{{ route('admin.activity-logs.index', ['log_name' => 'governance']) }}" class="text-sm font-medium {{ request('log_name') === 'governance' ? 'text-purple-600' : 'text-purple-400' }}">Governance</a>
                        @endcan
                    </div>

                    <table class="w-full text-left text-sm text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Timestamp</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Actor</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Action</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Entity</th>
                                <th scope="col" class="px-6 py-3 font-semibold tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-400 font-mono text-xs">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($log->causer)
                                            <span class="font-medium text-gray-900">{{ $log->causer->name }}</span>
                                        @else
                                            <span class="text-gray-400 italic">System / Anonymous</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeColors = [
                                                'created' => 'bg-green-100 text-green-800',
                                                'updated' => 'bg-blue-100 text-blue-800',
                                                'deleted' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $badgeColors[$log->event] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-[10px] uppercase tracking-wider font-semibold rounded {{ $color }}">
                                            {{ $log->event }}
                                        </span>
                                        <span class="ml-2 text-gray-900">{{ $log->description }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-900">{{ class_basename($log->subject_type) }}</span>
                                        @if ($log->subject_id)
                                            <span class="text-gray-400 font-mono text-xs ml-1">#{{ $log->subject_id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.activity-logs.show', $log) }}" class="text-indigo-600 hover:text-indigo-900">Inspect</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
