<div
    x-data="{ open: false, statePath: null }"
    x-show="open"
    x-on:open-bandcamp-modal.window="
        open = true; statePath = $event.detail.statePath
        console.log('Evento recibido:', $event.detail);    
    "
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-white rounded p-6 max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4">Insertar iframe Bandcamp</h2>

        <!-- Aquí pones el formulario o campo donde el usuario inserta el iframe -->
        <textarea
            x-ref="iframeInput"
            placeholder="Pega aquí el iframe de Bandcamp"
            class="w-full border rounded p-2"
            rows="4"
        ></textarea>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="open = false" class="btn btn-secondary">Cancelar</button>
            <button
                @click="
                    // Aquí deberías tomar el contenido de iframeInput y enviarlo de vuelta al editor
                    // Por ejemplo: disparar un evento personalizado con el iframe para insertar en tiptap
                    const iframeCode = $refs.iframeInput.value;
                    window.dispatchEvent(new CustomEvent('insert-bandcamp-iframe', { detail: { iframeCode, statePath } }));
                    open = false;
                "
                class="btn btn-primary"
            >
                Insertar
            </button>
        </div>
    </div>
</div>
