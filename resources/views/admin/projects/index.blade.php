<x-admin-layout>
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Innovation Archive</h1>
            <p class="mt-2 text-sm text-gray-500 font-sans">Curate projects, case studies, and research highlights.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.technologies.index') }}" class="text-gray-500 hover:text-gray-900 px-5 py-2.5 text-sm font-medium transition-colors border border-gray-200">
                Manage Stack
            </a>
            <a href="{{ route('admin.projects.create') }}" class="bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors inline-block">
                Archive Project
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-100 p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Project</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Category</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status</th>
                    <th class="pb-4 font-sans text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($projects as $project)
                    <tr class="group hover:bg-museum-light/30 transition-colors">
                        <td class="py-5 pr-6 w-1/3">
                            <p class="font-serif text-xl text-gray-900">{{ $project->title }}</p>
                            @if($project->is_featured)
                                <span class="inline-block mt-1 text-[10px] uppercase tracking-widest text-institutional-gold font-bold">Featured</span>
                            @endif
                        </td>
                        <td class="py-5 pr-6">
                            <p class="text-sm text-gray-900 font-sans">{{ $project->category->name ?? 'None' }}</p>
                        </td>
                        <td class="py-5 pr-6">
                            <span class="text-xs font-sans text-gray-600 border border-gray-200 px-2 py-1 rounded-sm uppercase tracking-wider">{{ $project->status }}</span>
                        </td>
                        <td class="py-5 text-right">
                            <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="text-sm text-gray-500 hover:text-gray-900">Edit</a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <p class="text-gray-500 font-serif italic">No projects added yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    </div>
</x-admin-layout>
