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
  viewBox: '0 4 24 24',
});

const isHovered = ref(false);
</script>

<template>
    <button
        class="share-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg width="24" height="24" viewBox="0 0 24 24" :fill="isHovered ? hoverColor : color" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_4418_6693)">
                <path d="M12.5002 14.75H10.0002C9.59024 14.75 9.25024 14.41 9.25024 14C9.25024 13.59 9.59024 13.25 10.0002 13.25H12.5002C15.1202 13.25 17.2502 11.12 17.2502 8.5C17.2502 5.88 15.1202 3.75 12.5002 3.75H7.50024C4.88024 3.75 2.75024 5.88 2.75024 8.5C2.75024 9.6 3.14023 10.67 3.84023 11.52C4.10023 11.84 4.06023 12.31 3.74023 12.58C3.42023 12.84 2.95024 12.8 2.68024 12.48C1.75024 11.36 1.24023 9.95 1.24023 8.5C1.24023 5.05 4.04023 2.25 7.49023 2.25H12.4902C15.9402 2.25 18.7402 5.05 18.7402 8.5C18.7402 11.95 15.9502 14.75 12.5002 14.75Z" fill="white" style="fill: var(--fillg);"/>
                <path d="M16.5 21.75H11.5C8.05 21.75 5.25 18.95 5.25 15.5C5.25 12.05 8.05 9.25 11.5 9.25H14C14.41 9.25 14.75 9.59 14.75 10C14.75 10.41 14.41 10.75 14 10.75H11.5C8.88 10.75 6.75 12.88 6.75 15.5C6.75 18.12 8.88 20.25 11.5 20.25H16.5C19.12 20.25 21.25 18.12 21.25 15.5C21.25 14.4 20.86 13.33 20.16 12.48C19.9 12.16 19.94 11.69 20.26 11.42C20.58 11.15 21.05 11.2 21.32 11.52C22.25 12.64 22.76 14.05 22.76 15.5C22.75 18.95 19.95 21.75 16.5 21.75Z" :fill="isHovered ? hoverColor : color"/>
            </g>
            <defs>
                <clipPath id="clip0_4418_6693">
                    <rect width="24" height="24" :fill="isHovered ? hoverColor : color"/>
                </clipPath>
            </defs>
        </svg>
    </button>
</template>