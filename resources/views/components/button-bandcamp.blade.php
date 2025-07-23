<x-filament-tiptap-editor::button
    label="Bandcamp"
    active="bandcampIframe"
    action="window.dispatchEvent(new CustomEvent('open-bandcamp-modal', {
        detail: { statePath: '{{ $statePath }}' }
    }))"
    x-on:insert-bandcamp-iframe.window="editor().chain().focus().insertBandcampIframe($event.detail.iframeCode, $event.detail.statePath).run();"
>
    <x-icon.bandcamp />
</x-filament-tiptap-editor::button>