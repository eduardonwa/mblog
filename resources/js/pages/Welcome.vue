<script setup lang="ts">
    import { Head, Link, usePage } from '@inertiajs/vue3';
    import SiteLayout from '@/layouts/SiteLayout.vue';
    import Uphail from '@/components/ui/icons/Uphail.vue';
    import Comment from '@/components/ui/icons/Comment.vue';
    import Author from '@/components/ui/icons/Author.vue';
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
        <section class="main-editorial-grid | padding-6">
            <!-- post principal -->
            <header class="main-post" aria-label="Main Post">
                <div class="main-post__info">
                    <div class="main-post__info__header">
                        <h2 class="fs-700">
                            Abbath's New Album 'Dread Reaver' leaked - turns out it's just 40 minutes of him laughing in a snowstorm
                        </h2>
                        <p class="fs-500">
                            Corpsepaint sales spike 666% as fans prepare for the frostbitten apocalypse. Pre-order now before Odin changes his WiFi password
                        </p>
                    </div>
                    <!-- uphails, comentarios, autor -->
                    <div class="main-post__info__details">
                        <div>
                            <Uphail
                                size="24px"
                                fillColor="#D3D7EA"
                                style="margin-right: .4rem;"
                            />
                            <span>uphails</span>
                        </div>
    
                        <div class="padding-inline-4">
                            <Comment
                                class="main-post-comment-icon"
                                size="24px"
                                style="margin-right: .4rem;"
                            />
                            <span>comments</span>
                        </div>
    
                        <div>
                            <Author
                                class="main-post-author-icon"
                                color="#d9d9de" 
                                hoverColor="#1e90ff"
                                size="24px"
                                style="margin-right: .4rem;"
                            />
                            <span>warpig</span>
                        </div>
                    </div>
                </div>

                <div class="main-post__image">
                    <img src="images/albums/clayman.webp" alt="">
                </div>
            </header>

            <!-- leaderboard -->
            <section class="leaderboard | flow" aria-label="Leaderboard">
                <h2 class="uppercase fw-semibold clr-secondary-200">Leaderboard</h2>

                <article class="leaderboard__container">
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
                            <p>warpig</p>
                        </div>
                        <div class="leaderboard__container__left-column__post">
                            <h2>"Funeral Fog" (Norway, 2025) - Pure kvlt lo-fi necrotones for the real connoisseurs!</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">2</span>
                        <Uphail
                            size="24px"
                            fillColor="#A0DD05"
                            style="margin-right: .4rem;"
                        />
                    </div>
                </article>

                <article class="leaderboard__container">
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
                            <p>warpig</p>
                        </div>
                        <div class="leaderboard__container__left-column__post">
                            <h2>"Funeral Fog" (Norway, 2025) - Pure kvlt lo-fi necrotones for the real connoisseurs!</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">2</span>
                        <Uphail
                            size="24px"
                            fillColor="#A0DD05"
                            hoverColor="#BFF625"
                            style="margin-right: .4rem;"
                        />
                    </div>
                </article>

                <article class="leaderboard__container">
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
                            <p>warpig</p>
                        </div>
                        <div class="leaderboard__container__left-column__post">
                            <h2>"Funeral Fog" (Norway, 2025) - Pure kvlt lo-fi necrotones for the real connoisseurs!</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">2</span>
                        <Uphail
                            size="24px"
                            fillColor="#A0DD05"
                            hoverColor="#BFF625"
                            style="margin-right: .4rem;"
                        />
                    </div>
                </article>

                <article class="leaderboard__container">
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
                            <p>warpig</p>
                        </div>
                        <div class="leaderboard__container__left-column__post">
                            <h2>"Funeral Fog" (Norway, 2025) - Pure kvlt lo-fi necrotones for the real connoisseurs!</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">2</span>
                        <Uphail
                            size="24px"
                            fillColor="#A0DD05"
                            hoverColor="#BFF625"
                            style="margin-right: .4rem;"
                        />
                    </div>
                </article>

                <article class="leaderboard__container">
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
                            <p>warpig</p>
                        </div>
                        <div class="leaderboard__container__left-column__post">
                            <h2>"Funeral Fog" (Norway, 2025) - Pure kvlt lo-fi necrotones for the real connoisseurs!</h2>
                        </div>
                    </div>
                    <!-- uphail btn -->
                    <div class="leaderboard__container__uphail">
                        <span class="clr-primary-300">2</span>
                        <Uphail
                            size="24px"
                            fillColor="#A0DD05"
                            hoverColor="#BFF625"
                            style="margin-right: .4rem;"
                        />
                    </div>
                </article>
            </section>

            <!-- posts secundarios -->
            <section class="secondary-posts" aria-label="Featured posts">
                <div class="secondary-posts__post-card">
                    <div class="secondary-posts__post-card__image-wrapper">
                        <img src="images/albums/deftones.webp" alt="">
                    </div>
                    <h2>Bestial Devastation 2: Apocalypse now with riffs in High Definition</h2>
                </div>

                <div class="secondary-posts__post-card">
                    <div class="secondary-posts__post-card__image-wrapper">
                        <img src="images/albums/terrasite.webp" alt="">
                    </div>
                    <h2>Mastodon in chaos as Brent Hinds quits to pursue career as space blues outlaw</h2>
                </div>

                <div class="secondary-posts__post-card">
                    <div class="secondary-posts__post-card__image-wrapper">
                        <img src="images/albums/deftones.webp" alt="">
                    </div>
                    <h2>Bestial Devastation 2: Apocalypse now with riffs in High Definition</h2>
                </div>

                <div class="secondary-posts__post-card">
                    <div class="secondary-posts__post-card__image-wrapper">
                        <img src="images/albums/terrasite.webp" alt="">
                    </div>
                    <h2>Mastodon in chaos as Brent Hinds quits to pursue career as space blues outlaw</h2>
                </div>
            </section>

            <!-- community feed -->
        </section>
    </SiteLayout>
</template>