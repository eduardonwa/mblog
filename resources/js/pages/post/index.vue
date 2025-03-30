<script setup>
  import Layout from '@/layouts/SiteLayout.vue';
  import { Link } from '@inertiajs/vue3';

  const props = defineProps({
    posts: Object,
  });
</script>

<template>
    <SiteLayout>
        <div v-if="posts.data.length">
            <div v-for="post in posts.data" :key="post.id">
                <Link :href="route('post.show', post.slug)">
                    <h1>{{ post.title }}</h1>
                </Link>

                <p>author: {{ post.author.name }}</p>

                <Link :href="route('category.show', post.category.slug)">
                    <p>category: {{ post.category.name }}</p>
                </Link>
            </div>
        </div>
        
        <div v-else>
            no posts =(
        </div>

        <div v-if="posts.links" class="mt-4">
            <a
                v-for="link in posts.links"
                :key="link.label"
                :href="link.url"
                class="px-3 py-1 mx-1 border rounded"
                :class="{'bg-blue-500 text-white': link.active}"
            >
                {{ link.label }}
            </a>
        </div>
    </SiteLayout>
</template>