<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import ChannelHeader from '@/components/ChannelHeader.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Channel, Post } from '@/types';

interface ChannelPosts {
    data: Post[];
    next_page_url: string | null;
}

// Props con tipos fuertes
const props = defineProps<{
    posts: ChannelPosts;
    channel: Channel;
}>();

// Estado reactivo con tipo explícito
const displayedPosts = ref<Post[]>(props.posts.data);

// Manejador de carga con tipos definidos
const handleLoad = (newItems: Post[]) => {
  // Filtra duplicados antes de agregar
  displayedPosts.value = [
    ...displayedPosts.value,
    ...newItems.filter(
      newPost => !displayedPosts.value.some(post => post.id === newPost.id)
    )
  ];
};
</script>

<template>
    <SiteLayout>
        <section class="container">
            <header>
                <ChannelHeader :channel="channel" />
            </header>

            <article>
                <InfiniteScroll
                    :endpoint="`/channels/${channel.slug}?json=true`"
                    @load="handleLoad"
                >
                    <template #default>
                        <div 
                            v-for="post in displayedPosts" 
                            :key="`post-${post.id}-${post.updated_at}`"
                            class="post-item"
                        >
                            <Link :href="`/channel/${channel.slug}/${post.slug}`">
                                <h3>{{ post.title }}</h3>
                                <h3>{{ post.user?.slug }}</h3>
                                <p>{{ post.likes_count }} uphails</p>
                                <p>{{ post.comments_count }} comments</p>
                            </Link>
                        </div>
                    </template>
                </InfiniteScroll>
            </article>
        </section>
    </SiteLayout>
</template>