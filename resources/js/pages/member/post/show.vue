<script setup lang="ts">
import { Post, Comment, MentionableUser, Channel } from '@/types';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import { BlogPostProps } from '@/components/ui/blog-post';
import { computed, ref } from 'vue';
import LikeButton from '@/components/LikeButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    post: Post;
    comments: Comment[];
    channel: Channel;
    mentionableUsers?: BlogPostProps['mentionableUsers'];
}>();

const mentionableUsersArr = computed<MentionableUser[]>(() => 
  Array.isArray(props.mentionableUsers) ? props.mentionableUsers : []
);

const localPost = ref<Post>(props.post);
</script>

<template>
    <SiteLayout>
        <section class="post-member container" data-type="blog-post" data-align="start">
            <header>
                <h2>{{ post.title }}</h2>
                <p>{{ post?.user?.slug || 'Rattlehead' }}</p>
                <span>{{ post.smart_date }}</span>
                <Link :href="route('channel.show', {slug: channel.slug})">{{ post.channel.name }}</Link>
            </header>

            <!-- body -->
            <article>
                <p>{{ post.body }}</p>
                <CommentForm :post="post" />
                <CommentBox
                    :post="post"
                    :comments="comments"
                    :users="mentionableUsersArr"
                    :depth="0"
                />
            </article>

            <!-- interacciones -->
            <footer>
                <LikeButton
                    :post="localPost"
                    @update:post="(newPost) => localPost = newPost"
                />
            </footer>
        </section>
    </SiteLayout>
</template>