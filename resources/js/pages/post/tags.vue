<script setup lang="ts">
    import SiteLayout from '@/layouts/SiteLayout.vue';
    import { Link } from '@inertiajs/vue3';

    const { posts } = defineProps({
        posts: Object,
    });
</script>

<template>
    <SiteLayout>
        <!-- los tags de un post -->
        <div v-if="posts?.data.length">
            <div v-for="post in posts.data" :key="post.id">
                <h2>
                    <Link :href="route('post.show', post.slug)">
                        {{ post.title }}
                    </Link>
                </h2>
                
                <!-- List tags for each post -->
                <div v-if="post.tags.length">
                    <span v-for="tag in post.tags" :key="tag.id">
                        {{ tag.name.en }}
                    </span>
                </div>
            </div>
        </div>

        <div v-else>
            No posts found with this tag.
        </div>

        <!-- pagination -->
         <div v-if="posts?.links">
            <template v-for="(link, index) in posts.links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="{'bg-blue-500 text-white': link.active}"
                    :innerHTML="link.label"
                />
                <span v-else :innerHTML="link.label" />
            </template>

         </div>
    </SiteLayout>
</template>