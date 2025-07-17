<script setup lang="ts">
import { cn } from '@/lib/utils';
import { AvatarRoot } from 'radix-vue';
import type { HTMLAttributes } from 'vue';
import { avatarVariant, type AvatarVariants } from '.';

const props = withDefaults(
    defineProps<{
        class?: HTMLAttributes['class'];
        size?: AvatarVariants['size'];
        shape?: AvatarVariants['shape'];
        responsive?: boolean;
        src?: string;
        alt?: string;
    }>(),
    {
        size: 'sm',
        shape: 'circle',
        responsive: false,
    },
);
</script>

<template>
    <AvatarRoot
        :class="cn(
            avatarVariant({ size, shape }),
            props.class,
            'avatar'
        )"
        >
        <slot>
            <img
                v-if="src"
                :src="src"
                class="avatar__image"
                :alt="alt || 'Avatar'"
            >
        </slot>
    </AvatarRoot>
</template>
