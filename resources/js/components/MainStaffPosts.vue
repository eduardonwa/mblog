<script setup lang="ts">
    import type { Post } from '@/types';
    import StaffPost from './StaffPosts/StaffPost.vue';

    interface MainStaffPostsProps {
        staffPosts: Post[];
    }
    defineProps<MainStaffPostsProps>();
</script>

<template>
    <section
        v-if="staffPosts?.length"
        class="secondary-posts"
        aria-label="Featured posts"
    >
        <article
            v-for="(post, index) in staffPosts?.slice(0, 4)"
            :key="post.id"
        >
            <StaffPost
                :post="post"
                :index="index"
            />
            <hr class="hr-secondary">
        </article>
    </section>
</template>