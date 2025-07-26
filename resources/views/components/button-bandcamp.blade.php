<x-filament-tiptap-editor::button
    label="Bandcamp"
    active="bandcampIframe"
    action="window.dispatchEvent(new CustomEvent('open-bandcamp-modal', {
        detail: { statePath: '{{ str_replace('.', '_', $statePath) }}' }
    }))"
>
    <x-icon.bandcamp />
</x-filament-tiptap-editor::button>