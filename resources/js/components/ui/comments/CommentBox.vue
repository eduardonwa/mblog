<script setup lang="ts">
import { onMounted, toRaw } from 'vue';
import CommentReply from './CommentReply.vue';
import type { Post, Comment, MentionableUser } from '@/types';

const props = defineProps<{
    post: Post;
    depth: number;
    comments: Comment[];
    users: MentionableUser[];
}>();

// Scroll automático a comentarios específicos al cargar la página
onMounted(() => {
  const hash = window.location.hash;
  if (!hash.startsWith('#comment-')) return;

  // Delay para asegurar que el DOM está bien listo (opcional)
  setTimeout(() => {
    const el = document.querySelector(hash);
    if (!el) {
      console.warn('❌ No comment found on DOM:', hash);
      return;
    }

    // Scroll suave y con margen top
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Añadir clase highlight para el efecto visual
    el.classList.add('highlighted-comment');

    setTimeout(() => {
      el.classList.remove('highlighted-comment');
    }, 2500);
  }, 100); // 100ms para que cargue el layout
});


</script>

<template>
    <div v-if="comments?.length" class="blog-post__comments__thread">
        <CommentReply
            v-for="comment in comments"
            :key="comment.id"
            :comment="toRaw(comment)"
            :depth="0"
            :is-root="true"
            :users="users"
            :post="post"
        />
    </div>
</template>