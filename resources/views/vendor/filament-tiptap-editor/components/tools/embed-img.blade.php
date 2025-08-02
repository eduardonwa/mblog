<div x-data="{ showModal: false, url: '', alt: '' }" class="relative">
    <!-- Trigger personalizado -->
    <div
        x-on:click="showModal = true"
        class="filament-tiptap-editor-button flex items-center justify-center cursor-pointer"
        aria-label="Insert Image (URL)"
        title="Insertar imagen por URL"
        tabindex="0"
    >
        <x-icon.img />
    </div>

    <!-- Modal backdrop y contenido -->
    <div
        x-show="showModal"
        x-transition
        class="fixed inset-0 z-[9999] flex items-center justify-center"
        style="display: none"
        @keydown.escape.window="showModal = false"
    >
        <div
            class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm"
            @click="showModal = false"
        ></div>
        <div class="relative bg-white rounded-xl p-6 w-80 shadow-2xl z-[10000]">
            <h2 class="text-lg font-bold mb-2">Insertar imagen por URL</h2>
            <div class="mb-2">
                <input x-model="url" type="text" class="w-full border rounded p-2" placeholder="Pega la URL de la imagen">
            </div>
            <div class="mb-4">
                <input x-model="alt" type="text" class="w-full border rounded p-2" placeholder="Texto alternativo (opcional)">
            </div>
            <div class="flex justify-end space-x-2">
                <button
                    type="button"
                    class="px-3 py-1 bg-gray-200 rounded"
                    @click="showModal = false"
                >Cancelar</button>
                <button
                    type="button"
                    class="px-3 py-1 bg-primary-600 text-white rounded"
                    @click="
                        if(url){
                            $dispatch('tiptap-editor-command', {
                                command: 'setEmbedImg',
                                attrs: { src: url, alt: alt }
                            });
                            showModal = false;
                            url = '';
                            alt = '';
                        }
                    "
                >Insertar</button>
            </div>
        </div>
    </div>
</div>
