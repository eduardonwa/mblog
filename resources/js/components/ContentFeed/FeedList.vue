<script setup lang="ts">
import type { BaseItem } from '@/types';

defineProps<{
  items: BaseItem[];
  heading?: string;
  poweredBy?: string;
}>();
</script>

<template>
  <section>
    <ul class="content-feed__scroll-container">
      <li class="content" v-for="it in items" :key="it.id">
        <a :href="it.url" target="_blank" rel="noopener noreferrer" class="content__info">
          
          <div class="content__header">
            <div class="labels">
              <p class="label">{{ it.sourceLabel }}</p>
            </div>
            <img class="yt-thumb" v-if="it.thumb" :src="it.thumb" :alt="it.title" />
          </div>

          <div class="content__body">
            <p class="padding-block-2">{{ it.title }}</p>
            <p class="date" v-if="it.date">{{ it.date }}</p>
          </div>

        </a>
      </li>
    </ul>
  </section>
</template>
