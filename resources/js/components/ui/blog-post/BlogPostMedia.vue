<script setup lang="ts">
import { inject, computed } from 'vue'
import type { BlogPostProps } from './index'
import { useMediaAnimation } from '@/composables/useMediaAnimation';

const layoutState = inject('layoutState') as {
  state: { value: 'expanded' | 'collapsed' };
}

const post = inject('postData') as BlogPostProps['post']

const props = defineProps<{
  items: Array<{ index: number; src: string; type: string }>
  activeIndex: number | null
}>()

const current = computed(() =>
  props.items.find(m => m.index === props.activeIndex) ?? null
)

const { setMediaEl } = useMediaAnimation(props.items, () => props.activeIndex)
</script>

<template>
  <section 
    class="blog-post__media" 
    grid-area="media"
    :class="{ 'collapsed': layoutState.state.value === 'collapsed' }"
  >
    <div class="media-container">
      <div
        v-for="(m,i) in items"
        :key="m.src"
        :class="['media-item', { 'visible': m.index === activeIndex }]"
      >
        <component
          :is="m.type === 'image' ? 'img' : 'iframe'"
          :src="m.src"
          class="media-element"
        />
      </div>  
    </div>

    <!-- <div class="debug">
      activeIdx={{ activeIndex }} — current={{ current?.src || 'null' }}
    </div>
    <pre class="debug">activeIndex: {{ activeIndex }}</pre> -->
  </section>
</template>