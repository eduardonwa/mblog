<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import type { Post } from '@/types';
import { defineProps } from 'vue';

defineProps<{
  post: Post;
}>();
</script>

<template>
    <Link
        :href="route('post.show', { slug:post.slug })"
        class="feed-post | no-decor"
    >
        <!-- autor y fecha -->
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
</template>