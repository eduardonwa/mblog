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
          xmlns="http://www.w3.org/2000/svg"
          :width="size"
          :height="size"
          viewBox="0 0 24 24"
        >
            <path id="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 6.25)" :stroke="isHovered ? hoverColor : color"/>
            <path id="Vector-2" data-name="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 11.25)" :stroke="isHovered ? hoverColor : color"/>
            <path id="Vector-3" data-name="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 16.25)" :stroke="isHovered ? hoverColor : color"/>
            <path id="Vector-4" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
        </svg>
    </button>
</template>