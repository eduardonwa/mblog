import { computed } from 'vue';
import DOMPurify from 'dompurify';
import { Post } from '@/types';

export function useSanitizedHtml(post: Post) {
  DOMPurify.addHook('uponSanitizeElement', (node, data) => {
    if (data.tagName === 'iframe') {
      const src = (node as Element).getAttribute('src') || '';
      if (
        src.includes('bandcamp.com/EmbeddedPlayer') ||
        src.includes('youtube.com/embed')
      ) {
        (node as Element).setAttribute('allow', 'autoplay; encrypted-media');
        (node as Element).setAttribute('allowfullscreen', 'true');
        (node as Element).setAttribute('loading', 'lazy');
      } else {
        node.parentNode?.removeChild(node);
      }
    }
  });

  DOMPurify.setConfig({
    ADD_TAGS: ['iframe'],
    ADD_ATTR: [
      'allow',
      'allowfullscreen',
      'frameborder',
      'scrolling',
      'src',
      'height',
      'width',
      'style',
      'seamless',
      'loading'
    ],
  });

  const sanitizedHtml = computed(() => {
    if (typeof post.list_data_html === 'string' && post.list_data_html) {
      return DOMPurify.sanitize(post.list_data_html);
    }
    if (typeof post.body === 'string' && post.body) {
      return DOMPurify.sanitize(post.body);
    }
    return '';
  });

  return { sanitizedHtml };
}
