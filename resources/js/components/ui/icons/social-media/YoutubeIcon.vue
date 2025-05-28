<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
        class="youtube-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
        href="https://www.youtube.com/@sickofmetal"
        target="_blank"
        rel="noopener noreferrer"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 24 17"
        >
            <path
                id="Path_3"
                data-name="Path 3"
                d="M23.469,5.929l.03.2a3.043,3.043,0,0,0-2.068-2.112l-.021-.005c-1.871-.508-9.4-.508-9.4-.508s-7.51-.01-9.4.508A3.044,3.044,0,0,0,.527,6.1l0,.021A33.454,33.454,0,0,0,.553,18.072l-.031-.2A3.043,3.043,0,0,0,2.59,19.986l.021,0c1.869.509,9.4.509,9.4.509s7.509,0,9.4-.509a3.043,3.043,0,0,0,2.084-2.1l0-.021a31.265,31.265,0,0,0,.5-5.648c0-.073,0-.147,0-.221s0-.149,0-.23a32.256,32.256,0,0,0-.531-5.846ZM9.608,15.651V8.358l6.266,3.652Z"
                transform="translate(0 -3.5)"
                :fill="isHovered ? hoverColor : color"
            />
        </svg>
    </a>
</template>