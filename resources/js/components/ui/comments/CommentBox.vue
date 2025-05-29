<script setup lang="ts">
import CommentReply from './CommentReply.vue';

const { post } = defineProps({
    post: Object,
});
</script>

<template>
    <div
        v-if="post?.comments.length"
        class="blog-post__comments__thread"
    >
        <CommentReply
            v-for="comment in post.comments"
            :key="comment.id"
            :comment="comment"
            :depth="0"
            :is-root="true"
        />
    </div>
</template>