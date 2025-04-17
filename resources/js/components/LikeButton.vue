<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';

const likeButton = ref<HTMLButtonElement | null>(null);
const isAnimating = ref(false);

let visibilityHandler: () => void;

const props = defineProps({
    post: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['update:post']);

const toggleLike = async (event: MouseEvent) => {
    event.preventDefault();
    event.stopImmediatePropagation();
    
    if (!likeButton.value || isAnimating.value) return;
    
    isAnimating.value = true;
    const wasLiked = props.post.is_liked_by_user;
    const newCount = wasLiked ? props.post.likes_count - 1 : props.post.likes_count + 1;
    
    // Animación del contador
    const counterEl = likeButton.value.querySelector('.like-counter');
    if (counterEl) {
        counterEl.classList.add('animating');
    }

    // Optimistic update
    emit('update:post', {
        ...props.post,
        is_liked_by_user: !wasLiked,
        likes_count: newCount
    });

    try {
        const method = wasLiked ? 'delete' : 'post';
        const url = wasLiked 
            ? route('posts.unlike', props.post.id)
            : route('posts.like', props.post.id);
        
        // SOLUCIÓN CLAVE: Usar replace en lugar de visit
        await router[method](url, {}, {
            preserveScroll: true,
            replace: true, // Evita recarga de la página
            only: [] // No recargar ningún dato del componente
        });
    } catch (error) {
        emit('update:post', props.post);
    } finally {
        setTimeout(() => {
            isAnimating.value = false;
            counterEl?.classList.remove('animating');
        }, 600);
    }
};

onMounted(() => {
    visibilityHandler = () => {
        if (document.visibilityState === 'hidden' && isAnimating.value) {
            isAnimating.value = false;
        }
    };
    document.addEventListener('visibilitychange', visibilityHandler);
});

onBeforeUnmount(() => {
    document.removeEventListener('visibilitychange', visibilityHandler);
});
</script>

<template>
  <div class="like-button-wrapper">
    <button
      ref="likeButton"
      @click="toggleLike"
      :class="{
        'like-button--active': post.is_liked_by_user,
        'pulse-animation': isAnimating,
      }"
      class="button"
      data-type="like"
      :aria-label="post.is_liked_by_user ? 'Unlike' : 'Like'"
    >
      <div class="like-counter">
        <span class="like-count">{{ post.likes_count }}</span>
      </div>
      <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="30" height="30" viewBox="0 0 34 34">
        <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
      </svg>
    </button>
  </div>
</template>