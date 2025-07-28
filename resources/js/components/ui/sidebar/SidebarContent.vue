<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div
        data-sidebar="content"
        :class="cn('', props.class)"
    >
        <slot />
    </div>
</template>
