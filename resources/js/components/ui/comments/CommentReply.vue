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
    :style="{ marginLeft: `${depth * 30}px` }"
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
      <!-- <div
        class="comment-content"
        :id="`comment-${comment.id}`"
      >
        <header class="comment-content__header">
          <Avatar
            size="sm"
            :src="comment.commentator?.avatar_url"
            :alt="comment.commentator?.username || 'Rattlehead'"
          />

          <Link 
            v-if="comment.commentator?.username"
            :href="route('author.posts', { user: comment.commentator.username })"
            class="comment-content__header__author | no-decor"
          >
            {{ comment?.commentator?.username || 'Rattlehead' }}
          </Link>

          <p class="comment-content__header__date">{{ shortDate(comment?.created_at) }}</p>
        </header>

        <div class="comment-content__body">
          <p>{{ rawComment.comment }}</p>
        </div>

        <div class="comment-content__actions">
          <ReplyIcon
            v-if="$page.props.auth.user && $page.props.auth.user.id !== comment?.user_id"
            class="reply-icon"
            hoverColor="#D3D7EA"
            @click="handleReplyClick"
          />
          <div 
            v-if="$page.props.auth.user && $page.props.auth.user.id === comment?.user_id"
            @click="deleteComment(comment?.id)"
            class="delete-icon-wrapper"
          >
            <DeleteIcon
                size="24px"
                hoverColor="#D3D7EA"
            />
            <span>delete</span>
          </div>
        </div>  
      </div>
        
      <div class="comment-replies">
        <div
          v-if="showReplyForm"
          class="reply-form"
        >
          <CommentMention
            ref="commentMentionRef"
            v-model="replyForm.comment"
            :users="users"
            row="2"
            class="comment-suggestion"
          />
          <button
            @click="submitReply"
            :disabled="replyForm.processing"
            class="button"
            data-type="reply-btn"
          >
            Submit
          </button>
        </div>
        <div v-if="hasReplies" class="replies">
          <CommentReply
            v-for="reply in rawComment.children"
            :key="reply.id"
            :comment="reply"
            :depth="depth + 1"
            :users="users"
          />
        </div>
      </div> -->
    </div>
  </div>
</template>