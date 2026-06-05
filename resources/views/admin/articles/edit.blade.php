<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.articles.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Newsroom</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Edit Article</h1>
    </div>

    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <div class="space-y-12">
            <!-- Title -->
            <div>
                <input type="text" name="title" placeholder="Article Title" class="w-full text-4xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('title', $article->title) }}">
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Meta Data (Author, Date, Status) -->
            <div class="flex flex-wrap gap-8 items-center border-b border-gray-100 pb-8">
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Status</span>
                    <select name="status" class="border-0 bg-transparent text-sm font-medium text-gray-900 focus:ring-0 p-0 pr-6 cursor-pointer">
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $article->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
                
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Featured</span>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-900">Feature this article</span>
                    </label>
                </div>

                <div class="flex-1 flex gap-4">
                    @if($article->getFirstMediaUrl('cover'))
                        <div class="w-24 h-16 bg-gray-100 shrink-0">
                            <img src="{{ $article->getFirstMediaUrl('cover', 'thumbnail') }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex-1">
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Cover Image</span>
                        <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                        <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Excerpt -->
            <div>
                <label class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-3">Excerpt (Optional)</label>
                <textarea name="excerpt" rows="2" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-4 text-sm font-sans" placeholder="A brief summary of the article...">{{ old('excerpt', $article->excerpt) }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <!-- TipTap Editor Container -->
            <div x-data="editorInit()" x-init="initEditor()" class="border border-gray-200 bg-white min-h-[500px] flex flex-col">
                <!-- Toolbar -->
                <div class="border-b border-gray-200 p-2 flex items-center space-x-1 bg-gray-50/50">
                    <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('heading', { level: 2 }) }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                    </button>
                    <button type="button" @click="editor.chain().focus().toggleBold().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('bold') }">
                        B
                    </button>
                    <button type="button" @click="editor.chain().focus().toggleItalic().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif italic" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('italic') }">
                        I
                    </button>
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" :class="{ 'bg-gray-200 text-museum-black': editor && editor.isActive('blockquote') }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    </button>
                </div>
                <!-- Content Area -->
                <div x-ref="editorElement" class="prose prose-stone prose-lg max-w-none flex-1 p-8 outline-none font-sans focus:outline-none focus-visible:outline-none tiptap-content"></div>
                <!-- Hidden input to submit content -->
                <input type="hidden" name="content" :value="content">
            </div>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
            
            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-3 text-sm font-medium hover:bg-gray-800 transition-colors">
                    Update Article
                </button>
            </div>
        </div>
    </form>

    @stack('styles')
    @push('styles')
    <style>
        .tiptap-content .ProseMirror { min-height: 400px; outline: none; }
        .tiptap-content .ProseMirror p.is-editor-empty:first-child::before {
            color: #adb5bd; content: attr(data-placeholder); float: left; height: 0; pointer-events: none;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- TipTap via CDN for Alpine integration -->
    <script src="https://unpkg.com/@tiptap/core@2.2.4/dist/tiptap-core.umd.js"></script>
    <script src="https://unpkg.com/@tiptap/starter-kit@2.2.4/dist/tiptap-starter-kit.umd.js"></script>
    <script src="https://unpkg.com/@tiptap/extension-placeholder@2.2.4/dist/tiptap-extension-placeholder.umd.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editorInit', () => ({
                editor: null,
                content: {!! json_encode(old('content', $article->content)) !!},
                initEditor() {
                    this.editor = new tiptapCore.Editor({
                        element: this.$refs.editorElement,
                        extensions: [
                            tiptapStarterKit.StarterKit,
                            tiptapExtensionPlaceholder.Placeholder.configure({
                                placeholder: 'Start writing your story...',
                            }),
                        ],
                        content: this.content,
                        onUpdate: ({ editor }) => {
                            this.content = editor.getHTML();
                        },
                        onTransaction: () => {
                            // Alpine needs to know about the editor state change to update toolbar classes
                            this.editor = this.editor; 
                        }
                    });
                }
            }));
        });
    </script>
    @endpush
</x-admin-layout>
