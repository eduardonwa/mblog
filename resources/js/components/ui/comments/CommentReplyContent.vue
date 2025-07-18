<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import { Comment, Post } from '@/types';
import Avatar from '../avatar/Avatar.vue';
import ReplyIcon from '../icons/ReplyIcon.vue';
import DeleteIcon from '../icons/DeleteIcon.vue';
import SimpleDropdown from '../simple-dropdown/SimpleDropdown.vue';
import MoreVerticalIcon from '../icons/MoreVerticalIcon.vue';
import ReportModal from '@/components/ReportModal.vue';

const props = defineProps<{
    post: Post;
    comment: Comment;
    rawComment: { comment: string };
    shortDate: (date: string | Date) => string;
    authUser?: { id: number; username: string; } | null;
}>();

const emit = defineEmits(['reply-click', 'delete-comment']);

const isDropdownOpen = ref(false);

const isAuthor = computed(() =>
  props.comment.commentator?.id === props.comment.commentable?.user_id
);
</script>

<template>
    <section class="comment-content" :id="`comment-${comment.id}`">
        <header class="comment">
            <Avatar
                class="comment__avatar"
                size="md"
                :src="comment.commentator?.avatar_url"
                :alt="comment.commentator?.username || 'Rattlehead'"
            />

            <article class="comment__body">
                <div class="user">
                    <Link
                        v-if="comment.commentator?.username"
                        :href="route('author.posts', { user: comment.commentator.username })"
                        :class="[
                            'user__author no-decor',
                            {'post-author': isAuthor}
                        ]"
                    >
                        {{ comment?.commentator?.username || 'Rattlehead' }}
                    </Link>
        
                    <p class="user__date">{{ shortDate(comment?.created_at) }}</p>
                </div>

                <p class="comment">{{ rawComment.comment }}</p>
            </article>
        </header>

        <SimpleDropdown
            v-if="authUser"
            class="comment-content__actions"
            v-model="isDropdownOpen"
            v-click-away="() => isDropdownOpen = false"
        >
            <template #header="{ isOpen, toggle }">
                <span @click.stop="toggle">
                    <MoreVerticalIcon />
                </span>
            </template>

            <template #content>
                <div
                    v-if="authUser && authUser.id !== comment?.user_id"
                    @click="emit('reply-click')"
                >
                    <p>
                        <ReplyIcon size="20" hoverColor="#D3D7EA"/>
                        Reply
                    </p>
                </div>

                <div v-if="authUser && authUser.id !== comment?.user_id">
                    <ReportModal
                        :reportable="{ id: comment.id, type: 'comment' }"
                        :popoverId="`reportPopover-comment-${comment.id}`"
                    />
                </div>

                <div
                    v-if="authUser && authUser.id === comment?.user_id"
                    @click="emit('delete-comment', comment?.id)"
                >
                    <p>
                        <DeleteIcon size="20" hoverColor="#D3D7EA"/>
                        Delete
                    </p>
                </div>
            </template>
        </SimpleDropdown>
    </section>
</template>