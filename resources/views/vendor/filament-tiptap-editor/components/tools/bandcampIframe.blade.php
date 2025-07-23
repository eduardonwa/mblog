<x-filament-tiptap-editor::button
    action="window.dispatchEvent(new CustomEvent('open-bandcamp-modal', {
        detail: { statePath: '{{ $statePath }}' }
    }))"
    label="Bandcamp"
>
    <x-icon.bandcamp />
</x-filament-tiptap-editor::button>