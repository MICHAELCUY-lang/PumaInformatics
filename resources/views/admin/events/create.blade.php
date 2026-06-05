<x-admin-layout>
    <div class="mb-12">
        <a href="{{ route('admin.events.index') }}" class="text-sm font-sans text-gray-400 hover:text-gray-900 mb-4 inline-block">&larr; Back to Exhibition</a>
        <h1 class="text-3xl font-serif text-gray-900 tracking-tight">Curate Event</h1>
    </div>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="max-w-5xl">
        @csrf
        
        <div class="space-y-12">
            <!-- Title & Basic Info -->
            <div>
                <input type="text" name="title" placeholder="Event Title" class="w-full text-5xl font-serif text-gray-900 border-0 border-b border-gray-200 focus:border-museum-black focus:ring-0 px-0 py-4 placeholder-gray-300 bg-transparent transition-colors" required value="{{ old('title') }}">
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Configuration Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-gray-100 pb-12">
                <!-- Status & Category -->
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Status</span>
                        <select name="status" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Category</span>
                        <select name="category_id" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm">
                            <option value="">Uncategorized</option>
                            @foreach(\App\Models\EventCategory::orderBy('order')->get() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-4">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Featured Exhibition</span>
                        </label>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Start Date & Time</span>
                        <input type="datetime-local" name="start_date" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('start_date') }}" required>
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">End Date & Time</span>
                        <input type="datetime-local" name="end_date" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="{{ old('end_date') }}">
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Timezone</span>
                        <input type="text" name="timezone" class="w-full border-gray-200 bg-gray-50 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" value="Asia/Jakarta" readonly>
                    </div>
                </div>

                <!-- Location & Registration -->
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Location Name</span>
                        <input type="text" name="location_name" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="e.g. Main Auditorium" value="{{ old('location_name') }}">
                    </div>

                    <div>
                        <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">External Registration URL</span>
                        <input type="url" name="external_registration_url" class="w-full border-gray-200 focus:border-museum-black focus:ring-0 rounded-none p-3 text-sm" placeholder="https://..." value="{{ old('external_registration_url') }}">
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer mt-4">
                            <input type="hidden" name="internal_rsvp_enabled" value="0">
                            <input type="checkbox" name="internal_rsvp_enabled" value="1" class="rounded border-gray-300 text-museum-black focus:ring-museum-black w-5 h-5" {{ old('internal_rsvp_enabled') ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-900">Enable Internal RSVP</span>
                        </label>
                        <p class="text-xs text-gray-400 mt-1 ml-8">Future-ready feature for on-platform ticketing.</p>
                    </div>
                </div>
            </div>

            <!-- Media Uploads (Hero & Gallery) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-12">
                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Featured Hero Image</span>
                    <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                        <input type="file" name="featured_image" class="hidden" x-ref="photo"
                            x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL($refs.photo.files[0]);
                            " />
                        <div class="mt-2" x-show="!photoPreview">
                            <button type="button" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 bg-white hover:bg-gray-50 focus:ring-0" x-on:click.prevent="$refs.photo.click()">
                                Select Image
                            </button>
                        </div>
                        <div class="mt-2 relative" x-show="photoPreview" style="display: none;">
                            <span class="block w-full h-48 bg-cover bg-no-repeat bg-center rounded border border-gray-300" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                            <button type="button" class="absolute top-2 right-2 bg-white/80 p-1 rounded-full text-gray-500 hover:text-red-500" x-on:click="photoName = null; photoPreview = null; $refs.photo.value = null">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />
                </div>

                <div>
                    <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-2">Event Gallery</span>
                    <input type="file" name="gallery[]" multiple class="w-full border border-gray-200 p-3 text-sm focus:border-museum-black focus:ring-0 rounded-none bg-white">
                    <p class="text-xs text-gray-400 mt-1">Hold ctrl/cmd to select multiple images.</p>
                    <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                </div>
            </div>

            <!-- TipTap Editor -->
            <div>
                <span class="block text-xs font-sans font-semibold text-gray-400 uppercase tracking-widest mb-3">Event Description</span>
                <div x-data="tiptapEditor" x-init="initEditor('{!! addslashes(old('description', '')) !!}')" class="border border-gray-200 bg-white min-h-[400px] flex flex-col">
                    <div class="border-b border-gray-200 p-2 flex flex-wrap items-center gap-1 bg-gray-50/50">
                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold text-lg leading-none" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('heading', { level: 1 }) }">H1</button>
                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold leading-none" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('heading', { level: 2 }) }">H2</button>
                        <span class="w-px h-6 bg-gray-300 mx-1"></span>
                        <button type="button" @click="editor.chain().focus().toggleBold().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('bold') }">B</button>
                        <button type="button" @click="editor.chain().focus().toggleItalic().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif italic" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('italic') }">I</button>
                        <span class="w-px h-6 bg-gray-300 mx-1"></span>
                        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('bulletList') }">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M4 6v.01M4 12v.01M4 18v.01" stroke-dasharray="0.1 4" /></svg>
                        </button>
                        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('orderedList') }">
                            <span class="font-sans font-bold text-xs">1.</span>
                        </button>
                        <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors font-serif font-bold" :class="{ 'bg-gray-200 text-museum-black': editor?.isActive('blockquote') }">"</button>
                        <span class="w-px h-6 bg-gray-300 mx-1"></span>
                        <button type="button" @click="editor.chain().focus().undo().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" title="Undo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </button>
                        <button type="button" @click="editor.chain().focus().redo().run()" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors" title="Redo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/></svg>
                        </button>
                        <span class="w-px h-6 bg-gray-300 mx-1"></span>
                        <button type="button" @click="openMediaPicker" class="p-2 text-gray-500 hover:text-museum-black rounded hover:bg-gray-100 transition-colors flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-semibold">Media</span>
                        </button>
                    </div>
                    <div x-ref="editorElement" class="prose prose-stone prose-lg max-w-none flex-1 p-8 outline-none font-sans tiptap-content"></div>
                    <input type="hidden" name="description" :value="content">
                </div>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />

                <!-- Media Modal -->
                <div x-show="mediaModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="mediaModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="mediaModalOpen = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="mediaModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Media</h3>
                                    <div>
                                        <input type="file" @change="handleUpload" class="hidden" x-ref="uploadInput">
                                        <button type="button" @click="$refs.uploadInput.click()" class="bg-museum-black text-white px-4 py-2 text-sm font-medium hover:bg-gray-800 transition-colors" :disabled="isUploading">
                                            <span x-show="!isUploading">Upload New</span>
                                            <span x-show="isUploading">Uploading...</span>
                                        </button>
                                        <button type="button" @click="mediaModalOpen = false" class="ml-2 text-gray-400 hover:text-gray-500">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 max-h-[60vh] overflow-y-auto p-2">
                                    <template x-for="media in mediaItems" :key="media.id">
                                        <div @click="insertMedia(media.url)" class="cursor-pointer border border-gray-200 p-2 rounded hover:border-museum-black transition-colors group relative aspect-square">
                                            <img :src="media.thumbnail" :alt="media.name" class="w-full h-full object-cover rounded">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity flex items-center justify-center">
                                                <span class="bg-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">Insert</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="mediaNextPage" class="mt-4 text-center">
                                    <button type="button" @click="loadMedia(mediaNextPage)" class="text-sm text-museum-black hover:underline">Load More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-museum-black text-white px-8 py-4 text-sm font-medium hover:bg-gray-800 transition-colors tracking-wide">
                    Curate Event
                </button>
            </div>
        </div>
    </form>

    @push('styles')
    <style>
        .tiptap-content .ProseMirror { min-height: 400px; outline: none; }
        .tiptap-content .ProseMirror p.is-editor-empty:first-child::before {
            color: #adb5bd; content: attr(data-placeholder); float: left; height: 0; pointer-events: none;
        }
        .tiptap-content .ProseMirror img {
            max-width: 100%; height: auto; border-radius: 0.5rem; margin-top: 1rem; margin-bottom: 1rem;
        }
    </style>
    @endpush
</x-admin-layout>
