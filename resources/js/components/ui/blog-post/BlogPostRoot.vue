<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import LikeButton from '@/components/LikeButton.vue';
import { BlogPostContent, BlogPostHeader, BlogPostMedia } from '.';
import { onMounted, onUnmounted, nextTick, provide, ref } from 'vue'
import { useResponsivePostBlocks } from '@/composables/useResponsivePostBlocks'
import { useMediaScrollTrigger } from '@/composables/useMediaScroller'
import { useLayoutState } from '@/composables/useLayoutState';
import type { BlogPostProps } from '.';
import ShareMenu from '../share-menu/ShareMenu.vue';
import ReportModal from '@/components/ReportModal.vue';

const props = defineProps<BlogPostProps>()

const localPost = ref({ ...props.post })

const { activeIdx, initTriggers, destroyTriggers } = useMediaScrollTrigger()

const {
  isDesktop,
  blocks,
  textBlocks,
  mediaItems,
  rawBody,
  updateLayout
} = useResponsivePostBlocks(props.post.body)

const { layoutState, toggle } = useLayoutState();

provide('layoutState', { state: layoutState, toggle })
provide('postData', props.post)
provide('textBlocks', textBlocks)

onMounted(() => {
  updateLayout()
  window.addEventListener('resize', updateLayout)

  nextTick(() => {
    setTimeout(() => {
      if (isDesktop.value) initTriggers(mediaItems.value)
    }, 100)
  })
})

onUnmounted(() => {
  destroyTriggers()
  window.removeEventListener('resize', updateLayout)
})

function onVisible(idx: number) {
  activeIdx.value = idx
};
// cosas para que el reportmodal se dispare
const isMobile = ref(window.innerWidth < 1280);
function onResize() {
  isMobile.value = window.innerWidth < 1280;
}
onMounted(() => window.addEventListener('resize', onResize));
onUnmounted(() => window.removeEventListener('resize', onResize));
</script>

<template>
  <SiteLayout>
    <main
      class="blog-post | container"
      data-type="extra-wide"
      :data-state="layoutState"
    >
      <BlogPostHeader :url="props.url" />
      <BlogPostContent
        :comments="props.comments"
        :mentionableUsers="props.mentionableUsers"
        :meta="props.meta"
        :text-blocks="textBlocks"
        :raw-body="rawBody"
        :is-desktop="isDesktop"
        @paragraph-visible="onVisible"
      />
      <div class="media-sticky">
        <BlogPostMedia
          v-if="isDesktop"
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
      <ShareMenu
        class="share-menu share-menu--mobile"
       :url="props.url" variant="mobile"
      />
      <ReportModal
        v-if="isMobile"
        :reportable="{ id: post.id, type: 'post' }"
        :popoverId="`reportPopover-mobile-${post.id}`"
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