<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
  color: '#d9d9de',
  hoverColor: '#F4FFC7',
  size: '24px',
});
const isHovered = ref(false);
</script>

<template>
    <button
        class="channels-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            :width="size"
            :height="size"
            :fill="isHovered ? props.hoverColor : props.color"
            xmlns="http://www.w3.org/2000/svg"
        >
            <g clip-path="url(#clip0_4418_8191)">
                <path d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2ZM18.28 12.91C18.28 13.11 18.27 13.31 18.25 13.5C18.07 15.62 16.82 16.68 14.52 16.68H14.2C14 16.68 13.81 16.77 13.7 16.93L12.76 18.19C12.34 18.75 11.67 18.75 11.25 18.19L10.31 16.93C10.21 16.8 9.98 16.68 9.81 16.68H9.49C6.98 16.68 5.73 16.06 5.73 12.92V9.76C5.73 7.46 6.79 6.21 8.91 6.03C9.08 6.01 9.28 6 9.49 6H14.52C17.03 6 18.28 7.26 18.28 9.76V12.91Z" :fill="isHovered ? props.hoverColor : props.color"/>
            </g>
            <defs>
                <clipPath id="clip0_4418_8191">
                    <rect width="24" height="24" :fill="isHovered ? props.hoverColor : props.color"/>
                </clipPath>
            </defs>
        </svg>
    </button>
</template>