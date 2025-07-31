<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import ArticleCardHorizontal from '@/components/ArticleCardHorizontal.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import type { Post, User } from '@/types';
import Avatar from '@/components/ui/avatar/Avatar.vue';

interface PaginatedPosts {
    data: Post[];
    next_page_url: string | null;
}

const { posts: initialPosts, author } = defineProps<{
    posts: PaginatedPosts;
    author: User;
}>();
</script>

<template>
    <SiteLayout>
        <section class="horiz-card-wrapper | container">
            <!-- autor -->
            <article class="horiz-card-wrapper__header">
                <div class="user-profile">
                    <div class="header">
                        <h2 class="username">{{ author?.username }}</h2>
                        <Avatar
                            size="lg"
                            :src="author.avatar_url"
                            :alt="author.username"
                        />
                    </div>

                    <div class="content">
                        <p class="bio">{{ author?.bio }}</p>
                        <div v-if="author?.social_links">
                            <div v-for="(url, key) in author.social_links" :key="key">
                                <span v-if="url">
                                    <a class="link" :href="url" target="_blank" rel="noopener noreferrer">
                                        {{ url.replace(/^https?:\/\//, '') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <InfiniteScroll
                :endpoint="`/members/${author.username}/posts?json=true`"
                data-key="data"
                :initial-items="initialPosts.data"
                :initial-next-page="initialPosts.next_page_url ?? undefined"
            >
                <template #default="{items}">
                    <div v-for="post in items as Post[]" :key="post.id">
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
                                    <p>{{ post.extract }}</p>
                                </div>
                            </template>

                            <template #footer="{ post }">
                                <div class="horiz-card__footer">
                                    <span class="date | padding-inline-end-2">{{ post.smart_date }}</span>
                                </div>
                            </template>
                        </ArticleCardHorizontal>
                    </div>
                </template>
            </InfiniteScroll>
        </section>
    </SiteLayout>
</template>