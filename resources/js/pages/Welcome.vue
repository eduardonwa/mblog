<script setup lang="ts">
import type { Post, MAReleases } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import MainPost from '@/components/MainPost.vue';
import MainLeaderboard from '@/components/Leaderboard/MainLeaderboard.vue';
import MainStaffPosts from '@/components/MainStaffPosts.vue';
import MainCommunityFeed from '@/components/CommunityFeed/MainCommunityFeed.vue';

interface WelcomePageProps {
    featuredPost: Post[];
    staffPosts: Post[];
    leaderboard: Post[];
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
    albums: MAReleases[];
}

const props = defineProps<WelcomePageProps>();
const { featuredPost, staffPosts, leaderboard, communityFeed } = props;
</script>

<template>
    <SiteLayout>
        <Head title="Welcome" />

        <section class="main-editorial-grid">
            <MainPost
                v-if="featuredPost?.length"
                :featuredPost="featuredPost"
            />

            <MainLeaderboard
                v-if="leaderboard?.length"
                :leaderboard="leaderboard"
            />

            <hr class="hr-leaderboard | margin-inline-4">

            <MainStaffPosts
                v-if="staffPosts?.length"
                :staffPosts="staffPosts"
            />

            <section class="new-albums">
                <div class="new-albums-header">
                    <h2 class="clr-secondary-200 fw-semibold uppercase">metal releases</h2>
                    <p class="powered-string">by <a href="https://www.metal-archives.com" target="_blank" rel="noopener noreferrer" class="no-decor uppercase">metal-archives</a></p>
                </div>

                <ul class="new-albums__scroll-container">
                    <li class="album" v-for="(album, index) in albums" :key="index">
                        <a
                            :href="album.albumUrl"
                            rel="noopener noreferrer"
                            target="_blank"
                            class="album__info"
                        >
                            <div class="album__header">
                                <div class="genres">
                                    <p class="genre">{{ album.genre }}</p>
                                    <hr>
                                </div>

                                <div class="album-cover" v-if="album.cover">
                                    <img :src="album.cover" :alt="album.releaseTitle" />
                                </div>
                            </div>

                            <div class="album__content">
                                <p class="band">{{ album.band }}</p>
                                <p class="title">{{ album.releaseTitle }} ({{ album.type }})</p>
                                <p class="date">{{ album.releaseDate }}</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </section>
        </section>

        <section
            class="community-grid"
            v-if="communityFeed?.data?.length"
        >
            <MainCommunityFeed :communityFeed="communityFeed" />
        </section>

    </SiteLayout>
</template>