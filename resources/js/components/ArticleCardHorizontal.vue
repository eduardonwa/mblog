<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Post } from '@/types';

const { post } = defineProps<{
  post: Post;
  variant?: 'default' | 'compact';
}>();
</script>

<template>
    <Link :href="route('post.show', { post: post.slug })">
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