<script setup lang="ts">
import { inject, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import LikeButton from '@/components/LikeButton.vue';
import Button from '../button/Button.vue';
import type { BlogPostProps } from './index';
import ToggleIcon from '../icons/ToggleIcon.vue';

// Obtener datos del post mediante inyección
const post = inject('postData') as BlogPostProps['post'];
const layoutState = inject('layoutState') as {
  state: { value: 'expanded' | 'collapsed' };
  toggle: () => void;
};

// Estado local para likes
const localPost = ref({ ...post });
</script>

<template>
  <section class="blog-post__header" grid-area="header" >
    <div class="post-header-sticky-wrapper">
      
      <header class="post-header container" data-type="blog-post">
        <!-- Botón para colapsar/expandir -->
        <ToggleIcon
          size="34"
          class="toggle-media-button"
          @click="layoutState.toggle()"
        />
        <!-- Título y metadatos -->
        <article class="post-header__meta-group">
          <h1 class="post-title">{{ post?.title }}</h1>
  
          <div class="meta-primary">
            <div class="no-decor">
              by
              <Link class="author" :href="route('author.posts', { user: post.user.slug })">
                {{ post?.user?.name || 'Rattlehead' }}
              </Link>
            </div>
  
            <p class="date">{{ post?.smart_date }}</p>
            
            <Link class="category" :href="route('category.index', {slug: post?.category.slug})">
              {{ post?.category.name }}
            </Link>
          </div>
        </article>
      </header>
      <div class="post-interactions-wrapper">
        <LikeButton
          :post="localPost"
          class="stick-this"
          @update:post="updatedPost => localPost = updatedPost"
        />
      </div>
      
    </div>
  </section>
</template>