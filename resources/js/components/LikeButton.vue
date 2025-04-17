<script setup lang="ts">
  import axios from 'axios';

  const props = defineProps({
      post: {
          type: Object,
          required: true,
      }
  });

  const emit = defineEmits(['update:post']);

  const toggleLike = async (e: MouseEvent) => {
    e.preventDefault();
    const wasLiked = props.post.is_liked_by_user;
    
    // Optimistic update
    const tempPost = {
        ...props.post,
        is_liked_by_user: !wasLiked,
        likes_count: wasLiked 
            ? props.post.likes_count - 1 
            : props.post.likes_count + 1
    };
    emit('update:post', tempPost);

    try {
        const response = await axios[wasLiked ? 'delete' : 'post'](
            wasLiked 
                ? route('posts.unlike', props.post.id)
                : route('posts.like', props.post.id)
        );

        // Actualización con datos reales del servidor
        if (response.data.success) {
            emit('update:post', {
                ...tempPost,
                likes_count: response.data.likes_count,
                is_liked_by_user: response.data.is_liked_by_user
            });
        }
    } catch (error) {
        // Rollback en caso de error
        emit('update:post', props.post);
        console.error('Error:', error);
    }
};
</script>

<template>
  <div>
    <button
      @click="toggleLike($event)"
      :class="{
        'like-button--active': post.is_liked_by_user,
        'like-button--inactive': !post.is_liked_by_user
      }"
      :aria-label="post.is_liked_by_user ? 'Unlike' : 'Like'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
      <span>{{ post.likes_count }}</span>
    </button>
  </div>
</template>