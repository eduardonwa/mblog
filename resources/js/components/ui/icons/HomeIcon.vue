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
        class="menu-icon"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        :aria-pressed="isHovered"
    >
        <svg
            id="home"
            xmlns="http://www.w3.org/2000/svg"
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
        >
            <path id="Vector" d="M19,6.01,12.45.77A3.765,3.765,0,0,0,7.9.76L1.35,6.01A3.917,3.917,0,0,0,.04,9.44L1.3,16.98A3.716,3.716,0,0,0,4.87,20h10.6a3.773,3.773,0,0,0,3.58-3.03l1.26-7.54A4.018,4.018,0,0,0,19,6.01ZM10.92,16a.75.75,0,0,1-1.5,0V13a.75.75,0,0,1,1.5,0Z" transform="translate(1.83 2)" fill="#e6e9f3"/>
        </svg>
    </button>
</template>