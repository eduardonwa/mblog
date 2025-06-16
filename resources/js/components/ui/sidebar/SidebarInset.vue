<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <!-- sidebar inset -->
    <main
        class="container" data-type="dashboard"
        :class="
            cn(
                '',
                props.class,
            )
        "
    >
        <slot />
    </main>
</template>
