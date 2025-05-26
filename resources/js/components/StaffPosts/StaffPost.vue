<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Post } from '@/types';

defineProps<{
  post: Post;
}>();
</script>

<template>
    <Link
        :href="route('post.show', {slug: post.slug })"
        class="secondary-posts__post-card"
    >
        <div class="secondary-posts__post-card__image-wrapper">
            <picture>
                <source
                    media="(min-width: 768px)"
                    :srcset="post?.thumbnail_urls?.max"
                >
                <source
                    media="(max-width: 767px)"
                    :srcset="post?.thumbnail_urls?.md"
                >
                <img
                    :src="post?.thumbnail_urls?.lg"
                    :alt="post?.title"
                    class="post-thumbnail"
                >
            </picture>
        </div>

        <div class="secondary-posts__post-card__info">
            <h2>{{ post.title }}</h2>
            <p>{{ post.extract }}</p>
        </div>
    </Link>
</template>