<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Post } from '@/types';
import { computed } from 'vue';

const { post } = defineProps<{
  post: Post;
  variant?: 'default' | 'compact';
}>();

const postRoute = computed(() => {
    // Posts de members (usando channel)
    if (post.channel?.slug) {
        return route('channel.post.show', {
            channel: post.channel.slug,
            post: post.slug
        });
    }
    // Posts editoriales (usando category)
    else if (post.category?.slug) {
        return route('category.index', { 
            slug: post.category.slug 
        });
    }
    // Fallback para posts sin channel/category (opcional)
    return route('post.show', { post: post.slug });
});
</script>

<template>
    <Link :href="postRoute">
        <!-- header -->
        <div
            v-if="$slots.header"
            role="group"
        >
            <slot name="header" :post="post"></slot>
        </div>

        <!-- middle -->
        <div v-if="$slots.middle">
            <slot name="middle" :post="post"></slot>
        </div>

        <!-- divider -->
        <div v-if="$slots.divider">
            <slot name="divider"></slot>
        </div>

        <!-- footer -->
        <div v-if="$slots.footer">
            <slot name="footer" :post="post"></slot>
        </div>
    </Link>
</template>