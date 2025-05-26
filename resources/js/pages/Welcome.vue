<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import AuthorIcon from '@/components/ui/icons/AuthorIcon.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import InfiniteScroll from '@/components/InfiniteScroll.vue';
import type { Post } from '@/types';

interface WelcomePageProps {
    featuredPost: Post[];
    staffPosts: Post[];
    leaderboard: Post[];
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
}

const props = defineProps<WelcomePageProps>();
const { featuredPost, staffPosts, leaderboard, communityFeed } = props;
</script>

<template>
    <SiteLayout>

        <Head title="Welcome" />

        <!-- leaderboard y posts -->
        <section class="main-editorial-grid">
            <!-- post principal -->
            <Link
                :href="route('post.show', { slug: featuredPost[0]?.slug })"
                class="main-post | no-decor"
                aria-label="Main Post"
            >
            <div class="main-post__info">
                <div class="main-post__info__header">
                    <h2 class="fs-700">
                        {{ featuredPost?.[0].title }}
                    </h2>
                    <p class="fs-500">
                        {{ featuredPost?.[0].extract }}
                    </p>
                </div>
                <!-- uphails, comentarios, autor -->
                <div class="main-post__info__details">
                    <div>
                        <UphailIcon
                            size="24px"
                            fillColor="#D3D7EA"
                            style="margin-right: .4rem;"
                        />
                        <span class="uphail-count">{{ featuredPost?.[0].likes_count }}</span>
                    </div>

                    <div class="padding-inline-4">
                        <CommentIcon
                            class="main-post-comment-icon"
                            size="24px"
                            style="margin-right: .4rem;"
                        />
                        <span class="comment-count">2</span>
                    </div>

                    <div>
                        <AuthorIcon
                            class="main-post-author-icon"
                            color="#d9d9de"
                            hoverColor="#1e90ff"
                            size="24px"
                            style="margin-right: .4rem;"
                        />
                        <span>{{ featuredPost?.[0].user?.name || 'Rattlehead' }}</span>
                    </div>
                </div>
            </div>

            <div class="main-post__image">
                <picture>
                    <source
                        media="(min-width: 768px)"
                        :srcset="featuredPost?.[0]?.thumbnail_urls?.max"
                    >
                    <source
                        media="(max-width: 767px)"
                        :srcset="featuredPost?.[0]?.thumbnail_urls?.lg"
                    >
                    <img
                        :src="featuredPost?.[0]?.thumbnail_urls?.lg"
                        :alt="featuredPost?.[0]?.title"
                        class="post-thumbnail"
                    >
                </picture>
            </div>
            </Link>

            <!-- leaderboard -->
            <section
                v-if="leaderboard?.length"
                class="leaderboard | flow"
                aria-label="Leaderboard"
            >
                <h2 class="leaderboard__header | uppercase fw-semibold clr-secondary-200">Leaderboard</h2>

                <div
                    v-for="post in leaderboard"
                    :key="post.id"
                >
                    <Link
                        class="leaderboard__container | no-decor"
                        :href="route('post.show', { slug: featuredPost[0]?.slug })"
                    >
                    <div class="leaderboard__container__left-column">
                        <div class="leaderboard__container__left-column__user">
                            <span class="clr-neutral-200">#1</span>
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
                            <p>{{ post.user?.name }}</p>
                        </div>
                        <div class="leaderboard__container__left-column__post-title">
                            <h2>{{ post.title }}</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">{{ post.likes_count }}</span>
                        <UphailIcon
                            size="24px"
                            fillColor="#A0DD05"
                            style="margin-right: .4rem;"
                        />
                    </div>
                    </Link>
                </div>
            </section>

            <hr class="hr-leaderboard | margin-inline-4">

            <!-- posts secundarios -->
            <section
                v-if="staffPosts?.length"
                class="secondary-posts"
                aria-label="Featured posts"
            >
                <article
                    v-for="(post, index) in staffPosts?.slice(0, 4)"
                    :key="post.id"
                >
                    <Link
                        :href="route('post.show', {slug: post.slug })"
                        class="secondary-posts__post-card"
                    >
                    <div class="secondary-posts__post-card__image-wrapper">
                        <picture>
                            <source
                                media="(min-width: 768px)"
                                :srcset="post?.thumbnail_urls?.max"
                            >
                            <source
                                media="(max-width: 767px)"
                                :srcset="post?.thumbnail_urls?.md"
                            >
                            <img
                                :src="post?.thumbnail_urls?.lg"
                                :alt="post?.title"
                                class="post-thumbnail"
                            >
                        </picture>
                    </div>

                    <div class="secondary-posts__post-card__info">
                        <h2>{{ post.title }}</h2>
                        <p>{{ post.extract }}</p>
                    </div>

                    </Link>
                    <hr class="hr-secondary">

                </article>
            </section>
        </section>

        <!-- community -->
        <div
            class="community-grid"
            v-if="communityFeed?.data?.length"
        >
            <section class="community-layout">
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
                                <Link
                                    :href="route('post.show', { slug:post.slug })"
                                    class="feed-post | no-decor"
                                >
                                <!-- info del autor y fecha -->
                                <div
                                    class="feed-post__top"
                                    role="group"
                                    aria-label="More about this author"
                                >
                                    <Link
                                        class="no-decor"
                                        :href="route('author.posts', { name: post?.user?.name })"
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
        
                                    <!-- fecha -->
                                    <span class="feed-post__top__date">{{ post?.short_date }}</span>
                                </div>
                                <!-- info del post -->
                                <div class="feed-post__middle">
                                    <h2 class="post-title">{{ post.title }}</h2>
                                    <p class="post-excerpt">{{ post.excerpt }}</p>
                                </div>
        
                                <hr class="hr-straight-mobile">
        
                                <!-- interacciones -->
                                <div class="feed-post__bottom">
                                    <div class="feed-uphail-count">
                                        <UphailIcon
                                            size="22px"
                                            fillColor="#d9d9de"
                                            style="margin-right: .4rem;"
                                        />
                                        <span class="clr-primary-300">{{ post.likes_count }}</span>
                                    </div>
        
                                    <div class="feed-comment-count">
                                        <CommentIcon
                                            size="22px"
                                            style="margin-right: .4rem;"
                                        />
                                        <span class="comment-count">2</span>
                                    </div>
                                </div>
                                </Link>
        
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
            </section>
        </div>

    </SiteLayout>
</template>