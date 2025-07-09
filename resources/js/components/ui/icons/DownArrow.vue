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
        class="down-arrow-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
            :fill="isHovered ? hoverColor : color"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M17.92 8.17969H11.69H6.07999C5.11999 8.17969 4.63999 9.33969 5.31999 10.0197L10.5 15.1997C11.33 16.0297 12.68 16.0297 13.51 15.1997L15.48 13.2297L18.69 10.0197C19.36 9.33969 18.88 8.17969 17.92 8.17969Z" :fill="isHovered ? hoverColor : color"/>
        </svg>
    </button>
</template>