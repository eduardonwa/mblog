<template>
  <Layout>
    <!-- Meta tags (se pueden manejar con Inertia Head) -->
    <Head>
      <title>{{ meta.title }}</title>
      <meta name="description" :content="meta.description">
      <meta name="author" :content="meta.author">
    </Head>

    <section class="max-w-4xl mx-auto py-8">
      <header class="mb-8">
        <!-- Categoría -->
        <Link 
          :href="route('category.show', post.category.slug)" 
          class="text-blue-600 hover:underline"
        >
          {{ post.category.name }}
        </Link>

        <!-- Featured -->
        <h3 v-if="post.featured" class="bg-pink-500 text-white p-4">
          FEATURED
        </h3>

        <!-- Likes (componente reutilizable) -->
        <LikeButton 
          :post-id="post.id" 
          :initial-liked="post.is_liked_by_user"
          :initial-likes-count="post.likes_count"
        />

        <!-- Tags -->
        <div v-if="post.tags?.length" class="flex gap-2 mt-4">
          <Link 
            v-for="tag in post.tags" 
            :key="tag.id"
            :href="route('tag.show', tag.slug.en)"
            class="text-sm bg-gray-200 px-2 py-1 rounded-full"
          >
            #{{ tag.name.en }}
          </Link>
        </div>

        <!-- Título y autor -->
        <h1 class="text-3xl font-bold mt-4">{{ post.title }}</h1>
        <p class="text-gray-600">by {{ post.author?.name || 'Rattlehead' }}</p>
        <p class="text-sm text-gray-500">
          {{ formattedDate(post.created_at) }}
        </p>
      </header>

      <!-- Imagen y extracto -->
      <article class="mb-8">
        <img 
          :src="post.thumbnail_url"
          :alt="post.title"
          class="w-full rounded-lg shadow-md"
        >
        <span class="inline-block mt-2 text-sm text-gray-500">
          {{ post.language }}
        </span>
        <p class="mt-4 text-lg">{{ post.extract }}</p>
      </article>

      <!-- Contenido principal -->
      <section class="prose max-w-none">
        <div v-html="post.body"></div>
      </section>
    </section>
  </Layout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/layouts/SiteLayout.vue';
import LikeButton from '@/components/LikeButton.vue';

const props = defineProps({
  post: Object,
  meta: Object,
});

const formattedDate = (date) => {
  if (!date) return 'Somewhere in Time';
  const diffInWeeks = (new Date() - new Date(date)) / (1000 * 60 * 60 * 24 * 7);
  return diffInWeeks >= 1 
    ? new Date(date).toLocaleDateString() 
    : new Date(date).toRelativeTime();
};
</script>