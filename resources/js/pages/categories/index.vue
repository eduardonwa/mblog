<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import ArticleCardHorizontal from '@/components/ArticleCardHorizontal.vue';
import AuthorIcon from '@/components/ui/icons/AuthorIcon.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import type { Post, Category } from '@/types';

interface categoryPosts {
    data: Post[];
    next_page_url: string | null;
}

const { posts: initialPosts, category } = defineProps<{
    posts: categoryPosts;
    category: Category;
}>();
</script>

<template>
    <SiteLayout>
        <section class="horiz-card-wrapper | container">
            <!-- Título de la categoría -->
            <div class="horiz-card-wrapper__header">
                <h2 class="clr-neutral-100">{{ category?.name }}</h2>
                <p class="fs-400 clr-primary-300">{{ category?.description }}</p>
            </div>

            <!-- Lista de posts -->
            <InfiniteScroll
                :endpoint="`/category/${category.slug}?json=true`"
                data-key="data"
                :initial-items="initialPosts.data"
                :initial-next-page="initialPosts.next_page_url ?? undefined"
            >
                <template #default="{ items }">
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
                                    <h2>
                                        {{ post.title }}
                                    </h2>
                                    <p>
                                        {{ post.excerpt }}
                                    </p>
                                </div>
                            </template>
    
                            <template #footer="{ post }">
                                <div class="horiz-card__footer">
                                    <AuthorIcon
                                        color="#D3D7EA"
                                        size="24px"
                                        style="margin-right: .4rem;"
                                    />
    
                                    <Link
                                        :href="route('author.posts', { user: post?.user?.slug })"
                                        class="no-decor"
                                        aria-label="More about this author"
                                    >{{ post.user?.slug }}</Link>
                                    <span class="date">{{ post.smart_date }}</span>
                                </div>
                            </template>
                        </ArticleCardHorizontal>
                    </div>
                </template>
            </InfiniteScroll>
            <hr class="hr-straight-medium">
        </section>
    </SiteLayout>
</template>