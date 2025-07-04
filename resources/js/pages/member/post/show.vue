<script setup lang="ts">
import { Post } from '@/types';
import SiteLayout from '@/layouts/SiteLayout.vue';

const props = defineProps<{
    post: Post;
}>();
</script>

<template>
    <SiteLayout>
        <section class="container margin-block-start-12" style="border: 1px solid;" data-type="blog-post" data-align="start">
            <header>
                <h2>{{ post.title }}</h2>
                <p>{{ post.user?.slug || 'Rattlehead' }}</p>
                <span>{{ post.smart_date }}</span>
                <p>{{ post.channel.name }}</p>
            </header>

            <!-- body -->
            <article>
                <p>{{ post.body }}</p>
            </article>
        </section>
    </SiteLayout>
</template>