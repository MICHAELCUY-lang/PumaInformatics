import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

import tiptapEditor from './components/tiptap-editor';

Alpine.plugin(intersect);
window.Alpine = Alpine;

Alpine.data('tiptapEditor', tiptapEditor);

Alpine.start();
