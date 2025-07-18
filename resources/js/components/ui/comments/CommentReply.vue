<script setup lang="ts">
import { Comment, MentionableUser, Post } from '@/types';
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
  post: Post;
}>();

const {
  showReplyForm,
  replyForm,
  commentMentionRef,
  handleReplyClick,
  handleCancelClick,
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
      <!-- el diseño de un comentario -->
      <CommentReplyContent
        :post="post"
        :comment="comment"
        :rawComment="rawComment"
        :shortDate="shortDate"
        :authUser="$page.props.auth.user"
        :isReplying="showReplyForm"
        @reply-click="handleReplyClick"
        @delete-comment="deleteComment"
      />

      <!-- la creación de replicarle a un comentario -->
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
        @cancel-click="handleCancelClick"
      />
    </div>
  </div>
</template>