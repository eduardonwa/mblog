(async () => {
    try {
        const module = await import('@/tiptap/extensions/BandcampIframe.js');
        const BandcampIframe = module.default;
        console.log('[bandcamp extension] BandcampIframe cargado', BandcampIframe);

        // Registrar la extensión globalmente para el editor Tiptap
        window.TiptapEditorExtensions = {
            bandcampIframe: [BandcampIframe],
        };

        // Registro global de editores (si no existe)
        window.TiptapEditors = window.TiptapEditors || {};

        function registrarEditores() {
          document.querySelectorAll('[data-state-path]').forEach((el) => {
            const statePath = el.getAttribute('data-state-path');
            if (!statePath) return;

            const alpineData = Alpine?.closestDataStack(el)?.[0];
            if (alpineData && alpineData.editor && !window.TiptapEditors[statePath]) {
              window.TiptapEditors[statePath] = alpineData.editor;
              console.log(`[bandcamp extension] Editor registrado en window.TiptapEditors['${statePath}']`);
            }
          });
        }

          setTimeout(registrarEditores, 1000);

        // Escuchar evento para insertar iframe desde modal
        window.addEventListener('insert-bandcamp-iframe', (event) => {
          let { statePath, html } = event.detail || {};
          if (!statePath || !html) {
            console.error('[bandcamp extension] Falta statePath o html en el evento insert-bandcamp-iframe');
            return;
          }

          // Asegúrate que el statePath tenga el formato correcto
          statePath = statePath.replace(/\./g, '_');

          const editor = window.TiptapEditors[statePath];
          if (!editor) {
            console.error(`[bandcamp extension] Editor no encontrado para: ${statePath}`);
            return;
          }

          // Aquí parsea el HTML para extraer atributos
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = html;
          const iframe = tempDiv.querySelector('iframe');
          if (!iframe) {
            console.error('[bandcamp extension] No se encontró iframe válido en html');
            return;
          }

          const attrs = {
            src: iframe.getAttribute('src'),
            style: iframe.getAttribute('style'),
            seamless: iframe.hasAttribute('seamless'),
          };

          console.log(`[bandcamp extension] Insertando iframe en editor ${statePath}`, html);
          editor.commands.setBandcampIframe(attrs);
          console.log(`[bandcamp extension] Iframe insertado en editor ${statePath}`);
        });

        console.log('[bandcamp extension] Script loaded');
    } catch (error) {
        console.error('[bandcamp extension] Error al importar BandcampIframe:', error);
    }
})();
