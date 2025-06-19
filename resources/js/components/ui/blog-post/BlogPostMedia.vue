<script setup lang="ts">
import { ref, inject, onMounted, onUnmounted } from 'vue';
import type { BlogPostProps } from './index';

const post = inject('postData') as BlogPostProps['post'];
const layoutState = inject('layoutState') as {
  state: { value: 'expanded' | 'collapsed' };
};

// Extraer medios del contenido HTML
const mediaItems = ref<Array<{
  id: string;
  type: 'image' | 'video';
  element: HTMLElement;
  rect: DOMRect;
}>>([]);

// Estado para seguimiento de scroll
const activeMedia = ref<string | null>(null);
const scrollDirection = ref<'up' | 'down'>('down');

const extractMedia = () => {
  const parser = new DOMParser();
  const doc = parser.parseFromString(post.body, 'text/html');
  
  const images = Array.from(doc.querySelectorAll('img')).map((img, i) => ({
    id: `img-${i}`,
    type: 'image' as const,
    element: img,
    rect: img.getBoundingClientRect()
  }));
  
  const videos = Array.from(doc.querySelectorAll('video')).map((video, i) => ({
    id: `vid-${i}`,
    type: 'video' as const,
    element: video,
    rect: video.getBoundingClientRect()
  }));
  
  return [...images, ...videos];
};

// Manejo de scroll
let lastScrollY = 0;

const handleScroll = () => {
  const scrollY = window.scrollY;
  scrollDirection.value = scrollY > lastScrollY ? 'down' : 'up';
  lastScrollY = scrollY;
  
  const viewportCenter = scrollY + window.innerHeight / 2;
  
  // Encontrar el medio más cercano al centro del viewport
  let closestMedia: typeof mediaItems.value[0] | null = null;
  let minDistance = Infinity;
  
  for (const media of mediaItems.value) {
    const mediaCenter = media.rect.top + scrollY + media.rect.height / 2;
    const distance = Math.abs(viewportCenter - mediaCenter);
    
    if (distance < minDistance) {
      minDistance = distance;
      closestMedia = media;
    }
  }
  
  if (closestMedia && closestMedia.id !== activeMedia.value) {
    activeMedia.value = closestMedia.id;
    
    // Aplicar clase al elemento correspondiente
    mediaItems.value.forEach(m => {
      m.element.classList.toggle('media-active', m.id === closestMedia!.id);
      m.element.classList.toggle('media-past', m.id !== closestMedia!.id && scrollY > m.rect.top);
      m.element.classList.toggle('media-future', m.id !== closestMedia!.id && scrollY <= m.rect.top);
    });
  }
};

onMounted(() => {
  mediaItems.value = extractMedia();
  window.addEventListener('scroll', handleScroll);
  handleScroll(); // Inicializar
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>


<template>
  <section 
    class="blog-post__media" 
    grid-area="media"
    :class="{ 'collapsed': layoutState.state.value === 'collapsed' }"
  >
    <article class="media-container">
      <div 
        v-for="media in mediaItems" 
        :key="media.id"
        class="media-item"
        :class="{
          'active': media.id === activeMedia,
          'past': media.id !== activeMedia && scrollDirection === 'up',
          'future': media.id !== activeMedia && scrollDirection === 'down'
        }"
      >
        <component 
          :is="media.type === 'image' ? 'img' : 'video'" 
          :src="(media.element as HTMLImageElement | HTMLVideoElement).src"
          :controls="media.type === 'video'"
          class="media-element"
        />
      </div>
    </article>
  </section>
</template>