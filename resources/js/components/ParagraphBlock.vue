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
  }
})

const el = ref<HTMLElement | null>(null)

const textBlocks = computed(() => {
  return
});
</script>

<template>
  <div
    ref="el"
    v-html="html"
    :data-index="props.index"
    :data-paragraph="index"
    class="paragraph-block"
  ></div>
</template>