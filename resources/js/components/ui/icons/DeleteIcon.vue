<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
    color: '#D3D7EA',
    hoverColor: '#F83B3B',
    size: '24px'
});
const isHovered = ref(false);
</script>

<template>
    <button
        class="delete-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
        >
            <g
                id="vuesax_linear_trash"
                data-name="vuesax/linear/trash"
                transform="translate(-108 -188)"
            >
                <g id="trash">
                    <path
                        id="Vector"
                        d="M18,.5C14.67.17,11.32,0,7.98,0A59.068,59.068,0,0,0,2.04.3L0,.5"
                        transform="translate(111 193.48)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-2"
                        data-name="Vector"
                        d="M0,2.97.22,1.66C.38.71.5,0,2.19,0H4.81C6.5,0,6.63.75,6.78,1.67L7,2.97"
                        transform="translate(116.5 190)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-3"
                        data-name="Vector"
                        d="M13.7,0l-.65,10.07c-.11,1.57-.2,2.79-2.99,2.79H3.64C.85,12.86.76,11.64.65,10.07L0,0"
                        transform="translate(113.15 197.14)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-4"
                        data-name="Vector"
                        d="M0,0H3.33"
                        transform="translate(118.33 204.5)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-5"
                        data-name="Vector"
                        d="M0,0H5"
                        transform="translate(117.5 200.5)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-6"
                        data-name="Vector"
                        d="M0,0H24V24H0Z"
                        transform="translate(108 188)"
                        fill="none"
                        opacity="0"
                    />
                </g>
            </g>
        </svg>
    </button>
</template>