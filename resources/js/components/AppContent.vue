<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { computed } from 'vue';
import type { SidebarState } from '@/types';

interface Props {
    variant?: 'header' | 'sidebar';
    state?: SidebarState;
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() => props.class);

// Calcula el margen basado en el estado
const marginStyle = computed(() => {
    if (props.variant !== 'sidebar') return {};
    
    return {
        marginLeft: props.state === 'expanded' 
            ? '250px' 
            : props.state === 'collapsed' ? '60px' : '250px'
    };
});
</script>

<template>
  <main class="pusher" :data-state="state">
    <SidebarInset v-if="variant === 'sidebar'">
      <slot />
    </SidebarInset>
    <div v-else class="content-wrapper">
      <slot />
    </div>
  </main>
</template>