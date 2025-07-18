<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import CommentMention from './CommentMention.vue';
import CommentReply from './CommentReply.vue';
import { MentionableUser, Comment, ReplyFormData, ReplyForm } from '@/types';
import { ref } from 'vue';

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

const emit = defineEmits(['submit-reply', 'cancel-click']);

const commentMentionRef = ref(null);
</script>

<template>
    <div class="comment-replies">
        <div v-if="showReplyForm" class="reply-form">
            <CommentMention
                class="comment-suggestion"
                row="2"
                :users="users"
                v-model="replyForm.comment"
                ref="commentMentionRef"
            />

            <button
                @click="emit('submit-reply')"
                :disabled="replyForm.processing"
                class="button"
                data-type="reply-btn"
            >   Submit
            </button>

            <button
                type="button"
                @click="emit('cancel-click')"
            >   Cancel
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