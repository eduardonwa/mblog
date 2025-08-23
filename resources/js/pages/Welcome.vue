<script setup lang="ts">
import type { NewsFeed, Post } from '@/types';
import { Head } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import MainPost from '@/components/MainPost.vue';
import MainLeaderboard from '@/components/Leaderboard/MainLeaderboard.vue';
import MainStaffPosts from '@/components/MainStaffPosts.vue';
import MainCommunityFeed from '@/components/CommunityFeed/MainCommunityFeed.vue';
import NewsFeedList from '@/components/NewsFeed/NewsFeedList.vue';
import FilterCommunityPosts from '@/components/CommunityFeed/FilterCommunityPosts.vue';

interface WelcomePageProps {
    featuredPost: Post[];
    staffPosts: Post[];
    leaderboard: Post[];
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
    newsFeed: NewsFeed;
    order: string;
}

const props = defineProps<WelcomePageProps>();
const { featuredPost, staffPosts, leaderboard, communityFeed } = props;
</script>

<template>
    <SiteLayout>
        <Head title="Welcome" />
        <!-- above the fold -->
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

            <NewsFeedList :news="newsFeed.data" />
        </section>

        <!-- below the fold -->
        <section class="community-grid" v-if="communityFeed?.data?.length">
            <FilterCommunityPosts :order="props.order" />
            <MainCommunityFeed
                :communityFeed="communityFeed"
                :order="order"
            />
        </section>

    </SiteLayout>
</template>