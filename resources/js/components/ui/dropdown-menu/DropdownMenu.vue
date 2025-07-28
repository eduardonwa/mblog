<script setup lang="ts">
import { DropdownMenuRoot, useForwardPropsEmits, type DropdownMenuRootEmits, type DropdownMenuRootProps } from 'radix-vue';

const props = defineProps<DropdownMenuRootProps & { class?: string }>();
const emits = defineEmits<DropdownMenuRootEmits>();

const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
    <div :class="props.class">
        <DropdownMenuRoot v-bind="forwarded">
            <slot />
        </DropdownMenuRoot>
    </div>
</template>
