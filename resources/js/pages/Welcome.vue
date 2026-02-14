<script setup lang="ts">
import type { NewsItem, Paginated, Post, VideoItem } from '@/types';
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { Head } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import MainPost from '@/components/MainPost.vue';
import MainLeaderboard from '@/components/Leaderboard/MainLeaderboard.vue';
import MainStaffPosts from '@/components/MainStaffPosts.vue';
import MainCommunityFeed from '@/components/CommunityFeed/MainCommunityFeed.vue';
import FilterCommunityPosts from '@/components/CommunityFeed/FilterCommunityPosts.vue';
import ContentFeed from '@/components/ContentFeed/ContentFeed.vue';

interface WelcomePageProps {
    featuredPost: Post[];
    staffPosts: Post[];
    leaderboard: Post[];
    communityFeed: {
        data: Post[];
        next_page_url: string | null;
    };
    readFeed:   Paginated<NewsItem>;
    listenFeed: Paginated<VideoItem>;
    order: string;
}

const props = defineProps<WelcomePageProps>();
const { featuredPost, staffPosts, leaderboard, communityFeed } = props;

const activeTab = ref<"blog" | "feed">("blog");

watch(
  () => props.order,
  (order) => {
    if (order) activeTab.value = "feed"
  },
  { immediate: true }
)
</script>

<template>
    <SiteLayout>
        <section class="layout-selector">
            <button
                class="button"
                data-type="ghost"
                type="button"
                :class="{ active: activeTab === 'blog' }"
                @click="
                    activeTab = 'blog';
                    router.get(route('home'), {}, { preserveScroll: true, replace: true, preserveState: true });
                "
            >
                blog
            </button>
            <button
                class="button"
                data-type="ghost"
                type="button"
                :class="{ active: activeTab === 'feed' }"
                @click="activeTab = 'feed'"
            >
                feed
            </button>
        </section>
        
        <Head title="Welcome" />

        <section class="main-editorial-grid"
            :class="{ 'active-layout': activeTab === 'blog' }"
        >
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

            <ContentFeed
                :read-feed="props.readFeed"
                :listen-feed="props.listenFeed"
            />
        </section>

        <section
            class="community-grid"
            :class="{ 'active-layout': activeTab === 'feed' }"
            v-if="communityFeed?.data?.length"
        >
            <FilterCommunityPosts :order="props.order" />
            <MainCommunityFeed
                :communityFeed="communityFeed"
                :order="order"
            />
        </section>
    </SiteLayout>
</template>