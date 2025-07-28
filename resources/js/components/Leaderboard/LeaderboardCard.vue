<script setup lang="ts">
import { Post, User } from '@/types';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import { Link } from '@inertiajs/vue3';

const { post, rank} = defineProps<{
    post: Post;
    rank?: number;
}>();
</script>

<template>
    <Link
        class="leaderboard__container | no-decor"
        :href="route('channel.post.show', { 
            channel: post.channel?.slug, 
            post: post.slug 
        })"
    >
        <div class="leaderboard__container__left-column">
            <div class="leaderboard__container__left-column__user">
                <span class="clr-neutral-200">#{{ rank ?? '-' }}</span>
                <Avatar
                    size="sm"
                    shape="circle"
                    :src="post.user?.avatar_url"
                    :alt="post.user?.username || 'Rattlehead'"
                />
                <p>{{ post.user?.username }}</p>
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
</template>