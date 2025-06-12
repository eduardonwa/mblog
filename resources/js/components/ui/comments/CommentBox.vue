<script setup lang="ts">
import { toRaw } from 'vue';
import CommentReply from './CommentReply.vue';
import type { Post, Comment, MentionableUser } from '@/types';

const props = defineProps<{
    post: Post;
    depth: number;
    comments: Comment[];
    users: MentionableUser[];
}>();

</script>

<template>
    <div v-if="comments?.length" class="blog-post__comments__thread">
        <CommentReply
            v-for="comment in comments"
            :key="comment.id"
            :comment="toRaw(comment)"
            :depth="0"
            :is-root="true"
            :users="users"
        />
    </div>
</template>