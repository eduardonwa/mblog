<script setup lang="ts">
  import { router } from '@inertiajs/vue3';

  const props = defineProps({
      post: {
          type: Object,
          required: true,
      }
  });

  const emit = defineEmits(['update:post']);

  const toggleLike = async () => {
    const wasLiked = props.post.is_liked_by_user;
    
    // Optimistic update
    emit('update:post', {
        ...props.post,
        is_liked_by_user: !wasLiked,
        likes_count: wasLiked 
            ? props.post.likes_count - 1 
            : props.post.likes_count + 1
    });

    try {
        const method = wasLiked ? 'delete' : 'post';
        const url = wasLiked 
            ? route('posts.unlike', props.post.id)
            : route('posts.like', props.post.id);
        
        await router[method](url, {}, {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(route('post.show', props.post.slug), {
                    only: ['post'],
                    preserveState: true,
                    replace: true
                });
            }
        });
    } catch (error) {
        emit('update:post', props.post);
        console.error('Error:', error);
    }
  };
</script>

<template>
  <div class="like-button-wrapper">
    <button
      @click="toggleLike"
      :class="{
        'like-button--active': post.is_liked_by_user,
        'like-button--inactive': !post.is_liked_by_user
      }"
      class="like-button"
      :aria-label="post.is_liked_by_user ? 'Unlike' : 'Like'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
      <span>{{ post.likes_count }}</span>
    </button>
  </div>
</template>