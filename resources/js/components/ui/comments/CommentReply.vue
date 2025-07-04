<script setup lang="ts">
import { computed, ref, nextTick, toRaw } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import { useDateFormat } from '@/composables/useDateFormat';
import { Comment, MentionableUser } from '@/types';
import CommentReply from './CommentReply.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import ReplyIcon from '@/components/ui/icons/ReplyIcon.vue';
import DeleteIcon from '@/components/ui/icons/DeleteIcon.vue';
import CommentMention from './CommentMention.vue';

const props = defineProps<{
  comment: Comment;
  depth: number;
  isFirstLevel?: boolean;
  isRoot?: boolean;
  users: MentionableUser[];
}>();

// Convertimos el comentario a objeto plano
const rawComment = computed(() => toRaw(props.comment));

const { shortDate } = useDateFormat();
const showReplyForm = ref(false);

const replyForm = useForm({
  comment: ''
});

const submitReply = () => {
  replyForm.post(route('comments.replies.store', { comment: props.comment.id }), {
    preserveScroll: true,
    onSuccess: () => {
      replyForm.reset();
      showReplyForm.value = false;
      router.reload({only: ['comments']});
    }
  });
};

const hasReplies = computed(() => {
  return rawComment.value.children?.length > 0;
});

const deleteComment = (commentId: number) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', {comment: commentId}), {
            preserveScroll: true,
        })
        console.log(`Comentario con ID ${commentId} eliminado`);
    }
};

const commentMentionRef = ref();

const handleReplyClick = () => {
  showReplyForm.value = !showReplyForm.value;
  
  // Si estamos abriendo el formulario
  if (showReplyForm.value) {
    nextTick(() => {
      // Obtener el nombre del usuario del comentario
      const username = props.comment?.commentator?.name || '';
      
      if (username) {
        // Autoetiquetar al usuario
        replyForm.comment = `@${username} `;
        
        // Enfocar el textarea después de un pequeño retraso
        setTimeout(() => {
          if (commentMentionRef.value) {
            commentMentionRef.value.focusTextarea();
          }
        }, 100);
      }
    });
  }
};
</script>

<template>
  <div
    class="comment-container"
    :class="{'first-level': isFirstLevel}"
    :style="{ marginLeft: `${depth * 30}px` }"
  >
    <div class="comment-wrapper" :class="{'is-root': isRoot}">
      <div class="comment-content">
        <header class="comment-content__header">
          <Avatar size="sm" />
          <Link 
            v-if="comment.commentator?.slug"
            :href="route('author.posts', { user: comment.commentator.slug })"
            class="comment-content__header__author | no-decor"
          >
            {{ comment?.commentator?.slug || 'Rattlehead' }}
          </Link>
          <p class="comment-content__header__date">{{ shortDate(comment?.created_at) }}</p>
        </header>
    
        <div class="comment-content__body">
          <p>{{ rawComment.comment }}</p>
        </div>
    
        <div class="comment-content__actions">
          <!-- mostrar boton de replica solo si NO es el autor -->
          <ReplyIcon
            v-if="$page.props.auth.user && $page.props.auth.user.id !== comment?.user_id"
            class="reply-icon"
            hoverColor="#D3D7EA"
            @click="handleReplyClick"
          />
          <!-- boton de eliminar (solo para el autor)-->
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
        <!-- Formulario para responder -->
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
    
        <!-- Mostrar réplicas existentes -->
        <div v-if="hasReplies" class="replies">
          <CommentReply
            v-for="reply in rawComment.children"
            :key="reply.id"
            :comment="reply"
            :depth="depth + 1"
            :users="users"
          />
        </div>
      </div>
    </div>
  </div>
</template>