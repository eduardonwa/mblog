import { ref, nextTick } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

export function useCommentActions(comment: any, users: any[]) {
  const showReplyForm = ref(false);
  const commentMentionRef = ref();

  const replyForm = useForm({
    comment: ''
  });

  const handleReplyClick = () => {
    showReplyForm.value = !showReplyForm.value;

    if (showReplyForm.value) {
      nextTick(() => {
        const username = comment?.commentator?.username || '';
        if (username) {
          replyForm.comment = `@${username} `;
          setTimeout(() => {
            commentMentionRef.value?.focusTextarea();
          }, 100);
        }
      });
    }
  };

  const submitReply = () => {
    replyForm.post(route('comments.replies.store', { comment: comment.id }), {
      preserveScroll: true,
      onSuccess: () => {
        replyForm.reset();
        showReplyForm.value = false;
        router.reload({ only: ['comments'] });
      }
    });
  };

  const deleteComment = (commentId: number) => {
    if (confirm('Are you sure you want to delete this comment?')) {
      router.delete(route('comments.destroy', { comment: commentId }), {
        preserveScroll: true,
      });
      console.log(`Comentario con ID ${commentId} eliminado`);
    }
  };

  return {
    showReplyForm,
    replyForm,
    commentMentionRef,
    handleReplyClick,
    submitReply,
    deleteComment,
  };
}