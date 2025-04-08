<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';

  const { staffPosts, leaderboard } = defineProps({
    staffPosts: Object,
    leaderboard: Object,
    recent: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />

    <div v-if="staffPosts?.length">
      <h2>STAFF</h2>
      <div v-for="post in staffPosts" :key="post.id">
          <h2>
              <Link :href="route('post.show', post.slug)">
                  {{ post.title }}
              </Link>
          </h2>
      </div>
    </div>

    <div v-if="leaderboard?.length">
      <h2>LEADERBOARD</h2>
      <div v-for="post in leaderboard" :key="post.id">
        <h2>{{ post.title }}</h2>
        <span>{{ post.likes_count }} likes</span>
      </div>
    </div>
    <div v-else>
      no one is trying to be popular right now.
    </div>

    <div v-if="recent?.length">
      <h2>MOST RECENT</h2>
      <div v-for="post in recent" :key="post.id">
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