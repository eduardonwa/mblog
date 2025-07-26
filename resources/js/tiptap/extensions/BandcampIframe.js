import { Node } from '@tiptap/core';

const BandcampIframe = Node.create({
  name: 'bandcampIframe',
  group: 'block',
  atom: true,

  addAttributes() {
    return {
      src: {},
      style: {},
      seamless: { default: true },
    };
  },

  parseHTML() {
    return [{ tag: 'iframe[data-bandcamp]' }];
  },

  renderHTML({ HTMLAttributes }) {
    return ['iframe', {
      ...HTMLAttributes,
      frameborder: 0,
      allowtransparency: 'true',
      allow: 'encrypted-media',
    }];
  },

  addCommands() {
    return {
      setBandcampIframe:
        (attrs) =>
        ({ commands }) => {
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

      iframe.setAttribute('frameborder', '0');
      iframe.setAttribute('allowtransparency', 'true');
      iframe.setAttribute('allow', 'encrypted-media');
      iframe.style.width = '100%';
      iframe.style.height = '120px';

      return {
        dom: iframe,
      };
    };
  }
});

export default BandcampIframe;
