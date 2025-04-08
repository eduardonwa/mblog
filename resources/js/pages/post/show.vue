<script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';
  import LikeButton from '@/components/LikeButton.vue';

  const props = defineProps({
    post: Object,
    meta: Object,
  });
</script>

<template>
  <SiteLayout>
    <Head>
      <title>{{ meta.title }}</title>
      <meta name="description" :content="meta.description">
      <meta name="author" :content="meta.author">
    </Head>

    <section>
      <header>
        <!-- Categoría -->
        <Link 
          :href="route('category.show', post.category.slug)" 
        >
          {{ post.category.name }}
        </Link>

        <!-- Featured -->
        <h3 v-if="post.featured">
          FEATURED
        </h3>

        <!-- Likes (componente reutilizable) -->
        <LikeButton 
            :post="post" 
            @update:post="updatedPost => post = updatedPost" 
        />

        <!-- Tags -->
        <div v-if="post.tags?.length">
          <Link 
            v-for="tag in post.tags" 
            :key="tag.id"
            :href="route('tag.show', tag.slug.en)"
          >
            #{{ tag.name.en }}
          </Link>
        </div>

        <!-- Título y autor -->
        <h1>{{ post.title }}</h1>
        
        <Link :href="route('author.posts', post.author.name)">
          <p>by {{ post.author?.name || 'Rattlehead' }}</p>
        </Link>

        <p>{{ post.smart_date }}</p>
      </header>

      <!-- Imagen y extracto -->
      <article>
        <img 
          :src="post.thumbnail_url"
          :alt="post.title"
        >
        <span>
          {{ post.language }}
        </span>
        <p>{{ post.extract }}</p>
      </article>

      <!-- Contenido principal -->
      <section>
        <div v-html="post.body"></div>
      </section>
    </section>
  </SiteLayout>
</template>