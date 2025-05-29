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
        href="https://www.instagram.com/warpig.som/"
        class="instagram-icon"
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
            viewBox="0 0 24 24"
        >
            <g
                id="Group_1"
                data-name="Group 1"
                transform="translate(0 -0.001)"
                :fill="isHovered ? hoverColor : color"
            >
                <path
                    id="Path_1"
                    data-name="Path 1"
                    d="M12,5.838A6.158,6.158,0,1,0,18.162,12,6.157,6.157,0,0,0,12,5.838Zm0,10.155a4,4,0,1,1,4-4A4,4,0,0,1,12,15.993Z"
                />
                <path
                    id="Path_2"
                    data-name="Path 2"
                    d="M16.948.076c-2.208-.1-7.677-.1-9.887,0A7.172,7.172,0,0,0,2.025,2.017C-.283,4.325.012,7.435.012,12c0,4.668-.26,7.706,2.013,9.979C4.342,24.291,7.5,23.988,12,23.988c4.624,0,6.22,0,7.855-.63,2.223-.863,3.9-2.85,4.065-6.419.1-2.209.1-7.677,0-9.887-.2-4.213-2.459-6.768-6.976-6.976Zm3.5,20.372c-1.513,1.513-3.612,1.378-8.468,1.378-5,0-7.005.074-8.468-1.393-1.685-1.677-1.38-4.37-1.38-8.453,0-5.525-.567-9.5,4.978-9.788,1.274-.045,1.649-.06,4.856-.06l.045.03c5.329,0,9.51-.558,9.761,4.986.057,1.265.07,1.645.07,4.847,0,4.942.093,6.959-1.394,8.453Z"
                />
                <circle
                    id="Ellipse_1"
                    data-name="Ellipse 1"
                    cx="1.439"
                    cy="1.439"
                    r="1.439"
                    transform="translate(16.967 4.156)"
                />
            </g>
        </svg>
    </a>
</template>