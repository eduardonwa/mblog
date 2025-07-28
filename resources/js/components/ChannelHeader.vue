<script setup lang="ts">
import type { Channel } from '@/types'
import { Link } from '@inertiajs/vue3';

defineProps<{
  channel: Channel
}>();
</script>

<template>
  <section class="container">

    <article class="channel-header">
      <picture>
          <source :srcset="channel.sticker_url.sm" media="(max-width: 640px)">
          <source :srcset="channel.sticker_url.lg" media="(min-width: 768px)">

          <div class="sticker-wrapper">
            <img :src="channel.sticker_url?.lg" :alt="channel.name" loading="lazy">
          </div>
      </picture>
      
      <div class="channel-header__info">
        <div class="header">
          <Link class="no-decor clr-primary-100" :href="route('channel.index')">
            Channels
          </Link>
          / <p class="clr-accent-300 fw-bold">{{ channel.name }}</p>
        </div>
        <p class="description">{{ channel.description }}</p>
      </div>

    </article>

  </section>
</template>