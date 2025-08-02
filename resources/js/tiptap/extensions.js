import Bandcamp from './Bandcamp.js';
import EmbedImg from './EmbedImg.js';

window.TiptapEditorExtensions = window.TiptapEditorExtensions || [];
window.TiptapEditorExtensions = {
    bandcamp: [Bandcamp],
    embedImg: [EmbedImg],
}

export {};