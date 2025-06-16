<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { computed, onMounted, provide, ref } from 'vue';
import type { SidebarState } from '@/types';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = ref(true);

onMounted(() => {
    isOpen.value = localStorage.getItem('sidebar') !== 'false';
});

const sidebarState = computed<SidebarState>(() => {
    return isOpen.value ? 'expanded' : 'collapsed';
});

const handleSidebarChange = (open: boolean) => {
    isOpen.value = open;
    localStorage.setItem('sidebar', String(open));
};

// provee el estado del sidebar a los componentes
provide<{ sidebarState: SidebarState }>('sidebarState', { sidebarState: sidebarState.value });
</script>

<template>
    <div>
        <div v-if="variant === 'header'">
            <slot :sidebarState="sidebarState" />
        </div>
        <SidebarProvider v-else :default-open="isOpen" :open="isOpen" @update:open="handleSidebarChange">
            <!-- Pasa el estado del sidebar al slot -->
            <slot :sidebarState="isOpen ? 'expanded' : 'collapsed'" />
        </SidebarProvider>
    </div>
</template>