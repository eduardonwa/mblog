<script setup lang="ts">

import type { Post } from '@/types';
import { Link } from '@inertiajs/vue3';
import UphailIcon from './ui/icons/UphailIcon.vue';
import CommentIcon from './ui/icons/CommentIcon.vue';
import AuthorIcon from './ui/icons/AuthorIcon.vue';

interface MainPostProps {
    featuredPost: Post[];
}

const props = defineProps<MainPostProps>();
</script>

<template>
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
                        color="#D3D7EA"
                        hoverColor="#F4FFC7"
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
                        color="#D3D7EA"
                        hoverColor="#F4FFC7"
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
</template>