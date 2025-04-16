<script setup lang="ts">
  import { router } from '@inertiajs/vue3';
  import { ref } from 'vue';

  const likeButton = ref<HTMLButtonElement | null>(null);
  const isAnimating = ref(false);

  const props = defineProps({
      post: {
          type: Object,
          required: true,
      }
  });

  const emit = defineEmits(['update:post']);

  const toggleLike = async () => {
  if (!likeButton.value) return;
  isAnimating.value = true;

  const wasLiked = props.post.is_liked_by_user;
  const newCount = wasLiked ? props.post.likes_count - 1 : props.post.likes_count + 1;
  
  // 1. Preparamos animación antes del cambio
  const counterEl = likeButton.value.querySelector('.hail-counter');
  if (counterEl) {
    const current = counterEl.querySelector('.current-count');
    const next = document.createElement('span');
    
    next.className = 'hail-count next-count';
    next.textContent = `${newCount} hailed`;
    counterEl.appendChild(next);
    
    // Forzar reflow para activar animación
    void next.offsetWidth;
    next.classList.add('animate');
    current?.classList.add('fade-out');
  }

  // 2. Optimistic update (tu código existente)
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
    // 3. Rollback visual si falla
    counterEl?.querySelector('.next-count')?.remove();
    counterEl?.querySelector('.current-count')?.classList.remove('fade-out');
    emit('update:post', props.post);
  } finally {
    // 4. Limpieza después de 600ms (dura más que la animación)
    setTimeout(() => {
      const current = counterEl?.querySelector('.current-count');
      const next = counterEl?.querySelector('.next-count');
      
      if (current && next) {
        current.remove();
        next.classList.remove('next-count', 'animate');
        next.classList.add('current-count');
      }
      
      isAnimating.value = false;
    }, 600);
  }
};
</script>

<template>
  <div class="like-button-wrapper">
    <button
      @click="toggleLike"
      :class="{
        'like-button--active': post.is_liked_by_user,
        'like-button--inactive': !post.is_liked_by_user,
        'pulse-animation': isAnimating,
      }"
      ref="likeButton"
      class="button"
      data-type="like"
      :aria-label="post.is_liked_by_user ? 'Unlike' : 'Like'"
    >
      <div class="like-counter"><span class="like-count current-count">{{ post.likes_count }}</span></div>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24" width="24" viewBox="0 0 32 32" stroke="currentColor">
        <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
      </svg>
    </button>
  </div>
</template>