<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  color?: string;
  hoverColor?: string;
  viewBox?: string;
  size?: string | number; // Tamaño del contenedor (ajustará automáticamente el SVG)
}

const props = withDefaults(defineProps<Props>(), {
  color: '#fff',
  hoverColor: '#F4FFC7',
  size: '24px',
});

const isHovered = ref(false);
</script>

<template>
    <button
        class="newest-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            :fill="isHovered ? hoverColor : color"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.06 13.79C15.91 13.94 15.72 14.01 15.53 14.01C15.34 14.01 15.15 13.94 15 13.79L12 10.79L9 13.79C8.71 14.08 8.23 14.08 7.94 13.79C7.65 13.5 7.65 13.02 7.94 12.73L11.47 9.2C11.76 8.91 12.24 8.91 12.53 9.2L16.06 12.73C16.35 13.03 16.35 13.5 16.06 13.79Z" :fill="isHovered ? hoverColor : color"/>
        </svg>
    </button>
</template>