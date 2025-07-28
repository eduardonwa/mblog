<script setup lang="ts">
import type { Post } from '@/types';
import LeaderboardCard from './LeaderboardCard.vue';

interface MainLeaderboardProps {
    leaderboard: Post[];
}

defineProps<MainLeaderboardProps>();
</script>

<template>
    <section
        v-if="leaderboard?.length"
        class="leaderboard | flow"
        aria-label="Leaderboard"
    >
        <h2 class="leaderboard__header | uppercase fw-semibold clr-secondary-200">Leaderboard</h2>
        <LeaderboardCard
            v-for="(post, index) in leaderboard"
            :key="post.id"
            :post="post"
            :rank="index + 1"
        />
    </section>
</template>