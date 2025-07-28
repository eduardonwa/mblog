<script setup lang="ts">
import BlogPostRoot, { type BlogPostProps } from '@/components/ui/blog-post/';
import BlogPostMeta from '@/components/ui/blog-post/BlogPostMeta.vue';

const props = defineProps<BlogPostProps>();
</script>

<template>
  <BlogPostMeta :meta="props.meta" />
  <BlogPostRoot v-bind="$props" />
</template>