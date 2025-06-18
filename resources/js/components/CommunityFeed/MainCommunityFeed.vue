<script setup lang="ts">
import type { Post } from '@/types';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import CommunityPostCard from '../ArticleCardHorizontal.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import { Link } from '@inertiajs/vue3';

interface MainCommunityFeedProps {
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
}

const props = defineProps<MainCommunityFeedProps>();
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
                        <CommunityPostCard :post="post" class="feed-post | no-decor">
                            <template #header="{post}">
                                <div class="feed-post__top">
                                    <Link
                                        class="no-decor"
                                        :href="route('author.posts', { user: post?.user?.slug })"
                                        aria-label="More about this author"
                                    >
                                        <Avatar
                                            size="sm"
                                            shape="circle"
                                        >
                                            <img
                                                src="images/avatar/thrash.png"
                                                alt="avatar de warpig"
                                                class="avatar__image"
                                            >
                                        </Avatar>
                                        <span class="feed-post-author">{{ post.user?.name }}</span>
                                    </Link>
                                    <span class="feed-post__top__date">{{ post?.short_date }}</span>
                                </div>
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
                        </CommunityPostCard>
                        <hr class="hr-straight-large">
                    </article>
                </template>
            </InfiniteScroll>
        </main>
        
        <div class="community-layout__groups-wrapper">
            <aside class="community-layout__groups-wrapper__following">
                <h2 class="following-header | uppercase clr-secondary-200 fw-semibold">
                    following
                </h2>
    
                <div class="group-card">
                    <h2 class="group-card__group-name | uppercase fs-200 clr-primary-300">nombre del grupo</h2>
                    <a
                        href="#"
                        class="group-card__post-title | no-decor"
                    >titulo del post</a>
                    <div class="group-card__interactions">
                        <div class="group-uphail">
                            <UphailIcon
                                size="20px"
                                fillColor="#d9d9de"
                                style="margin-right: .4rem;"
                            />
                            <span class="clr-primary-300">1</span>
                        </div>
    
                        <div class="group-comment">
                            <CommentIcon
                                size="20px"
                                style="margin-right: .4rem;"
                            />
                            <span class="comment-count">2</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>