<script setup lang="ts">
import CommentReply from './CommentReply.vue';
import type { Post, Comment } from '@/types';

const props = defineProps<{
    post: Post;
    comments: Comment[];
    users: MentionableUser[];
}>();

interface MentionableUser {
  id: number;
  name: string;
}
</script>

<template>
    <div
        v-if="comments && comments.length"
        class="blog-post__comments__thread"
    >
        <CommentReply
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            :depth="0"
            :is-root="true"
            :users="users"
        />
    </div>
</template>