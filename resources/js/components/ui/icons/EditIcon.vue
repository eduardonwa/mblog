<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  color?: string;
  hoverColor?: string;
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
        class="edit-icon"
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
            <g clip-path="url(#clip0_4418_7256)">
                <path d="M5.53999 19.5196C4.92999 19.5196 4.35999 19.3096 3.94999 18.9196C3.42999 18.4296 3.17999 17.6896 3.26999 16.8896L3.63999 13.6496C3.70999 13.0396 4.07999 12.2296 4.50999 11.7896L12.72 3.09956C14.77 0.929561 16.91 0.869561 19.08 2.91956C21.25 4.96956 21.31 7.10956 19.26 9.27956L11.05 17.9696C10.63 18.4196 9.84999 18.8396 9.23999 18.9396L6.01999 19.4896C5.84999 19.4996 5.69999 19.5196 5.53999 19.5196ZM15.93 2.90956C15.16 2.90956 14.49 3.38956 13.81 4.10956L5.59999 12.8096C5.39999 13.0196 5.16999 13.5196 5.12999 13.8096L4.75999 17.0496C4.71999 17.3796 4.79999 17.6496 4.97999 17.8196C5.15999 17.9896 5.42999 18.0496 5.75999 17.9996L8.97999 17.4496C9.26999 17.3996 9.74999 17.1396 9.94999 16.9296L18.16 8.23956C19.4 6.91956 19.85 5.69956 18.04 3.99956C17.24 3.22956 16.55 2.90956 15.93 2.90956Z" :fill="isHovered ? hoverColor : color" />
                <path d="M17.3399 10.9508C17.3199 10.9508 17.2899 10.9508 17.2699 10.9508C14.1499 10.6408 11.6399 8.27083 11.1599 5.17083C11.0999 4.76083 11.3799 4.38083 11.7899 4.31083C12.1999 4.25083 12.5799 4.53083 12.6499 4.94083C13.0299 7.36083 14.9899 9.22083 17.4299 9.46083C17.8399 9.50083 18.1399 9.87083 18.0999 10.2808C18.0499 10.6608 17.7199 10.9508 17.3399 10.9508Z" :fill="isHovered ? hoverColor : color" />
                <path d="M21 22.75H3C2.59 22.75 2.25 22.41 2.25 22C2.25 21.59 2.59 21.25 3 21.25H21C21.41 21.25 21.75 21.59 21.75 22C21.75 22.41 21.41 22.75 21 22.75Z" :fill="isHovered ? hoverColor : color" />
            </g>
            <defs>
                <clipPath id="clip0_4418_7256">
                    <rect width="24" height="24" :fill="isHovered ? hoverColor : color" />
                </clipPath>
            </defs>
        </svg>
    </button>
</template>