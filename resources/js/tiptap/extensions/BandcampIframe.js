import { Node, mergeAttributes } from '@tiptap/core'

export const BandcampIframe = Node.create({
  name: 'bandcampIframe',

  group: 'block',

  atom: true,

  addAttributes() {
    return {
      src: {
        default: null,
      },
      style: {
        default: 'border: 0; width: 100%; height: 120px;',
      },
      seamless: {
        default: true,
      },
    }
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
    return ({ node }) => {
      const iframe = document.createElement('iframe');
      iframe.src = node.attrs.src
      iframe.setAttribute('style', node.attrs.style || '')
      iframe.setAttribute('seamless', 'true')
      iframe.setAttribute('allow', 'autoplay; encrypted-media')
      iframe.setAttribute('loading', 'lazy')
      iframe.setAttribute('width', '100%')
      iframe.setAttribute('height', '120px')

      return {
        dom: iframe,
      }
    }
  },
})
