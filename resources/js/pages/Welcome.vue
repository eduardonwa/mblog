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

    <!-- 1. Primer post más reciente del staff -->
    <div v-if="newestStaffPosts?.length > 0">
      <div class="newest-staff-post">

        <h2 class="uppercase">Latest Staff Post</h2>
        
        <picture>
          <source media="(min-width: 768px)" :srcset="newestStaffPosts?.[0]?.thumbnail_urls?.lg">
          <source media="(max-width: 767px)" :srcset="newestStaffPosts?.[0]?.thumbnail_urls?.md">
          <img 
            :src="newestStaffPosts?.[0]?.thumbnail_urls?.md" 
            :alt="newestStaffPosts?.[0]?.title"
            class="post-thumbnail"
          >
        </picture>

        <h3>
          <Link :href="route('post.show', newestStaffPosts?.[0].slug)">
            {{ newestStaffPosts?.[0].title }}
          </Link>
        </h3>

        <p>{{ newestStaffPosts?.[0].smart_date }}</p>
        <p>By {{ newestStaffPosts?.[0].author.name }}</p>
        <p>{{ newestStaffPosts?.[0].extract }}</p>
        <span class="ml-1">{{ newestStaffPosts?.[0].likes_count }} likes </span>

      </div>
    </div>

    <!-- 2. Leaderboard -->
    <div v-if="leaderboard?.length">
      <h2 class="uppercase">leaderboard</h2>
      <div v-for="post in leaderboard" :key="post.id">
        <h3>{{ post.title }}</h3>
        <span>{{ post.likes_count }} likes</span>
      </div>
    </div>

    <!-- 3. Los siguientes 2 posts más recientes del staff -->
    <div v-if="newestStaffPosts?.length > 1"  class="secondary-posts">
      <h2>More from Staff</h2>
      <div v-for="(post, index) in newestStaffPosts?.slice(1, 3)" :key="post.id">
        <h3>
          <Link :href="route('post.show', post.slug)">
            {{ post.title }}
          </Link>
        </h3>

        <p>By {{ post.author.name }}</p>
        <p>{{ newestStaffPosts?.extract }}</p>
        <span class="ml-1">{{ post.likes_count }} likes</span>
        
        <picture>
          <source media="(min-width: 768px)" :srcset="post.thumbnail_urls?.md">
          <source media="(max-width: 767px)" :srcset="post.thumbnail_urls?.sm">
          <img 
            :src="post.thumbnail_urls?.sm" 
            :alt="post.title"
            class="post-thumbnail"
          >
        </picture>
      </div>
    </div>

    <!-- 4. Posts recientes de la comunidad -->
    <div v-if="recent?.length">
      <h2>Community Posts</h2>
      <div v-for="post in recent" :key="post.id">
        <h3>
          <Link :href="route('post.show', post.slug)">
            {{ post.title }}
          </Link>
        </h3>
        <p>By {{ post.author.name }}</p>
      </div>
    </div>

  </SiteLayout>
</template>