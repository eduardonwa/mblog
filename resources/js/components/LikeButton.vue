<template>
  <button
    @click="toggleLike"
    :class="{
      'text-red-500': post.is_liked_by_user,
      'text-gray-500 hover:text-red-500': !post.is_liked_by_user
    }"
    class="flex items-center space-x-1 focus:outline-none"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
    <span class="ml-1">{{ post.likes_count }}</span>
  </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            is_liked_by_user: false,
            likes_count: 0
        })
    }
});

const emit = defineEmits(['update:post']);

const toggleLike = () => {
    const url = props.post.is_liked_by_user 
        ? route('posts.unlike', props.post.id)
        : route('posts.like', props.post.id);
    
    const method = props.post.is_liked_by_user ? 'delete' : 'post';
    
    router[method](url, {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            // Creamos un nuevo objeto post con los valores actualizados
            const updatedPost = {
                ...props.post,
                is_liked_by_user: !props.post.is_liked_by_user,
                likes_count: props.post.is_liked_by_user 
                    ? props.post.likes_count - 1 
                    : props.post.likes_count + 1
            };
            
            // Emitimos el evento de actualización
            emit('update:post', updatedPost);
        },
        onError: (errors) => {
            console.error('Error en like:', errors);
        }
    });
};
</script>