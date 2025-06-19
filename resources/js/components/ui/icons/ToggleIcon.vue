<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  color?: string;
  hoverColor?: string;
  viewBox?: string;
  size?: string | number; // Tamaño del contenedor (ajustará automáticamente el SVG)
}

const props = withDefaults(defineProps<Props>(), {
  color: '#2587A4',
  hoverColor: '#35A6C2',
  size: '24px',
});

const isHovered = ref(false);
</script>

<template>
    <button
        class="toggle-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 21.5 21.5"
        >
            <path :fill="isHovered ? hoverColor : color" id="Vector" d="M7,20h6c5,0,7-2,7-7V7c0-5-2-7-7-7H7C2,0,0,2,0,7v6C0,18,2,20,7,20Z" transform="translate(0.75 0.75)" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"/>
            <g id="Group" transform="translate(-301.25 -381.25)">
                <path id="Vector-2" data-name="Vector" d="M3.04,0,0,3.04" transform="translate(314.11 393.82)" fill="none" stroke="#E5F3F9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                <path id="Vector-3" data-name="Vector" d="M0,0H10.3" transform="translate(306.85 393.82)" fill="none" stroke="#E5F3F9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            </g>
            <g id="Group-2" data-name="Group" transform="translate(-301.25 -381.25)">
                <path id="Vector-4" data-name="Vector" d="M0,3.04,3.04,0" transform="translate(306.85 387.14)" fill="none" stroke="#E5F3F9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                <path id="Vector-5" data-name="Vector" d="M10.3,0H0" transform="translate(306.85 390.18)" fill="none" stroke="#E5F3F9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            </g>
        </svg>
    </button>
</template>
