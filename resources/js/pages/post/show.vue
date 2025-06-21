<script setup lang="ts">
import BlogPostRoot, { type BlogPostProps } from '@/components/ui/blog-post/';

defineProps<BlogPostProps>();
</script>

<template>
  <BlogPostRoot v-bind="$props" />
</template>