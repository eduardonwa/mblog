<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { Channel } from '@/types'

defineProps<{
  channel: Channel
}>()
</script>

<template>
    <Link class="card | box-shadow-4 uppercase no-decor" :href="channel.url">
        <picture>
            <source :srcset="channel.sticker_url.sm" media="(max-width: 640px)">
            <source :srcset="channel.sticker_url.lg" media="(min-width: 768px)">
            <div class="card__sticker"><img :src="channel.sticker_url?.lg" :alt="channel.name" loading="lazy"></div>
        </picture>
        <p class="card__name">{{ channel.name }}</p>
    </Link>
</template>
