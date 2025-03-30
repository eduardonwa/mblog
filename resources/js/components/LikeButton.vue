<template>
  <button 
    @click="toggleLike"
    :class="{ 'text-red-500': liked }"
    class="flex items-center gap-1"
  >
    ♥ {{ likesCount }}
  </button>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  postId: Number,
  initialLiked: Boolean,
  initialLikesCount: Number, // ⚠️ Nueva prop
});

const liked = ref(props.initialLiked);
const likesCount = ref(props.initialLikesCount || 0); // ✅ Usa initialLikesCount

const toggleLike = async () => {
  try {
    const response = await router.post(`/posts/${props.postId}/like`);
    liked.value = response.data.liked;
    likesCount.value = response.data.count;
  } catch (error) {
    console.error('Error al dar like:', error);
  }
};
</script>