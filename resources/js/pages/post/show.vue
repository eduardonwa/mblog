<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';
  import LikeButton from '@/components/LikeButton.vue';
  import { ref } from 'vue'

  const { post, meta } = defineProps({
      post: Object,
      meta: Object,
  });

  // Solución para evitar mutar props directamente
  const localPost = ref({...post});
  const openLightbox = ref(false);
</script>

<template>
  <SiteLayout>

    <Head>
      <title>{{ meta?.title }}</title>
      <meta name="description" :content="meta?.description">
      <meta name="author" :content="meta?.author">
    </Head>

    <section class="blog-post | container">
      <header class="post-header">
        <div class="post-header__meta-group">
          <!-- titulo y extracto -->
          <div class="post-title-group">
              <h1>{{ post?.title }}</h1>
              <p class="extract">{{ post?.extract }}</p>
          </div>

          <!-- autor fecha y categoria -->
          <div class="meta-primary">
            <Link :href="route('author.posts', post?.author.name)">
              <p>{{ post?.author?.name || 'Rattlehead' }}</p>
            </Link>

            <p>{{ post?.smart_date }}</p>

            <Link :href="route('category.show', post?.category.slug)">
              {{ post?.category.name }}
            </Link>
          </div>
        </div>

        <!-- Likes -->
        <LikeButton 
            :post="localPost" 
            @update:post="updatedPost => localPost = updatedPost" 
        />

        <!-- Tags -->
        <div v-if="post?.tags?.length">
          <Link 
            v-for="tag in post?.tags" 
            :key="tag.id"
            :href="route('tag.show', tag.slug.en)"
          >
            #{{ tag.name.en }}
          </Link>
        </div>
      </header>

      <!-- Imagen y extracto -->
      <article class="blog-post__subheader">
        <section class="image">
          <picture class="image" @click="openLightbox = true">
              <source media="(min-width: 1536px)" :srcset="post?.thumbnail_urls?.max">
              <source media="(min-width: 1280px)" :srcset="post?.thumbnail_urls?.lg">
              <img
                :src="post?.thumbnail_urls?.lg" 
                :alt="post?.title"
                class="post-thumbnail"
                loading="lazy"
              >
          </picture>

          <div v-if="openLightbox" class="lightbox" @click.self="openLightbox = false">
            <button class="close-btn" @click="openLightbox = false">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
            </button>
            <img
              :src="post?.thumbnail_urls?.max" 
              :alt="post?.title"
              class="lightbox-image"
            >
          </div>
        </section>
      </article>

      <!-- Contenido principal -->
      <section class="blog-post__body | container flow" data-type="blog-post">
        <div v-html="post?.body"></div>
      </section>

    </section>

  </SiteLayout>
</template>

<style scoped>

</style>