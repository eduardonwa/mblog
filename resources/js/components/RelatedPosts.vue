<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { PostInSeries } from '@/types'

defineProps<{ relatedPosts: PostInSeries[] }>()
</script>

<template>
  <div v-if="relatedPosts.length">
    <h3 class="badge uppercase clr-secondary-900">from this series</h3>
    
    <section class="related-posts">
      <div class="series-item" v-for="p in relatedPosts" :key="p.id">
        <Link class="no-decor" :href="route('post.show', { slug: p.slug })">
          <h2 class="title"> {{ p.title }} </h2>  
          <picture v-if="p.thumbnail_urls">
            <source media="(min-width: 1280px)" :srcset="p.thumbnail_urls.max" />
            <source media="(min-width: 768px)" :srcset="p.thumbnail_urls.lg" />
            <img
                :src="p.thumbnail_urls.sm"
                :srcset="`${p.thumbnail_urls.md} 768w, ${p.thumbnail_urls.lg} 1280w`"
                sizes="(max-width: 767px) 100vw, (max-width: 1279px) 80vw, 1200px"
                :alt="p.title"
                class="preview-img"
            />
          </picture>
        </Link>
      </div>
    </section>
  </div>
</template>