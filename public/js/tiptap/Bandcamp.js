import { Node } from '@tiptap/core'

console.log('[Bandcamp] Extensión cargada');

export default Node.create({
    name: 'bandcamp',
    group: 'block',
    atom: true,

    addAttributes() {
        return {
            src: { default: '' },
            width: { default: '100%' },
            height: { default: '120px' },
            frameborder: { default: '0' },
            allow: { default: 'encrypted-media' },
        };
    },

    parseHTML() {
        return [
            {
                tag: 'iframe[data-bandcamp]',
                getAttrs: node => ({
                    src: node.getAttribute('src'),
                    width: node.getAttribute('width'),
                    height: node.getAttribute('height'),
                    frameborder: node.getAttribute('frameborder'),
                    allow: node.getAttribute('allow'),
                })
            }
        ];
    },

    renderHTML({ HTMLAttributes}) {
        return [
            'iframe',
            {
                ...HTMLAttributes,
                'data-bandcamp': 'true',
            }
        ];
    },

    addCommands() {
        return {
            setBandcampIframe:
                attrs => ({ commands }) => {
                    return commands.insertContent({
                        type: this.name,
                        attrs
                    })
                },
            toggleBandcampModal:
                () => () => {
                    window.dispatchEvent(new CustomEvent('open-bandcamp-modal'));
                    return true
                }
        }
    },
})