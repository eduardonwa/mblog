<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';

  const { staffPosts } = defineProps({
    staffPosts: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />

    <div v-if="staffPosts?.data.length">
      <div v-for="post in staffPosts?.data" :key="post.id">
          <h2>
              <Link :href="route('post.show', post.slug)">
                  {{ post.title }}
              </Link>
          </h2>
      </div>
    </div>

    <h1>Hola Dios</h1>
  </SiteLayout>
</template>