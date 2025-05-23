<script setup lang="ts">
    import { Head, Link, usePage } from '@inertiajs/vue3';
    import SiteLayout from '@/layouts/SiteLayout.vue';
    import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
    import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
    import AuthorIcon from '@/components/ui/icons/AuthorIcon.vue';
    import Avatar from '@/components/ui/avatar/Avatar.vue';

    const { featuredPosts, leaderboard, recent } = defineProps({
        featuredPosts: Object,
        leaderboard: Object,
        recent: Object,
    });
    
    const user = usePage().props.auth.user;
</script>

<template>
    <SiteLayout>
        <Head title="Welcome" />
        <!-- leaderboard y posts principales -->
        <section class="main-editorial-grid">
            <!-- post principal -->
            <Link
                :href="route('post.show', featuredPosts?.[0].slug)"
                class="main-post | no-decor"
                aria-label="Main Post"
            >
                <div class="main-post__info">
                    <div class="main-post__info__header">
                        <h2 class="fs-700">
                            {{ featuredPosts?.[0].title }}
                        </h2>
                        <p class="fs-500">
                            {{ featuredPosts?.[0].extract }}
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
                            <span class="uphail-count">{{ featuredPosts?.[0].likes_count }}</span>
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
                            <span>{{ featuredPosts?.[0].user?.name || 'Rattlehead' }}</span>
                        </div>
                    </div>
                </div>

                <div class="main-post__image">
                    <picture>
                        <source media="(min-width: 768px)" :srcset="featuredPosts?.[0]?.thumbnail_urls?.max">
                        <source media="(max-width: 767px)" :srcset="featuredPosts?.[0]?.thumbnail_urls?.lg">
                        <img 
                            :src="featuredPosts?.[0]?.thumbnail_urls?.lg" 
                            :alt="featuredPosts?.[0]?.title"
                            class="post-thumbnail"
                        >
                    </picture>
                </div>
            </Link>

            <!-- leaderboard -->
            <section v-if="leaderboard?.length" class="leaderboard | flow" aria-label="Leaderboard">
                <h2 class="uppercase fw-semibold clr-secondary-200">Leaderboard</h2>

                <div v-for="post in leaderboard" :key="post.id">
                    <Link class="leaderboard__container | no-decor" :href="route('post.show', post.slug)">
                        <div class="leaderboard__container__left-column">
                            <div class="leaderboard__container__left-column__user">
                                <span class="clr-neutral-200">#1</span>
                                <Avatar size="sm" shape="circle">
                                    <img
                                        src="images/avatar/thrash.png"
                                        alt="avatar de warpig"
                                        class="avatar__image"
                                    >
                                </Avatar>
                                <p>{{ post.user?.name }}</p>
                            </div>
                            <div class="leaderboard__container__left-column__post">
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

            <!-- posts secundarios -->
            <section
                v-if="recent?.length"
                class="secondary-posts"
                aria-label="Featured posts"
            >
                <article v-for="(post, index) in recent?.slice(0, 4)" :key="post.id">
                    <Link :href="route('post.show', post.slug)" class="secondary-posts__post-card">
                        <div class="secondary-posts__post-card__image-wrapper">
                            <picture>
                                <source media="(min-width: 768px)" :srcset="post?.thumbnail_urls?.max">
                                <source media="(max-width: 767px)" :srcset="post?.thumbnail_urls?.md">
                                <img 
                                    :src="post?.thumbnail_urls?.lg" 
                                    :alt="post?.title"
                                    class="post-thumbnail"
                                >
                            </picture>
                        </div>
                        <h2>{{ post.title }}</h2>
                    </Link>
                </article>
            </section>
            <!-- community feed -->
        </section>
    </SiteLayout>
</template>