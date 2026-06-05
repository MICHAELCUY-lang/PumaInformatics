<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.projects.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Archive</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Edit Project</h1>
    </div>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="max-w-5xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-12">
            <!-- Title -->
            <div>
                <input type="text" name="title" placeholder="Project Title" class="w-full text-5xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('title', $project->title) }}">
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-gray-100 pb-12">
                <div class="space-y-6 md:col-span-1">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Status</span>
                        <select name="status" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Category</span>
                        <select name="category_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-4">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Featured Project</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-6 md:col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Start Date</span>
                            <input type="date" name="start_date" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Completion Date</span>
                            <input type="date" name="completion_date" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('completion_date', optional($project->completion_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Demo URL</span>
                            <input type="url" name="demo_url" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="https://" value="{{ old('demo_url', $project->demo_url) }}">
                        </div>
                        <div>
                            <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Github URL</span>
                            <input type="url" name="github_url" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="https://" value="{{ old('github_url', $project->github_url) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-12">
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Hero Image</span>
                    @if($project->getFirstMediaUrl('hero'))
                        <div class="w-full h-32 mb-4 bg-gray-100 overflow-hidden">
                            <img src="{{ $project->getFirstMediaUrl('hero', 'thumbnail') }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="hero" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                </div>

                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Gallery Images</span>
                    @if($project->hasMedia('gallery'))
                        <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                            @foreach($project->getMedia('gallery') as $media)
                                <div class="w-20 h-20 shrink-0 bg-gray-100">
                                    <img src="{{ $media->getUrl('thumbnail') }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                    <p class="text-xs text-gray-400 mt-1">Select multiple files. Will add to existing gallery.</p>
                </div>
            </div>

            <!-- Description (TipTap Editor) -->
            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-3">Project Description</span>
                <div x-data="editorInit()" x-init="initEditor()" class="border border-gray-200 bg-white min-h-[300px] flex flex-col">
                    <div class="border-b border-gray-200 p-2 flex items-center space-x-1 bg-gray-50/50">
                        <button type="button" @click="editor.chain().focus().toggleBold().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('bold') }">B</button>
                        <button type="button" @click="editor.chain().focus().toggleItalic().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif italic" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('italic') }">I</button>
                    </div>
                    <div x-ref="editorElement" class="prose prose-stone prose-lg max-w-none flex-1 p-6 outline-none font-sans tiptap-content"></div>
                    <input type="hidden" name="description" :value="content">
                </div>
            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Update Project
                </button>
            </div>
        </div>
    </form>
    
    @push('styles')
    <style>
        .tiptap-content .ProseMirror { min-height: 200px; outline: none; }
        .tiptap-content .ProseMirror p.is-editor-empty:first-child::before {
            color: #adb5bd; content: attr(data-placeholder); float: left; height: 0; pointer-events: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/@tiptap/core@2.2.4/dist/tiptap-core.umd.js"></script>
    <script src="https://unpkg.com/@tiptap/starter-kit@2.2.4/dist/tiptap-starter-kit.umd.js"></script>
    <script src="https://unpkg.com/@tiptap/extension-placeholder@2.2.4/dist/tiptap-extension-placeholder.umd.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editorInit', () => ({
                editor: null,
                content: {!! json_encode(old('description', $project->description)) !!},
                initEditor() {
                    this.editor = new tiptapCore.Editor({
                        element: this.$refs.editorElement,
                        extensions: [
                            tiptapStarterKit.StarterKit,
                            tiptapExtensionPlaceholder.Placeholder.configure({ placeholder: 'Project details...' }),
                        ],
                        content: this.content,
                        onUpdate: ({ editor }) => { this.content = editor.getHTML(); },
                        onTransaction: () => { this.editor = this.editor; }
                    });
                }
            }));
        });
    </script>
    @endpush
</x-admin-layout>
