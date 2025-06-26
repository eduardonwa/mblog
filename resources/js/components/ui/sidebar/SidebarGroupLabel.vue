<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { PrimitiveProps } from 'radix-vue';
import { Primitive } from 'radix-vue';
import type { HTMLAttributes } from 'vue';

const props = defineProps<
    PrimitiveProps & {
        class?: HTMLAttributes['class'];
    }
>();
</script>

<template>
    <Primitive
        data-sidebar="group-label"
        :as="as"
        :as-child="asChild"
        :class="
            cn(
                '',
                '',
                props.class,
            )
        "
    >
        <slot />
    </Primitive>
</template>
