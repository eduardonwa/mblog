<script setup>
    import { Link } from '@inertiajs/vue3';
    import Layout from '@/layouts/SiteLayout.vue';

    const props = defineProps({
        posts: Object,
        author: Object
    });
</script>

<template>
    <SiteLayout>
        <!-- Título del autor -->
        <h1 class="text-2xl mb-6">Posts de {{ author.name }}</h1>

        <!-- Lista de posts -->
        <div v-for="post in posts.data" :key="post.id" 
             class="mb-6 p-4 bg-white shadow rounded-lg">
            <Link :href="route('post.show', post.slug)" 
                  class="text-blue-600 hover:underline">
                {{ post.title }}
            </Link>
            <p class="mt-2 text-gray-700">{{ post.extract }}</p>
        </div>

        <!-- Paginación (si hay más de una página) -->
        <div v-if="posts.links.length > 3" class="mt-8 flex justify-center">
            <div v-for="link in posts.links" :key="link.label">
                <Link 
                    v-if="link.url"
                    :href="link.url"
                    class="px-4 py-2 mx-1 rounded"
                    :class="{ 'bg-blue-500 text-white': link.active }"
                    v-html="link.label"
                />
            </div>
        </div>
    </SiteLayout>
</template>