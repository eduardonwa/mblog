<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  color?: string;
  hoverColor?: string;
  size?: string | number;
}
const props = withDefaults(defineProps<Props>(), {
  color: 'var(--icon-color, #D3D7EA)',
  hoverColor: 'var(--icon-hover, #1e90ff)',
  size: '24px'
});
const isHovered = ref(false);
</script>

<template>
  <button
    class="author-icon"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    :aria-pressed="isHovered"
  >
    <svg
      :width="size"
      :height="size"
      viewBox="0 0 24 24"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g :stroke="isHovered ? hoverColor : color">
        <path 
            d="M12.0001 9.11914C12.9101 9.11914 13.6501 9.85917 13.6501 10.7692V13.2391C13.6501 14.1491 12.9101 14.8892 12.0001 14.8892C11.0901 14.8892 10.3501 14.1491 10.3501 13.2391" 
            stroke-width="1.5" 
            stroke-linecap="round"
            style="fill: none;"
        />
        <path 
            d="M16.98 13.4697C16.78 16.0497 14.62 18.0697 12 18.0697C9.24 18.0697 7 15.8297 7 13.0697V10.9297C7 8.16969 9.24 5.92969 12 5.92969C14.59 5.92969 16.72 7.89968 16.97 10.4197" 
            stroke-width="1.5" 
            stroke-linecap="round"
            style="fill: none;"
        />
        <path 
            d="M15 2H17C20 2 22 4 22 7V9" 
            stroke-width="1.5" 
            stroke-miterlimit="10" 
            stroke-linecap="round"
            style="fill: none;"
            stroke-linejoin="round"
        />
        <path 
            d="M2 9V7C2 4 4 2 7 2H9" 
            stroke-width="1.5" 
            stroke-miterlimit="10" 
            stroke-linecap="round"
            style="fill: none;"
            stroke-linejoin="round"
        />
        <path 
            d="M15 22H17C20 22 22 20 22 17V15" 
            stroke-width="1.5" 
            stroke-miterlimit="10" 
            stroke-linecap="round"
            style="fill: none;"
            stroke-linejoin="round"
        />
        <path 
            d="M2 15V17C2 20 4 22 7 22H9" 
            stroke-width="1.5" 
            stroke-miterlimit="10" 
            stroke-linecap="round"
            style="fill: none;"
            stroke-linejoin="round"
        />
      </g>
      <defs>
        <clipPath id="clip0_4418_3412">
          <rect width="24" height="24" fill="none" style="fill: none;"
          />
        </clipPath>
      </defs>
    </svg>
  </button>
</template>