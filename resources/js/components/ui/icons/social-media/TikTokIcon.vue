<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
    color: 'var(--icon-color, #E6E9F3)',
    hoverColor: 'var(--icon-hover, #BAE2EE)',
    size: '24px'
});
const isHovered = ref(false);
</script>

<template>
    <a
        href="https://www.tiktok.com/@sickofmetalnet"
        class="tiktok-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
        target="_blank"
        rel="noopener noreferrer"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 22.232 26.203"
        >
            <path
                id="Path_8"
                data-name="Path 8"
                d="M525.232,332.554v4.464a9.934,9.934,0,0,1-5.526-1.668v9.5a8.345,8.345,0,1,1-7.21-8.276v4.548a3.9,3.9,0,1,0,2.755,3.728V327.037c0-.012,0-.024,0-.037h4.455v.046a5.508,5.508,0,0,0,5.508,5.508Z"
                transform="translate(-503 -327)"
                :fill="isHovered ? hoverColor : color"
            />
        </svg>
    </a>
</template>