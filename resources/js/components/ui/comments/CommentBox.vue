<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import ReplyIcon from '@/components/ui/icons/ReplyIcon.vue';
import { useDateFormat } from '@/composables/useDateFormat';
import { RouteParams } from '../../../../../vendor/tightenco/ziggy/src/js';
import DeleteIcon from '@/components/ui/icons/DeleteIcon.vue';

const {shortDate} = useDateFormat();

const { post } = defineProps({
  post: Object,
});

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
        v-if="post?.comments.length"
        class="blog-post__comments__thread"
    >
        <!-- comentario individual -->
        <div
            v-for="comment in post.comments"
            :key="comment.id"
            class="comment"
        >
            <header class="comment__header">
                <Link
                    class="comment__header__author | no-decor"
                    :href="route('author.posts', { user: comment.commentator.name })"
                >
                    <Avatar size="sm" />
                    {{ comment?.commentator?.name || 'Rattlehead' }}
                </Link>

                <p class="comment__header__date">{{ shortDate(comment.created_at) }}</p>
                
                <button 
                    v-if="$page.props.auth.user && $page.props.auth.user.id === comment.user_id"
                    @click="deleteComment(comment.id)"
                    class="delete-icon-wrapper"
                >
                    <DeleteIcon
                        color="#D3D7EA"
                        hoverColor="#F83B3B"
                        size="24px"
                    />
                </button>
            </header>

            <div class="comment__body">
                <p>{{ comment.comment }}</p>
            </div>

            <div class="comment__actions">
                <button type="button" class="button" data-type="reply-btn">
                    <ReplyIcon
                        color="#D3D7EA"
                        width="24"
                        height="24"
                    />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.delete-button {
  color: red;
  cursor: pointer;
  margin-left: 10px;
}
</style>