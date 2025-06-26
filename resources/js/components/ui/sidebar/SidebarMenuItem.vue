<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <li class="no-list-style" data-sidebar="menu-item" :class="cn('', props.class)">
        <slot />
    </li>
</template>
