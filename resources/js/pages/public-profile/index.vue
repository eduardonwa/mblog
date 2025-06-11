<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommunityPostCard from '@/components/CommunityFeed/CommunityPostCard.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import type { Post, User } from '@/types';

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
        <section class="categories-wrapper | container">
            <!-- autor -->
            <div class="categories-wrapper__header">
                <h1>Posts by {{ author?.name }}</h1>                
            </div>

            <!-- lista de posts -->
            <!-- <div v-if="posts?.data.length"> -->
                <!-- <div v-for="post in posts" :key="post.id"> -->
                    <InfiniteScroll
                        :endpoint="`/users/${author.id}?json=true`"
                        data-key="data"
                        :initial-items="initialPosts.data"
                        :initial-next-page="initialPosts.next_page_url ?? undefined"
                    >
                        <template #default="{items}">
                            <div v-for="post in items as Post[]" :key="post.id">
                                <CommunityPostCard :post="post" class="categories | no-decor">
                                    <template #header="{ post }">
                                        <div class="categories__header">
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
                                        <div class="categories__middle">
                                            <h2>{{ post.title }}</h2>
                                            <p>{{ post.extract }}</p>
                                        </div>
                                    </template>
        
                                    <template #footer="{ post }">
                                        <div class="categories__footer">
                                            <span class="date">{{ post.smart_date }}</span>
                                            <Link
                                                class="no-decor"
                                                v-if="post.category"
                                                :href="route('category.index', { slug: post.category.slug })"
                                            >
                                                <span>{{ post.category?.name }}</span>
                                            </Link>
                                        </div>
                                    </template>
                                </CommunityPostCard>
                            </div>
                        </template>
                    </InfiniteScroll>
                    <hr class="hr-straight-medium">
                <!-- </div> -->
            <!-- </div> -->

            <!-- <div v-else>
                Nothing to see here... yet!
            </div> -->
            
            <!-- Paginación -->
            <!-- <div class="pagination-wrapper" v-if="posts?.links">
                <template
                    v-for="(link, index) in posts?.links"
                    :key="index"
                >
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        :class="{ '': link.active }"
                        class="button"
                        data-type="pagination"
                        :innerHTML="link.label"
                    />
                    <span
                        v-else
                        :innerHTML="link.label"
                    />
                </template>
            </div> -->
        </section>
    </SiteLayout>
</template>