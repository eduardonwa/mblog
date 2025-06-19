<script setup lang="ts">
import { ref, provide, computed, onMounted, onUnmounted } from 'vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import LikeButton from '@/components/LikeButton.vue';
import { 
  BlogPostHeader, 
  BlogPostContent, 
  BlogPostMedia,
  type BlogPostProps,
  type LayoutState
} from './index';

const props = defineProps<BlogPostProps>();

// Estado del layout
const layoutState = ref<LayoutState>('collapsed');
const isCollapsed = computed(() => layoutState.value === 'collapsed');

// Proveer estado a componentes hijos
provide('layoutState', {
  state: layoutState,
  toggle: () => {
    layoutState.value = layoutState.value === 'expanded' 
      ? 'collapsed' 
      : 'expanded';
  }
});

// Proveer datos del post
provide('postData', props.post);

const localPost = ref({ ...props.post });

const shouldShowInteractions = ref(false);
const lastScrollPosition = ref(0);

const handleScroll = () => {
  const currentScrollPosition = window.scrollY || window.pageYOffset;
  // Muestra la barra solo cuando se hace scroll hacia abajo
  if (currentScrollPosition > lastScrollPosition.value && currentScrollPosition > 100) {
    shouldShowInteractions.value = true;
  } else if (currentScrollPosition <= 100) {
    // Oculta cuando está cerca del top
    shouldShowInteractions.value = false;
  }
  lastScrollPosition.value = currentScrollPosition;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <SiteLayout>
    <main
      class="blog-post | container"
      data-type="extra-wide"
      :data-state="layoutState"
    >
      <BlogPostHeader />
      <BlogPostContent
        :comments="props.comments"
        :mentionableUsers="props.mentionableUsers"
        :meta="props.meta"
      />
      <BlogPostMedia />
    </main>

    <section class="mobile-interactions-wrapper">
      <LikeButton
          variant="mobile"
          :post="localPost"
          @update:post="(updatedPost: typeof props.post) => localPost = updatedPost"
      />
    </section>
  </SiteLayout>
</template>