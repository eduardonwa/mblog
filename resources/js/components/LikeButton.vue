<script setup lang="ts">
  import axios from 'axios';
  import { ref, nextTick } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import type { PageProps } from '@inertiajs/core';

  const props = defineProps({
      post: {
          type: Object,
          required: true,
      },
      variant: {
        type: String,
        default: 'default',
        validator: (value: string) => ['default', 'mobile'].includes(value)
      }
  });

  const emit = defineEmits(['update:post']);
  const isPulsing = ref(false);
  const isCounting = ref(false);
  const page = usePage<PageProps>();

  const toggleLike = async (e: MouseEvent) => {
    if (!(page.props.auth as { user: any }).user) {
      router.visit(route('register'));
      return;
    }

    e.preventDefault();
    const wasLiked = props.post.is_liked_by_user;
    
    // activar animaciones
    isPulsing.value = false;
    isCounting.value = false;

    await nextTick();

    isPulsing.value = true;
    isCounting.value = true;

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
  <div class="like-button-wrapper">
    <button
      @click="toggleLike($event)"
      :class="{
        'like-button--active': post.is_liked_by_user,
        'like-button--inactive': !post.is_liked_by_user,
        [`like-button--${variant}`]: variant
      }"
      :aria-label="post.is_liked_by_user ? 'Unlike' : 'Like'"
      class="button"
      data-type="like"
    >
      <span
        :key="'count-'+post.likes_count+isCounting"
        class="like-count"
        :class="{'animating': isCounting}"
      >
        {{ post.likes_count }}
      </span>
      <svg
        :key="'icon-'+post.is_liked_by_user+isPulsing"
        :class="{'pulse-animation': isPulsing}"
        xmlns="http://www.w3.org/2000/svg"
        xml:space="preserve"
        width="30"
        height="30"
        viewBox="0 0 34 34"
      >
        <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
      </svg>
    </button>
  </div>
</template>