<script setup lang="ts">
import type { Post } from '@/types';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import ArticleCardHorizontal from '../ArticleCardHorizontal.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import { Link } from '@inertiajs/vue3';

interface MainCommunityFeedProps {
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
}
defineProps<MainCommunityFeedProps>();
</script>

<template>
    <div class="community-layout">
        <h2 class="community-layout__header | uppercase clr-secondary-200 fw-semibold">community</h2>

        <main class="community-layout__post-wrapper">
            <InfiniteScroll
                endpoint="/?json=true"
                dataKey="data"
                :initialItems="communityFeed.data"
                :initialNextPage="communityFeed.next_page_url ?? undefined"
            >
                <template #default="{ items }">
                    <article v-for="post in items as Post[]" :key="post.id">
                        <ArticleCardHorizontal :post="post" class="feed-post | no-decor">
                            <template #header="{post}">
                                <div class="feed-post__top">
                                    <Link
                                        class="no-decor"
                                        :href="post.user ? route('author.posts', { user: post.user }) : '/'"
                                        aria-label="More about this author"
                                    >
                                        <Avatar
                                            size="sm"
                                            shape="circle"
                                            :src="post.user?.avatar_url"
                                            :alt="post.user?.username"
                                        />
                                        <span class="feed-post-author">{{ post.user?.username || 'Rattlehead' }}</span>
                                    </Link>
                                    <span class="feed-post__top__date">{{ post?.short_date }}</span>
                                    <p class="clr-primary-300 fs-300 padding-inline-start-1">
                                        &bull;
                                        <span class="padding-inline-start-1">{{ post.channel?.name }}</span>
                                    </p>
                                </div>
                                <div>hotness score:{{ post.hotness_score }}</div>
                            </template>
            
                            <template #middle="{post}">
                                <div class="feed-post__middle">
                                    <h2 class="post-title">{{ post.title }}</h2>
                                    <p class="post-excerpt">{{ post.excerpt }}</p>
                                </div>
                            </template>
            
                            <template #divider>
                                <hr class="hr-straight-mobile">
                            </template>
            
                            <template #footer="{post}">
                                <div class="feed-post__bottom">
                                    <div class="feed-uphail-count">
                                        <UphailIcon
                                            size="22px"
                                            style="margin-right: .4rem;"
                                        />
                                        <span class="clr-primary-300">{{ post.likes_count }}</span>
                                    </div>
                
                                    <div class="feed-comment-count">
                                        <CommentIcon
                                            size="22px"
                                            style="margin-right: .4rem;"
                                        />
                                        <span class="comment-count">{{ post.comments_count }}</span>
                                    </div>
                                </div>
                            </template>
                        </ArticleCardHorizontal>
                        <hr class="hr-straight-large">
                    </article>
                </template>
            </InfiniteScroll>
        </main>
    </div>
</template>