<script setup>
    import { Link } from '@inertiajs/vue3';
    import SiteLayout from '@/layouts/SiteLayout.vue';

    const props = defineProps({
        posts: Object,
        author: Object
    });
</script>

<template>
    <SiteLayout>
        <!-- Título del autor -->
        <h1>Posts de {{ author.name }}</h1>

        <!-- Lista de posts -->
        <div v-for="post in posts.data" :key="post.id">
            <Link :href="route('post.show', post.slug)">
                {{ post.title }}
            </Link>
            <p>{{ post.extract }}</p>
        </div>

        <!-- Paginación (si hay más de una página) -->
        <div v-if="posts.links.length > 3">
            <div v-for="link in posts.links" :key="link.label">
                <Link 
                    v-if="link.url"
                    :href="link.url"
                    :class="{ 'bg-blue-500 text-white': link.active }"
                    v-html="link.label"
                />
            </div>
        </div>
    </SiteLayout>
</template>