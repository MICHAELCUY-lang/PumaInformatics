import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';

export default () => ({
    editor: null,
    content: '',
    mediaModalOpen: false,
    mediaItems: [],
    mediaNextPage: null,
    isUploading: false,
    initEditor(initialContent) {
        this.content = initialContent;
        this.editor = new Editor({
            element: this.$refs.editorElement,
            extensions: [
                StarterKit,
                Image.configure({
                    inline: true,
                    allowBase64: true,
                }),
                Placeholder.configure({
                    placeholder: 'Provide details about the exhibition/event...',
                }),
            ],
            content: this.content,
            onUpdate: ({ editor }) => {
                this.content = editor.getHTML();
            },
            onTransaction: () => {
                // Force Alpine to re-render buttons based on active state
                this.editor = this.editor;
            }
        });
    },
    openMediaPicker() {
        this.mediaModalOpen = true;
        this.loadMedia();
    },
    async loadMedia(url = '/admin/media') {
        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            if (url === '/admin/media') {
                this.mediaItems = result.data;
            } else {
                this.mediaItems = [...this.mediaItems, ...result.data];
            }
            this.mediaNextPage = result.next_page_url;
        } catch (error) {
            console.error('Error loading media', error);
        }
    },
    insertMedia(url) {
        this.editor.chain().focus().setImage({ src: url }).run();
        this.mediaModalOpen = false;
    },
    async handleUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.isUploading = true;
        const formData = new FormData();
        formData.append('file', file);

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch('/api/admin/media/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            const result = await response.json();
            if (result.url) {
                this.insertMedia(result.url);
            }
        } catch (error) {
            console.error('Upload failed', error);
            alert('Upload failed. Please try again.');
        } finally {
            this.isUploading = false;
            event.target.value = null; // reset file input
        }
    }
});
