<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';
import { useSidebar } from './utils';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();

const { toggleSidebar } = useSidebar();
</script>

<template>
    <button
        data-sidebar="rail"
        aria-label="Toggle Sidebar"
        :tabindex="-1"
        title="Toggle Sidebar"
        :class="
            cn(
                '',
                props.class,
            )
        "
        @click="toggleSidebar"
    >
        <slot />
    </button>
</template>
