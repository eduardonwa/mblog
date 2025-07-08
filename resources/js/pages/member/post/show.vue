<script setup lang="ts">
import { Post, Comment, MentionableUser, Channel } from '@/types';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import { BlogPostProps } from '@/components/ui/blog-post';
import { computed, ref } from 'vue';
import LikeButton from '@/components/LikeButton.vue';
import { Link } from '@inertiajs/vue3';
import ShareMenu from '@/components/ui/share-menu/ShareMenu.vue';

const props = defineProps<{
    post: Post;
    comments: Comment[];
    channel: Channel;
    mentionableUsers?: BlogPostProps['mentionableUsers'];
    url: string;
}>();

const mentionableUsersArr = computed<MentionableUser[]>(() => 
  Array.isArray(props.mentionableUsers) ? props.mentionableUsers : []
);

const localPost = ref<Post>(props.post);
</script>

<template>
    <SiteLayout>
        <section class="post-member container" data-type="blog-post">
            <header class="post-header">
                <h2 class="title">{{ post.title }}</h2>
                <div class="meta-primary">
                    <Link :href="route('author.posts', {user: post.user?.username})" class="author">{{ post?.user?.username || 'Rattlehead' }}</Link>
                    <span class="date">{{ post.smart_date }}</span>
                    <Link
                        :href="route('channel.show', {slug: channel.slug})"
                        class="channel"
                    >
                        {{ post.channel?.name }}
                    </Link>
                </div>

                <div class="desktop-interactions-wrapper">
                    <LikeButton
                        :post="localPost"
                        class="stick-this"
                        @update:post="updatedPost => localPost = updatedPost"
                    />
                    <ShareMenu class="share-menu share-menu--desktop" :url="props.url" variant="desktop" />
                </div>
            </header>

            <!-- body -->
            <article class="post-body">
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
            <footer class="mobile-interactions-wrapper">
                <LikeButton
                    :post="localPost"
                    @update:post="(newPost) => localPost = newPost"
                />
                <ShareMenu class="share-menu share-menu--mobile" :url="props.url" variant="mobile"/>
            </footer>
        </section>
    </SiteLayout>
</template>