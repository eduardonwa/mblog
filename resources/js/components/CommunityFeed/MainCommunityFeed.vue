<script setup lang="ts">
import { defineProps } from 'vue';
import type { Post } from '@/types';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import CommunityPostCard from './CommunityPostCard.vue';

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
                        <CommunityPostCard
                            :post="post"
                        />

                        <hr class="hr-straight-desktop">
                    </article>
                </template>
            </InfiniteScroll>
        </main>
        
        <aside class="community-layout__groups">
            <h2 class="community-layout__groups__header | uppercase clr-secondary-200 fw-semibold">
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
</template>