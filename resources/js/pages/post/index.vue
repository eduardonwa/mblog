<script setup>
  import Layout from '@/layouts/SiteLayout.vue';

  const props = defineProps({
    posts: Object,
  });
</script>

<template>
    <SiteLayout>

        <div v-if="posts.data.length">
            <div v-for="post in posts.data" :key="post.id">
                <h1>{{ posts.title }}</h1>
                <p>author: {{ post.author.name }}</p>
                <p>category: {{ post.category.name }}</p>
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