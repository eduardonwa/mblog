import { Node } from '@tiptap/core';

const BandcampIframe = Node.create({
  name: 'bandcampIframe',
  group: 'block',
  atom: true,
  inline: false,
  selectable: true,
  draggable: true,

  addAttributes() {
    return {
      src: { default: '' },
      style: { default: '' },
      seamless: { default: true },
    };
  },

  parseHTML() {
    return [
      { tag: 'iframe[data-bandcamp]' }
    ];
  },

  renderHTML({ HTMLAttributes }) {
    return [
      'iframe',
      {
        ...HTMLAttributes,
        'data-bandcamp': 'true', // ← ¡Clave! Siempre
        frameborder: 0,
        allowtransparency: 'true',
        allow: 'encrypted-media',
        width: '100%',
        height: '120px',
      }
    ];
  },

  addCommands() {
    return {
      setBandcampIframe:
        (attrs) => ({ commands }) => {
          console.log('[bandcamp extension] setBandcampIframe command ejecutado', attrs);
          return commands.insertContent({
            type: 'bandcampIframe',
            attrs,
          });
        },
    };
  },

  addNodeView() {
    return ({ HTMLAttributes }) => {
      const iframe = document.createElement('iframe');
      Object.entries(HTMLAttributes).forEach(([key, value]) => {
        if (value === true) {
          iframe.setAttribute(key, '');
        } else if (value) {
          iframe.setAttribute(key, value);
        }
      });

      return {
        dom: iframe,
      };
    };
  }
});

export default BandcampIframe;
