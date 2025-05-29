<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
    color: '#D3D7EA',
    hoverColor: '#F4FFC7',
    size: '24px'
});
const isHovered = ref(false);
</script>

<template>
    <button
        class="reply-icon"
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
                id="vuesax_linear_message-add"
                data-name="vuesax/linear/message-add"
                transform="translate(-172 -378)"
            >
                <g id="message-add">
                    <path
                        id="Vector"
                        d="M14,0H6Q0,0,0,6V19a1,1,0,0,0,1,1H14q6,0,6-6V6Q20,0,14,0Z"
                        transform="translate(174 380)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-2"
                        data-name="Vector"
                        d="M0,0H7"
                        transform="translate(180.5 390)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-3"
                        data-name="Vector"
                        d="M0,7V0"
                        transform="translate(184 386.5)"
                        fill="none"
                        :stroke="isHovered ? hoverColor : color"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                    />
                    <path
                        id="Vector-4"
                        data-name="Vector"
                        d="M0,0H24V24H0Z"
                        transform="translate(172 378)"
                        fill="none"
                        opacity="0"
                    />
                </g>
            </g>
        </svg>
    </button>
</template>