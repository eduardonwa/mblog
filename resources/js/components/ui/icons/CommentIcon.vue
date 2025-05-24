<script setup lang="ts">
    import { ref } from 'vue';

    interface Props {
        fillColor?: string;
        size?: string | number; // Tamaño del contenedor
    }

    const props = withDefaults(defineProps<Props>(), {
        fillColor: 'currentColor',
        size: '24px',
    });

    const isHovered = ref(false);
</script>

<template>
    <button
        class="comment-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            :width="props.size"
            :height="props.size"
            style="display: inline-block; vertical-align: middle;"
        >
            <path
                d="M12 21a2 2 0 0 1-1.6-.8l-1.5-2a1 1 0 0 0-.4-.2H7a7 7 0 0 1-7-7V7a7 7 0 0 1 7-7h10a7 7 0 0 1 7 7v5a7 7 0 0 1-7 7h-1.5a1 1 0 0 0-.8.3l-1.5 2a2 2 0 0 1-1.2.7Z"
                :stroke="props.fillColor"
                stroke-width="1.8"
                fill="none"
                style="fill: none;"
            />
            <circle cx="7" cy="11" r="1" :fill="props.fillColor" />
            <circle cx="12" cy="11" r="1" :fill="props.fillColor" />
            <circle cx="17" cy="11" r="1" :fill="props.fillColor" />
        </svg>
    </button>
</template>