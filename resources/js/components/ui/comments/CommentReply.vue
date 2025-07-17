<script setup lang="ts">
import { Comment, MentionableUser } from '@/types';
import { useCommentMeta } from '@/composables/useCommentMeta';
import { useCommentActions } from '@/composables/useCommentActions';
import CommentReplyContent from './CommentReplyContent.vue';
import CommentReplyReplies from './CommentReplyReplies.vue';

const props = defineProps<{
  comment: Comment;
  depth: number;
  isFirstLevel?: boolean;
  isRoot?: boolean;
  users: MentionableUser[];
}>();

const {
  showReplyForm,
  replyForm,
  commentMentionRef,
  handleReplyClick,
  submitReply,
  deleteComment
} = useCommentActions(props.comment, props.users);

const {
  rawComment,
  hasReplies,
  shortDate
} = useCommentMeta(props.comment);
</script>

<template>
  <div
    class="comment-container"
    :class="{'first-level': isFirstLevel}"
  >
    <div class="comment-wrapper" :class="{'is-root': isRoot}">
      <CommentReplyContent
        :comment="comment"
        :rawComment="rawComment"
        :shortDate="shortDate"
        :authUser="$page.props.auth.user"
        @reply-click="handleReplyClick"
        @delete-comment="deleteComment"
      />

      <CommentReplyReplies
        v-if="showReplyForm || hasReplies"
        :showReplyForm="showReplyForm"
        :replyForm="replyForm"
        :replies="comment.children"
        :users="users"
        :hasReplies="hasReplies"
        :depth="depth"
        :commentMentionRef="commentMentionRef"
        @submit-reply="submitReply"
      />
    </div>
  </div>
</template>