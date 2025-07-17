<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import CommentMention from './CommentMention.vue';
import CommentReply from './CommentReply.vue';
import { MentionableUser, Comment, ReplyFormData, ReplyForm } from '@/types';

withDefaults(defineProps<{
  showReplyForm?: boolean;
  replyForm: InertiaForm<ReplyForm>;
  users: MentionableUser[];
  hasReplies?: boolean;
  replies?: Comment[];
  depth?: number;
  commentMentionRef?: any;
}>(), {
    depth: 0, // numero inicial de replicas
});

const emit = defineEmits(['submit-reply']);
</script>

<template>
    <div class="comment-replies">
        <div v-if="showReplyForm" class="reply-form">
            <CommentMention
                row="2"
                class="comment-suggestion"
                v-model="replyForm.comment"
                ref="commentMentionRef"
                :users="users"
            />
            <button
                @click="emit('submit-reply')"
                :disabled="replyForm.processing"
                class="button"
                data-type="reply-btn"
            >
                Submit
            </button>
        </div>

        <div v-if="hasReplies" class="replies">
            <CommentReply
                v-for="reply in replies"
                :key="reply.id"
                :comment="reply"
                :depth="depth + 1"
                :users="users"
            />
        </div>
    </div>
</template>