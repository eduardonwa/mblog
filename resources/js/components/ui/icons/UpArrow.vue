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
        class="up-arrow-icon"
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
            <path d="M18.6801 13.9802L15.4701 10.7702L13.5101 8.80023C12.6801 7.97023 11.3301 7.97023 10.5001 8.80023L5.32007 13.9802C4.64007 14.6602 5.13007 15.8202 6.08007 15.8202H11.6901H17.9201C18.8801 15.8202 19.3601 14.6602 18.6801 13.9802Z" :fill="isHovered ? hoverColor : color" />
        </svg>
    </button>
</template>