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
        class="crushing-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
            :fill="isHovered ? props.hoverColor : props.color"
            xmlns="http://www.w3.org/2000/svg"
        >
            <g clip-path="url(#clip0_4418_8986)">
                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM15.38 12.43L16.84 13.27C17.47 13.63 17.68 14.44 17.32 15.07C16.96 15.7 16.15 15.91 15.52 15.55L14.06 14.71C13.73 14.52 13.31 14.76 13.31 15.14V16.82C13.31 17.54 12.72 18.14 11.99 18.14C11.26 18.14 10.67 17.55 10.67 16.82V15.14C10.67 14.76 10.25 14.51 9.92 14.71L8.46 15.55C7.83 15.91 7.02 15.7 6.66 15.07C6.3 14.44 6.51 13.63 7.14 13.27L8.6 12.43C8.93 12.24 8.93 11.76 8.6 11.56L7.14 10.72C6.51 10.36 6.3 9.55 6.66 8.92C7.02 8.29 7.83 8.08 8.46 8.44L9.92 9.28C10.25 9.47 10.67 9.23001 10.67 8.85001V7.17C10.67 6.45 11.26 5.85001 11.99 5.85001C12.72 5.85001 13.31 6.44 13.31 7.17V8.85001C13.31 9.23001 13.73 9.48 14.06 9.28L15.52 8.44C16.15 8.08 16.96 8.29 17.32 8.92C17.68 9.55 17.47 10.36 16.84 10.72L15.38 11.56C15.05 11.76 15.05 12.24 15.38 12.43Z" :fill="isHovered ? props.hoverColor : props.color" />
            </g>
            <defs>
                <clipPath id="clip0_4418_8986">
                    <rect width="24" height="24" :fill="isHovered ? props.hoverColor : props.color"/>
                </clipPath>
            </defs>
        </svg>
    </button>
</template>