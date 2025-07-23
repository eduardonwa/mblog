<div
    x-data="{ open: false, statePath: null }"
    x-show="open"
    x-on:open-bandcamp-modal.window="
        open = true; statePath = $event.detail.statePath
    "
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-white rounded p-6 max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4">Bandcamp insert</h2>

        <textarea
            x-ref="iframeInput"
            placeholder="Bandcamp's HTML code goes here"
            class="w-full border rounded p-2"
            rows="4"
        ></textarea>

        <div class="mt-4 flex justify-end space-x-2">
            <button @click="open = false" class="btn btn-secondary">Cancel</button>
            <button
                @click="
                    const iframeCode = $refs.iframeInput.value;
                    window.dispatchEvent(new CustomEvent('insert-bandcamp-iframe', { detail: { iframeCode, statePath } }));
                    open = false;
                "
                class="btn btn-primary"
            >
                Done
            </button>
        </div>
    </div>
</div>
