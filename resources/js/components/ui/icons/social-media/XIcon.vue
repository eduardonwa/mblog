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
        class="x-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="16.017" height="16.469" viewBox="0 0 16.017 16.469">
            <path id="Path_25" data-name="Path 25" d="M465.794,1087.5h1.371l-5.966,6.958,6.506,9.511h-4.741l-4.405-6.433-5.514,6.433h-1.357l6.273-7.323L451.7,1087.5h4.741l4.157,6.068Zm-12.151,1.05h2.144l9.978,14.369H463.62Z" transform="translate(-451.688 -1087.5)" :fill="isHovered ? hoverColor : color" fill-rule="evenodd"/>
        </svg>
    </button>
</template>