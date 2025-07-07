<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import ChannelHeader from '@/components/ChannelHeader.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import { ref } from 'vue';
import { Channel, Post } from '@/types';
import ArticleCardHorizontal from '@/components/ArticleCardHorizontal.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';

interface ChannelPosts {
    data: Post[];
    next_page_url: string | null;
}

const { posts: initialPosts, channel } = defineProps<{
    posts: ChannelPosts;
    channel: Channel;
}>();
</script>

<template>
    <SiteLayout>
        <section class="container">
            <header>
                <ChannelHeader :channel="channel" />
            </header>

            <article class="channel-posts">
                <InfiniteScroll
                    :endpoint="`/channels/${channel.slug}?json=true`"
                    data-key="data"
                    :initial-items="initialPosts.data"
                    :initial-next-page="initialPosts.next_page_url ?? undefined"
                >
                    <template #default="{ items }">
                        <div v-for="post in items as Post[]" :key="`post-${post.id}-${post.updated_at}`">
                            <ArticleCardHorizontal :post="post" class="horiz-card | no-decor">
                                <template #header="{ post }">
                                    <div class="horiz-card__header">
                                        <div class="uphail-icon">
                                            <UphailIcon
                                                color="#D3D7EA"
                                                hoverColor="#F4FFC7"
                                                viewBox="0 4 25 26"
                                                size="28px"
                                            ></UphailIcon>
                                            <span>{{ post.likes_count }}</span>
                                        </div>
    
                                        <div class="comment-icon">
                                            <CommentIcon
                                                class="comment-icon"
                                                color="#D3D7EA"
                                                hoverColor="#F4FFC7"
                                                viewBox="0 0 29 29"
                                            ></CommentIcon>
                                            <span>{{ post.comments_count }}</span>
                                        </div>
                                    </div>
                                </template>
                                
                                <template #middle="{ post }">
                                    <div class="horiz-card__middle">
                                        <h2>{{ post.title }}</h2>
                                        <p>{{ post.excerpt }}</p>
                                        <p>by <span>{{ post.user?.slug }}</span></p>
                                    </div>
                                </template>

                                <template #footer="{ post }">
                                    <div class="horiz-card__footer">
                                        <p>{{ post.smart_date }}</p>
                                    </div>
                                </template>
                            </ArticleCardHorizontal>
                        </div>
                    </template>
                </InfiniteScroll>
            </article>
        </section>
    </SiteLayout>
</template>