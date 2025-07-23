<div
    x-data="{ open: false, statePath: null }"
    x-show="open"
    x-on:open-bandcamp-modal.window="
        open = true; statePath = $event.detail.statePath
    "
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-morado-900 rounded p-6 max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4 clr-morado-100">Bandcamp insert</h2>

        <textarea
            x-ref="iframeInput"
            placeholder="Insert the HTML here"
            class="w-full border rounded p-2 resize-none clr-morado-100 bg-morado-950 focus:border-transparent focus:ring-white hover:outline-none"
            rows="4"
        ></textarea>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="open = false" class="bg-transparent p-2 text-red-500 rounded-md">Cancel</button>
            <button
                @click="
                    const iframeCode = $refs.iframeInput.value;
                    window.dispatchEvent(new CustomEvent('insert-bandcamp-iframe', { detail: { iframeCode, statePath } }));
                    open = false;
                "
                class="btn btn-primary p-2 bg-black rounded-md"
            >
                Done
            </button>
        </div>
    </div>
</div>
