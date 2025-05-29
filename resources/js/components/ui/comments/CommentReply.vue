<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { useDateFormat } from '@/composables/useDateFormat';
import { RouteParams } from '../../../../../vendor/tightenco/ziggy/src/js';
import CommentReply from './CommentReply.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import ReplyIcon from '@/components/ui/icons/ReplyIcon.vue';
import DeleteIcon from '@/components/ui/icons/DeleteIcon.vue';

const { shortDate } = useDateFormat();

const props = defineProps({
  comment: Object,
  depth: {
    type: Number,
    default: 0
  },
  isFirstLevel: {
    type: Boolean,
    default: true,
  },
  isRoot: {
    type: Boolean,
    default: false,
  }
});

const showReplyForm = ref(false);
const replyForm = useForm({
  reply: ''
});

const submitReply = () => {
  replyForm.post(route('comments.replies.store', props.comment?.id), {
    preserveScroll: true,
    onSuccess: () => {
      replyForm.reset();
      showReplyForm.value = false;
    }
  });
};

const deleteComment = (commentId: RouteParams<string> | undefined) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', commentId), {
            preserveScroll: true,
            onSuccess: () => {
                // Optionally, you can show a success message or perform other actions
            },
        })
        console.log(`Comentario con ID ${commentId} eliminado`);
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
          <span class="comment-content__header__author">
            {{ comment?.commentator?.name || 'Rattlehead' }}
          </span>
          <p class="comment-content__header__date">{{ shortDate(comment?.created_at) }}</p>
        </header>
    
        <div class="comment-content__body">
          <p>{{ comment?.comment }}</p>
        </div>
    
        <div class="comment-content__actions">
          <!-- mostrar boton de replica solo si NO es el autor -->
          <ReplyIcon
            v-if="$page.props.auth.user && $page.props.auth.user.id !== comment?.user_id"
            class="reply-icon"
            hoverColor="#D3D7EA"
            @click="showReplyForm = !showReplyForm"
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
          <textarea
            v-model="replyForm.reply"
            rows="2"
            class="textarea"
            data-type="reply-box"
          ></textarea>
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
        <div
          v-if="comment?.comments && comment?.comments.length"
          class="replies"
        >
          <CommentReply
            v-for="reply in comment?.comments"
            :key="reply.id"
            :comment="reply"
            :depth="depth + 1"
          />
        </div>
      </div>
    </div>
  </div>
</template>