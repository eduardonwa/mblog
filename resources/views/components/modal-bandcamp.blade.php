<div
    x-data="bandcampModal()"
    x-init="init()"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-morado-900 rounded p-6 max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4 clr-morado-100">Bandcamp insert</h2>

        <textarea
            x-model="html"
            placeholder="Insert the HTML here"
            class="w-full border rounded p-2 resize-none clr-morado-100 bg-morado-950 focus:border-transparent focus:ring-white hover:outline-none"
            rows="4"
        ></textarea>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="closeModal()" class="bg-transparent p-2 text-red-500 rounded-md">Cancel</button>
            <button @click="insertIframe()" class="btn btn-primary p-2 bg-black rounded-md">Done</button>
        </div>
    </div>
</div>

<script>
function bandcampModal() {
    return {
        isOpen: false,
        statePath: null,
        html: '',
        init() {
            window.BandcampModal = this;
            window.addEventListener('open-bandcamp-modal', (event) => {
                const path = event.detail?.statePath;
                if (!path) return;
                this.openModal(path);
            });
        },
        openModal(path) {
            this.statePath = path;
            this.isOpen = true;
            this.html = '';
        },
        closeModal() {
            this.isOpen = false;
            this.html = '';
            this.statePath = null;
        },
        insertIframe() {
            if (!this.statePath || !this.html.trim()) return;
            window.dispatchEvent(new CustomEvent('insert-bandcamp-iframe', {
                detail: { statePath: this.statePath, html: this.html }
            }));
            this.closeModal();
            // setTimeout(() => document.activeElement.blur(), 100);
        }
    }
}
</script>

{{-- <script>
    function bandcampModal() {
        return {
            isOpen: false,
            statePath: null,
            html: '',

            init() {
                // Guardar referencia global para acceder desde consola o fuera si quieres
                window.BandcampModal = this;

                // Escuchar el evento global para abrir el modal
                window.addEventListener('open-bandcamp-modal', (event) => {
                    const path = event.detail?.statePath;
                    if (!path) {
                        console.error('[BandcampModal] Evento open-bandcamp-modal sin statePath');
                        return;
                    }

                    this.openModal(path);
                });
            },

            openModal(path) {
                this.statePath = path;
                this.isOpen = true;

                // Opcional: limpiar textarea al abrir
                this.html = '';
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
                if (!this.html.trim()) {
                    console.error('[BandcampModal] html está vacío.');
                    return;
                }
                // console.log('[BandcampModal] insertIframe:', this.statePath);
                // console.log('[BandcampModal] Editores disponibles:', Object.keys(window.TiptapEditors));

                // Emitir evento para que la extensión capture e inserte el iframe
                window.dispatchEvent(new CustomEvent('insert-bandcamp-iframe', {
                    detail: {
                        statePath: this.statePath,
                        html: this.html,
                    }
                }));

                this.closeModal();

                // fuerza blur despues de insertar
                setTimeout(() => {
                    document.activeElement.blur();
                }, 100);
            }
        };
}
</script> --}}