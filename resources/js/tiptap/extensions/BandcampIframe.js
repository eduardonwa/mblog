import { Node, mergeAttributes } from '@tiptap/core'

const BandcampIframe = Node.create({
  name: "bandcampIframe",
  group: "block",
  atom: true,

  addAttributes() {
    return {
      src: { default: null },
      style: {
        default: 'border: 0; width: 100%; height: 120px;',
      },
      seamless: {
        default: 'true',
      }
    }
  },

  addCommands() {
    return {
      insertBandcampIframe: (options) => ({ commands }) => {
        return commands.insertContent({
          type: this.name,
          attrs: options
        });
      }
    };
  },

  parseHTML() {
    return [
      {
        tag: 'iframe[src*="bandcamp.com/EmbeddedPlayer"]',
      },
    ]
  },

  renderHTML({ HTMLAttributes }) {
    return ['iframe', mergeAttributes(HTMLAttributes)]
  },

  addNodeView() {
    return ({ HTMLAttributes }) => {
      const iframe = document.createElement('iframe')
      Object.entries(HTMLAttributes).forEach(([key, value]) => {
        iframe.setAttribute(key, value)
      })
      return { dom: iframe }
    }
  },
})

export default BandcampIframe;
