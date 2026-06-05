<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-3xl text-gray-900 leading-tight">
                {{ __('Cache Operations') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Infrastructure Control Plane
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Granular Tag Management -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Targeted Invalidation</h3>
                        <p class="text-sm text-gray-500">Purge specific repository cache domains. This is the recommended procedure for resolving localized synchronization discrepancies.</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.cache.tag') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="tag" class="block text-sm font-medium text-gray-700">Cache Domain (Tag)</label>
                                <select id="tag" name="tag" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm rounded-md shadow-sm">
                                    <option value="" disabled selected>Select domain to invalidate...</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag }}">{{ ucfirst($tag) }}</option>
                                    @endforeach
                                </select>
                                @error('tag')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Invalidate Domain
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System Optimization -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-1">System Optimization</h3>
                        <p class="text-sm text-gray-500">Recompile systemic application layers. Execute these routines after deploying updates or modifying configuration variables.</p>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Compile Views</div>
                                    <div class="text-xs text-gray-500">Clears and recompiles all Blade template caches.</div>
                                </div>
                                <form action="{{ route('admin.cache.system') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="views">
                                    <button type="submit" class="text-xs font-semibold text-gray-600 hover:text-gray-900 uppercase tracking-wider px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 transition-colors">Execute</button>
                                </form>
                            </li>
                            <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Refresh Configuration</div>
                                    <div class="text-xs text-gray-500">Purges and rebuilds the configuration cache state.</div>
                                </div>
                                <form action="{{ route('admin.cache.system') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="config">
                                    <button type="submit" class="text-xs font-semibold text-gray-600 hover:text-gray-900 uppercase tracking-wider px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 transition-colors">Execute</button>
                                </form>
                            </li>
                            <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Rebuild Routes</div>
                                    <div class="text-xs text-gray-500">Recompiles the application routing matrix.</div>
                                </div>
                                <form action="{{ route('admin.cache.system') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="routes">
                                    <button type="submit" class="text-xs font-semibold text-gray-600 hover:text-gray-900 uppercase tracking-wider px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 transition-colors">Execute</button>
                                </form>
                            </li>
                            <li class="flex items-center justify-between pt-2">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Full Optimization Suite</div>
                                    <div class="text-xs text-gray-500">Executes comprehensive recompilation of all system layers.</div>
                                </div>
                                <form action="{{ route('admin.cache.system') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="optimize">
                                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900 uppercase tracking-wider px-3 py-1 border border-indigo-200 bg-indigo-50 rounded hover:bg-indigo-100 transition-colors">Optimize</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Global Clear (Dangerous) -->
            <div x-data="{ confirmGlobal: false }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-red-700 mb-1">Global Application Purge</h3>
                        <p class="text-sm text-gray-600">Forces an immediate flush of the entire application data cache. This operation will temporarily degrade systemic performance as all data stores are forcefully rebuilt.</p>
                    </div>
                    <button @click="confirmGlobal = true" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Initiate Global Purge
                    </button>
                </div>
                
                <!-- Confirmation Modal -->
                <div x-show="confirmGlobal" style="display: none;" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div x-show="confirmGlobal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div x-show="confirmGlobal" 
                                 x-transition:enter="ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                 x-transition:leave="ease-in duration-200" 
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                 @click.away="confirmGlobal = false"
                                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <form action="{{ route('admin.cache.system') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="global">
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Confirm Global Purge</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 mb-4">This action will flush all cached data across the entire application stack. This should only be utilized during critical governance anomalies.</p>
                                                    <div class="mt-2">
                                                        <label for="reason" class="block text-sm font-medium text-gray-700">Audit Justification <span class="text-red-500">*</span></label>
                                                        <textarea id="reason" name="reason" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Provide governance reasoning for this global purge..."></textarea>
                                                        <p class="mt-1 text-xs text-gray-500">This reasoning will be permanently logged in the institutional ledger.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Execute Global Purge</button>
                                        <button type="button" @click="confirmGlobal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
