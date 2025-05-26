<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    import SiteLayout from '@/layouts/SiteLayout.vue';

    const { posts, category } = defineProps({
        posts: Object,
        category: Object
    });
</script>

<template>
    <SiteLayout>
        <section class="margin-block-12">
            <!-- Título de la categoría -->
            <h1>Post category for: {{ category?.name }}</h1>
    
            <!-- Lista de posts -->
            <div v-if="posts?.data.length">
                <div v-for="post in posts?.data" :key="post.id">
                    <!-- Título del post -->
                    <h2>
                        <Link :href="route('post.show', post.slug)">
                            {{ post.title }}
                        </Link>
                    </h2>
                    
                    <!-- Mostrar categoría del post (si está cargada) -->
                    <div v-if="post.category" class="">
                        <span>
                            Categoría: {{ post.category.name }}
                        </span>
                    </div>
                </div>
            </div>
            <div v-else>
                nothing to see here... yet!
            </div>
    
            <!-- Paginación -->
            <div v-if="posts?.links">
                <template v-for="(link, index) in posts?.links" :key="index">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        :class="{'bg-blue-500 text-white': link.active}"
                        :innerHTML="link.label"
                    />
                    <span v-else :innerHTML="link.label" />
                </template>
            </div>
        </section>
    </SiteLayout>
</template>