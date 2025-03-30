<script setup>
    import Layout from '@/layouts/SiteLayout.vue';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        posts: Object,
    });
</script>

<template>
    <SiteLayout>
        <!-- los tags de un post -->
        <div v-if="posts.data.length">
            <div v-for="post in posts.data" :key="post.id">
                <h2 class="text-red-500">
                    <Link :href="route('post.show', post.slug)">
                        {{ post.title }}
                    </Link>
                </h2>
                
                <!-- List tags for each post -->
                <div v-if="post.tags.length" class="flex gap-2 mt-2">
                    <span v-for="tag in post.tags" :key="tag.id" class="text-sm bg-gray-500 px-2 py-1 rounded">
                        {{ tag.name.en }}
                    </span>
                </div>
            </div>
        </div>

        <div v-else>
            No posts found with this tag.
        </div>

        <!-- pagination -->
         <div v-if="posts.links" class="mt-8">
            <template v-for="link in posts.links">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="px-3 py-1 mr-2 border rounded"
                    :class="{'bg-blue-500 text-white': link.active}"
                    v-html="link.label"
                />
                <span v-else class="px-3 py-1 mr-2 text-gray-400" v-html="link.label" />
            </template>

         </div>
    </SiteLayout>
</template>