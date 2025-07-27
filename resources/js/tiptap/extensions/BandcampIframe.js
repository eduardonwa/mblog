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
        'data-bandcamp': 'true',
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
              (attrs) => ({ commands, editor }) => {
                  console.log('[bandcamp extension] setBandcampIframe command ejecutado', attrs);
                  const result = commands.insertContent({
                      type: 'bandcampIframe',
                      attrs,
                  });
                  // LOG del documento actual:
                  console.log('[bandcamp extension] doc JSON después de insertar:', editor.getJSON());
                  return result;
              },
      };
  },

  addNodeView() {
    return ({ HTMLAttributes }) => {
      const attrs = HTMLAttributes || {};
      const iframe = document.createElement('iframe');
      Object.entries(attrs).forEach(([key, value]) => {
        if (typeof value === 'boolean') {
          if (value) iframe.setAttribute(key, '');
        } else if (typeof value === 'string' && value !== '') {
          iframe.setAttribute(key, value);
        }
      });
      if (!iframe.getAttribute('src')) {
        iframe.setAttribute('src', '');
      }
      return { dom: iframe };
    };
  }

});

export default BandcampIframe;
