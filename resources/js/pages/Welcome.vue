<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';

  const { staffPosts, leaderboard, newestStaffPosts } = defineProps({
    staffPosts: Object,
    leaderboard: Object,
    recent: Object,
    newestStaffPosts: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />

    <!-- los ultimos 10 del staff -->
    <div v-if="staffPosts?.length">
      <h2 class="uppercase">ultimos 10 del staff</h2>
      <div v-for="post in staffPosts" :key="post.id">
          <h2>
              <Link :href="route('post.show', post.slug)">
                  {{ post.title }}
              </Link>
          </h2>
      </div>
    </div>

    <!-- los ultimos 3 posts del staff -->
    <div v-if="newestStaffPosts?.length">
      <h2 class="uppercase">ultimos 3 del staff</h2>
      <div v-for="post in newestStaffPosts" :key="post.id">
        <h2>
          <Link :href="route('post.show', post.slug)">
            {{ post.title }}
          </Link>
        </h2>
      </div>
    </div>

    <!-- los mas votados de la comunidad -->
    <div v-if="leaderboard?.length">
      <h2 class="uppercase">los 5 mas votados de la comunidad</h2>

      <div v-for="post in leaderboard" :key="post.id">
        <h2>{{ post.title }}</h2>
        <span>{{ post.likes_count }} likes</span>
      </div>

    </div>
    <div v-else>
      no one's trying to be popular right now, come back later.
    </div>

    <!-- los ultimos 10 posts de la comunidad -->
    <div v-if="recent?.length">
      <h2 class="uppercase">ultimos 10 de la comunidad</h2>
      <div v-for="post in recent" :key="post.id">
        <h2>
          <Link :href="route('post.show', post.slug)">
              {{ post.title }}
          </Link>
        </h2>
      </div>
    </div>

  </SiteLayout>
</template>