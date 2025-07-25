import { Node } from '@tiptap/core';

export default Node.create({
  name: 'bandcampIframe',
  group: 'block',
  atom: true,

  addOptions() {
    return {
      statePath: null,
    }
  },
});
