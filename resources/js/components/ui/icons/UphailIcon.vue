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
    class="uphail-icon"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    :aria-pressed="isHovered"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      :viewBox="viewBox"
      :width="size"
      :height="size"
    >
      <path
        d="M20.14,5.73a2.47,2.47,0,0,0-2.426,2.6v1.9a2.258,2.258,0,0,0-.809-.173,2.183,2.183,0,0,0-1.617.692,2.184,2.184,0,0,0-1.617-.692,2.258,2.258,0,0,0-.809.173V6.6a2.432,2.432,0,1,0-4.852,0v9.431a2.262,2.262,0,0,0-1.617-.779,2.474,2.474,0,0,0-1.86.779,2.807,2.807,0,0,0-.081,3.634l2.588,3.115a7.426,7.426,0,0,0,5.823,2.855h2.992a7.085,7.085,0,0,0,6.793-7.268V8.326A2.588,2.588,0,0,0,20.14,5.73Zm.809,8.652v3.98A5.4,5.4,0,0,1,15.773,23.9H12.781a5.878,5.878,0,0,1-4.61-2.25L5.584,18.536a.912.912,0,0,1,.081-1.3.885.885,0,0,1,.647-.26.977.977,0,0,1,.647.346l1.213,1.557a.873.873,0,0,0,.89.26.823.823,0,0,0,.566-.779V6.6a.811.811,0,1,1,1.617,0v6.922a.811.811,0,1,0,1.617,0v-.865a.811.811,0,1,1,1.617,0v.865a.811.811,0,1,0,1.617,0v-.865a.811.811,0,1,1,1.617,0v.865a.811.811,0,1,0,1.617,0V8.326a.811.811,0,1,1,1.617,0Z"
        :fill="isHovered ? hoverColor : color"
      />
    </svg>
  </button>
</template>