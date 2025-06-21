<script setup lang="ts">
import { nextTick, onUnmounted, provide, ref, onMounted } from 'vue'
import SiteLayout from '@/layouts/SiteLayout.vue'
import LikeButton from '@/components/LikeButton.vue'
import { BlogPostHeader, BlogPostContent, BlogPostMedia, type BlogPostProps } from './index'
import ScrollTrigger from 'gsap/ScrollTrigger'
import { usePostBlocks } from '@/composables/usePostBlocks'
import { useLayoutState } from '@/composables/useLayoutState'
import { useStickyUI } from '@/composables/useStickyUI'
import { useMediaScrollTrigger } from '@/composables/useMediaScroller';

const props = defineProps<BlogPostProps>();
const { layoutState, toggle } = useLayoutState();
const { activeIdx, initTriggers, destroyTriggers } = useMediaScrollTrigger();
const { blocks, textBlocks, mediaItems } = usePostBlocks(props.post.body);
const { shouldShow: shouldShowInteractions } = useStickyUI();
const localPost = ref({ ...props.post });

provide('layoutState', { state: layoutState, toggle });
provide('postData', props.post);
provide('textBlocks', textBlocks)


onMounted(() => {
  nextTick(() => {
    setTimeout(() => {
      initTriggers(mediaItems.value)
    }, 100)
  })
})

onUnmounted(() => {
  ScrollTrigger.getAll().forEach(trigger => trigger.kill());
  destroyTriggers();
})

function onVisible(idx: number) {
  activeIdx.value = idx
};
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
        :text-blocks="textBlocks"
        @paragraph-visible="onVisible"
      />
      <div class="media-sticky">
        <BlogPostMedia 
          :items="mediaItems"
          :active-index="activeIdx"
        />
      </div>
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

<style>
.debug {
  position: fixed;
  bottom: 1rem;
  left: 1rem;
  background: black;
  color: lime;
  font-size: 12px;
  z-index: 9999;
}
</style>