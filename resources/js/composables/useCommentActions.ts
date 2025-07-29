import { ref, nextTick } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

export function useCommentActions(comment: any) {
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
          commentMentionRef.value?.focusTextarea();
        }
      });
    }
  };

  const handleCancelClick = () => {
    showReplyForm.value = false;
    replyForm.reset();
  };

  /*
  * Manda el nuevo comentario al servidor y lo agrega a la lista de respuestas
  * del comentario padre. Utiliza la prop `comment` para acceder al comentario padre.
  * El nuevo comentario se agrega a `comment.children` si existe, o se crea un
  * nuevo array si no existe.
  */
  const submitReply = () => {
    replyForm.post(route('comments.replies.store', { comment: comment.id }), {
      preserveScroll: true,
      onSuccess: (page) => {
        showReplyForm.value = false;
        replyForm.reset();
        // obtiene el nuevo comentario
        const newReply = page.props?.newReply;
        if (newReply) {
          // que comment.children exista
          if (!comment.children) comment.children = [];
          comment.children.push(newReply);
        }
      }
    });
  };

  const deleteComment = (commentId: number) => {
    if (confirm('Are you sure you want to delete this comment?')) {
      router.delete(route('comments.destroy', { comment: commentId }), {
        preserveScroll: true,
      });
      console.log(`ID comment ${commentId} was deleted`);
    }
  };

  return {
    showReplyForm,
    commentMentionRef,
    replyForm,
    handleReplyClick,
    handleCancelClick,
    submitReply,
    deleteComment,
  };
}