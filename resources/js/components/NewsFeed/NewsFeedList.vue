<script setup lang="ts">
import type { NewsItem } from '@/types';

defineProps<{ news: NewsItem[] }>();
</script>

<template>
  <section class="new-albums">
    <div class="new-albums-header">
      <h2 class="clr-accent-200 fw-semibold uppercase">news</h2>
      <p class="powered-string">by <span class="no-decor uppercase">the metal machine</span>
      </p>
    </div>

    <ul class="new-albums__scroll-container">
      <li class="album" v-for="item in news" :key="item.id">
        <a :href="item.url" target="_blank" rel="noopener noreferrer" class="album__info">
          <div class="album__header">
            <div class="genres">
              <p class="genre">{{ item.sourceLabel }}</p>
              <hr>
            </div>
          </div>
          <div class="album__content">
            <p class="title">{{ item.title }}</p>
            <p class="date">{{ item.date }}</p>
          </div>
        </a>
      </li>
    </ul>
  </section>
</template>
