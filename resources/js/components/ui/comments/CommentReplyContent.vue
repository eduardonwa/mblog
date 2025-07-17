<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Avatar from '../avatar/Avatar.vue';
import ReplyIcon from '../icons/ReplyIcon.vue';
import DeleteIcon from '../icons/DeleteIcon.vue';
import { Comment } from '@/types';

const props = defineProps<{
  comment: Comment;
  rawComment: { comment: string };
  shortDate: (date: string | Date) => string;
  authUser?: { id: number } | null;
}>();

const emit = defineEmits(['reply-click', 'delete-comment']);
</script>

<template>
    <div
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
                v-if="authUser && authUser.id !== comment?.user_id"
                class="reply-icon"
                hoverColor="#D3D7EA"
                @click="emit('reply-click')"
            />
            <div
                v-if="authUser && authUser.id === comment?.user_id"
                @click="emit('delete-comment', comment?.id)"
                class="delete-icon-wrapper"
            >
                <DeleteIcon size="24px" hoverColor="#D3D7EA" />
                <span>delete</span>
            </div>
        </div>
    </div>
</template>