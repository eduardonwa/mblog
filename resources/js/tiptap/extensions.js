(async () => {
    try {
        // 1. IMPORTAR LA EXTENSIÓN PERSONALIZADA
        const module = await import('@/tiptap/extensions/BandcampIframe.js');
        const BandcampIframe = module.default;

        // 2. REGISTRAR LA EXTENSIÓN EN UNA VARIABLE GLOBAL (por si lo requieres después)
        window.TiptapEditorExtensions = {
          bandcampIframe: [BandcampIframe],
        };

        // 3. CREAR REGISTRO GLOBAL DE INSTANCIAS DE EDITOR
        window.TiptapEditors = window.TiptapEditors || {};

        // 4. FUNCIÓN PARA BUSCAR Y REGISTRAR CADA EDITOR POR SU data-state-path
        function registerEditors() {
            document.querySelectorAll('[data-state-path]').forEach((el) => {
              const statePath = el.getAttribute('data-state-path');
              if (!statePath) return;

              // Buscar instancia real de editor usando Alpine
              const alpineData = Alpine?.closestDataStack(el)?.[0];
              let realEditor = alpineData?.editor;
              if (typeof realEditor === 'function') {
                realEditor = realEditor(); // Si es función, ejecútala
              }
              // Guardar solo si es objeto (tiene .commands, etc)
              if (realEditor && typeof realEditor === 'object' && !window.TiptapEditors[statePath]) {
                window.TiptapEditors[statePath] = realEditor;
                console.log(`[bandcamp extension] Editor registrado (objeto) en window.TiptapEditors['${statePath}']`);
              } else if (!realEditor) {
                console.warn(`[bandcamp extension] No se encontró instancia real de editor para: ${statePath}`);
              }
            });
        }

        // 5. ESPERAR 1 SEGUNDO PARA REGISTRAR (asegura que Alpine ya montó los componentes)
        setTimeout(registerEditors, 1000);

        // 6. ESCUCHAR EL EVENTO PARA INSERTAR EL IFRAME DESDE EL MODAL
        window.addEventListener('insert-bandcamp-iframe', (event) => {
          let { statePath, html } = event.detail || {};
          if (!statePath || !html) {
            console.error('[bandcamp extension] Falta statePath o html en el evento insert-bandcamp-iframe');
            return;
          }

          // Formatea el statePath para asegurar coincidencia
          statePath = statePath.replace(/\./g, '_');

          // OBTIENE LA INSTANCIA DEL EDITOR YA REGISTRADO
          const editor = window.TiptapEditors[statePath];
          console.log('Editor (después de fix):', editor);

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
            src: iframe.getAttribute('src'),
            style: iframe.getAttribute('style'),
            seamless: iframe.hasAttribute('seamless'),
          };

          // LOGS PARA DEBUG
          console.log('Editor:', editor);
          console.log('Editor.commands:', editor.commands);
          console.log('BandcampIframe extension present:', !!editor.extensionManager.extensions.find(e => e.name === 'bandcampIframe'));

          // INSERTA EL NODO PERSONALIZADO EN EL EDITOR (SI EL COMANDO EXISTE)
          if (editor.commands.setBandcampIframe) {
            editor.commands.setBandcampIframe(attrs);
            console.log(editor.getJSON());
          } else {
            console.error('setBandcampIframe command missing!');
          }
        });

    } catch (error) {
      console.error('[bandcamp extension] Error al importar BandcampIframe:', error);
    }
})();
