<div
    x-data="bandcampModal()"
    x-init="window.BandcampModal = $data; init()"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-morado-900 rounded p-6 max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4 clr-morado-100">Bandcamp insert</h2>

        <textarea
            x-ref="iframeInput"
            x-model="html"
            placeholder="Insert the HTML here"
            class="w-full border rounded p-2 resize-none clr-morado-100 bg-morado-950 focus:border-transparent focus:ring-white hover:outline-none"
            rows="4"
        ></textarea>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="isOpen = false" class="bg-transparent p-2 text-red-500 rounded-md">Cancel</button>

            <button @click="insertIframe" class="btn btn-primary p-2 bg-black rounded-md">
                Done
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {

        window.addEventListener('open-bandcamp-modal', (event) => {
            const statePath = event.detail?.statePath;
            console.log('[BandcampModal] Recibido evento para:', statePath);

            const modal = window.BandcampModal;
            if (!modal) {
                console.error('[BandcampModal] Bandcamp modal not found or not initialized');
                return;
            }

            modal.openModal(statePath);
        });

        window.TiptapEditors = window.TiptapEditors || {};

        // Inicial registro general para cuando los editores ya están en el DOM
        setTimeout(() => {
            document.querySelectorAll('[data-state-path]').forEach((el) => {
                const statePath = el.getAttribute('data-state-path');
                if (!statePath) return;

                const alpineData = Alpine?.closestDataStack(el)?.[0];

                if (alpineData && alpineData.editor && !window.TiptapEditors[statePath]) {
                    window.TiptapEditors[statePath] = alpineData.editor;
                    console.log(`Editor registrado en window.TiptapEditors['${statePath}']`);
                }
            });
        }, 500); // Puedes ajustar el delay si necesitas

    });

    function bandcampModal() {
        return {
            isOpen: false,
            statePath: null,
            html: '',

            openModal(path) {
                const normalizedPath = path.replaceAll('.', '_');
                this.statePath = normalizedPath;
                this.isOpen = true;

                // Registro dinámico si aún no existe el editor
                if (!window.TiptapEditors[path]) {
                    console.warn(`[BandcampModal] No se encontró editor para ${path}, intentando registrar...`);

                    const el = document.querySelector(`[data-state-path="${path}"]`);
                    const alpineData = el ? Alpine?.closestDataStack(el)?.[0] : null;

                    if (alpineData && alpineData.editor) {
                        window.TiptapEditors[path] = alpineData.editor;
                        console.log(`[BandcampModal] Editor registrado dinámicamente para ${path}`);
                    } else {
                        console.error(`[BandcampModal] No se pudo registrar editor para ${path}`);
                    }
                }
            },

            closeModal() {
                this.isOpen = false;
                this.html = '';
                this.statePath = null;
            },

            insertIframe() {
                if (!this.statePath) {
                    console.error('[BandcampModal] statePath es null. No se puede insertar iframe.');
                    return;
                }

                const key = this.statePath.replaceAll('.', '_');
                const editor = window.TiptapEditors[this.statePath];

                if (!editor) {
                    console.error(`[BandcampModal] Editor no encontrado para: ${this.statePath}`);
                    console.warn('Editores disponibles:', Object.keys(window.TiptapEditors || {}));
                    return;
                }

                console.log(`[BandcampModal] Insertando HTML en ${key}`, this.html);
                editor.commands.insertContent(this.html, false);
                this.closeModal();
            },

            init() {
                console.log('Modal iniciado');
            }
        };
    }
</script>
