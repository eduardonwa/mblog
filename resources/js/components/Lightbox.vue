<script setup lang="ts">
import { ref } from 'vue';
import type { Post } from '@/types/index';

const openLightbox = ref(false);

const { post } = defineProps<{
  post: Post;
}>();
</script>

<template>
    <picture
        class="image"
        @click="openLightbox = true"
        >
        <source
            media="(min-width: 1536px)"
            :srcset="post?.thumbnail_urls?.max"
        >
        <source
            media="(min-width: 1280px)"
            :srcset="post?.thumbnail_urls?.lg"
        >
        <img
            :src="post?.thumbnail_urls?.lg"
            :alt="post?.title"
            class="post-thumbnail"
            loading="lazy"
        >
        </picture>

        <div
        v-if="openLightbox"
        class="lightbox"
        @click.self="openLightbox = false"
        >
        <button
            class="close-btn"
            @click="openLightbox = false"
        >
            <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6"
            >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
            />
            </svg>
        </button>
        <img
            :src="post?.thumbnail_urls?.max"
            :alt="post?.title"
            class="lightbox-image"
        >
        </div>
</template>