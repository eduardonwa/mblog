(async () => {
    try {
        // 1. IMPORTAR LA EXTENSIÓN PERSONALIZADA
        const module = await import('@/tiptap/extensions/BandcampIframe.js');
        const BandcampIframe = module.default;

        window.TiptapEditorExtensions = { bandcampIframe: [BandcampIframe] };
        window.TiptapEditors = window.TiptapEditors || {};

        function registerEditors() {
            document.querySelectorAll('[data-state-path]').forEach((el) => {
                const statePath = el.getAttribute('data-state-path');
                if (!statePath) return;
                const alpineData = Alpine?.closestDataStack(el)?.[0];
                let realEditor = alpineData?.editor;
                if (typeof realEditor === 'function') realEditor = realEditor();
                if (realEditor && typeof realEditor === 'object' && !window.TiptapEditors[statePath]) {
                    window.TiptapEditors[statePath] = realEditor;
                }
            });
        }

        // Registra todos los editores al cargar la página
        document.addEventListener('DOMContentLoaded', registerEditors);

        // 6. ESCUCHAR EL EVENTO PARA INSERTAR EL IFRAME DESDE EL MODAL
        window.addEventListener('insert-bandcamp-iframe', (event) => {
            registerEditors(); // Refuerza registro antes de insertar, nunca está de más
            let { statePath, html } = event.detail || {};
            if (!statePath || !html) {
                console.error('[bandcamp extension] Falta statePath o html en el evento insert-bandcamp-iframe');
                return;
            }
            // OBTIENE LA INSTANCIA DEL EDITOR YA REGISTRADO
            const editor = window.TiptapEditors[statePath];

            if (!editor) {
                console.error(`[bandcamp extension] Editor no encontrado para: ${statePath}`);
                return;
            }

            // PARSEA EL HTML PEGADO PARA EXTRAER EL IFRAME Y SUS ATRIBUTOS
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const iframe = tempDiv.querySelector('iframe');
            if (!iframe) {
                console.error('[bandcamp extension] No se encontró iframe válido en html');
                return;
            }

            // CREA LOS ATRIBUTOS PARA EL NODO PERSONALIZADO
            const attrs = {
                src: iframe.getAttribute('src') || '',
                style: iframe.getAttribute('style') || '',
                seamless: iframe.hasAttribute('seamless'),
            };

            if (editor.commands.setBandcampIframe) {
                editor.commands.setBandcampIframe(attrs);
            } else {
                console.error('setBandcampIframe command missing!');
            }
        });

    } catch (error) {
        console.error('[bandcamp extension] Error al importar BandcampIframe:', error);
    }
})();
