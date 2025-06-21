<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps({
  html: {
    type: String,
    required: true
  },
  index: {
    type: Number,
    required: true
  },
  isDesktop: {
    type: Boolean,
    required: false,
    default: false,
  }
})

const el = ref<HTMLElement | null>(null)

const textBlocks = computed(() => {
  return
});
</script>

<template>
  <div
    v-if="!isDesktop"
    ref="el"
    v-html="html"
    class="paragraph-block"
  ></div>
  <div
    v-else
    v-html="html"
    :data-index="props.index"
    :data-paragraph="index"
  >
  </div>
</template>