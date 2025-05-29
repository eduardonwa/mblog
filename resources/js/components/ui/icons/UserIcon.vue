<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
    color: '#d9d9de',
    hoverColor: '#ffffff',
    size: '24px'
});
const isHovered = ref(false);
</script>

<template>
    <button
        class="user-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
            style="display: block;"
        >
            <g id="user" transform="translate(-108 -188)" :fill="isHovered ? hoverColor : color">
                <path id="Vector-2" data-name="Vector" d="M10,5A5,5,0,1,1,5,0,5,5,0,0,1,10,5Z" transform="translate(115 190)" fill="inherit" />
                <path id="Vector-3" data-name="Vector" d="M9.09,0C4.08,0,0,3.36,0,7.5A.5.5,0,0,0,.5,8H17.68a.5.5,0,0,0,.5-.5C18.18,3.36,14.1,0,9.09,0Z" transform="translate(110.91 202.5)" fill="inherit" />
            </g>
        </svg>
    </button>
</template>