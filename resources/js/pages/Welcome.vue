<script setup lang="ts">
  import { Head } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';
  import { Link } from '@inertiajs/vue3';

  const { posts } = defineProps({
      posts: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />
    
    
    
  </SiteLayout>
</template>