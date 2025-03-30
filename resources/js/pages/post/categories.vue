<script setup>
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';

const props = defineProps({
    posts: Object,
    category: Object
});
</script>

<template>
    <SiteLayout>
        <!-- Título de la categoría -->
        <h1 class="text-2xl font-bold mb-6">Post category for: {{ category.name }}</h1>

        <!-- Lista de posts -->
        <div v-if="posts.data.length">
            <div v-for="post in posts.data" :key="post.id" class="mb-8">
                <!-- Título del post -->
                <h2 class="text-red-500 text-xl">
                    <Link :href="route('post.show', post.slug)">
                        {{ post.title }}
                    </Link>
                </h2>
                
                <!-- Mostrar categoría del post (si está cargada) -->
                <div v-if="post.category" class="">
                    <span class="text-sm bg-gray-100 px-2 py-1 rounded">
                        Categoría: {{ post.category.name }}
                    </span>
                </div>
            </div>
        </div>
        <div v-else>
            No posts
        </div>

        <!-- Paginación -->
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