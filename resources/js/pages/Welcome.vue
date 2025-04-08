<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';

  const { staffPosts, leaderboard } = defineProps({
    staffPosts: Object,
    leaderboard: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />

    <div v-if="staffPosts?.length">
      <div v-for="post in staffPosts" :key="post.id">
          <h2>
              <Link :href="route('post.show', post.slug)">
                  {{ post.title }}
              </Link>
          </h2>
      </div>
    </div>

    <div v-if="leaderboard?.length">
      
      <div v-for="post in leaderboard" :key="post.id">
        <h2>{{ post.title }}</h2>
        <span>{{ post.likes_count }} likes</span>
      </div>

    </div>

    <div v-else>
      No hay posts populares aún
    </div>

    <h1>Hola Dios</h1>
  </SiteLayout>
</template>