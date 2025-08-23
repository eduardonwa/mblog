<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  color?: string;
  hoverColor?: string;
  viewBox?: string;
  size?: string | number; // Tamaño del contenedor (ajustará automáticamente el SVG)
}

const props = withDefaults(defineProps<Props>(), {
  color: '#D3D7EA',
  hoverColor: '#F4FFC7',
  size: '24px',
});

const isHovered = ref(false);
</script>

<template>
  <button
    class="view-icon"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    :aria-pressed="isHovered"
  >
    <svg
        xmlns="http://www.w3.org/2000/svg"
        stroke-width="1.5" stroke="currentColor"
        viewBox="0 0 24 24"
        :width="size"
        :height="size"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" :stroke="isHovered ? hoverColor : color" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" :stroke="isHovered ? hoverColor : color" />
    </svg>
  </button>
</template>