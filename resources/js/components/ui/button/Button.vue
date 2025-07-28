<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Primitive, type PrimitiveProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';
import { buttonVariants, type ButtonVariants } from '.';

interface Props extends PrimitiveProps {
    variant?: ButtonVariants['variant'];
    size?: ButtonVariants['size'];
    class?: HTMLAttributes['class'];
    dataType?: 'primary' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link' | 'toggle' | 'accent' | 'form-step-next' | 'form-step-prev';
}

const props = withDefaults(defineProps<Props>(), {
    as: 'button',
    variant: 'default',
    dataType: 'primary',
});

// mapeo entre variant y dataType
const resolvedVariant = computed(() => props.variant);
const resolvedDataType = computed(() => {
    // Si variant es 'default', usamos dataType (que por defecto es 'primary')
    if (props.variant === 'default') return props.dataType;
    
    // Mapeamos las variantes a dataTypes equivalentes
    const variantToDataTypeMap: Record<string, string> = {
        destructive: 'destructive',
        outline: 'outline',
        secondary: 'secondary',
        ghost: 'ghost',
        link: 'link'
    };
    
    return variantToDataTypeMap[props.variant || 'default'] || 'primary';
});
</script>

<template>
    <Primitive
        :as="as"
        :as-child="asChild"
        :class="cn(buttonVariants({ variant: resolvedVariant, size }), props.class)"
        :data-type="resolvedDataType"
    >
        <slot />
    </Primitive>
</template>
