import { cva, type VariantProps } from 'class-variance-authority';

export { default as Avatar } from './Avatar.vue';
export { default as AvatarFallback } from './AvatarFallback.vue';
export { default as AvatarImage } from './AvatarImage.vue';

export const avatarVariant = cva(
    'avatar',
    {
        variants: {
            size: {
                sm: 'avatar__sm',
                md: 'avatar__md',
                lg: 'avatar__lg',
                xl: 'avatar__xl',
                base: 'avatar__base',
            },
            shape: {
                circle: 'round',
            },
        },
    },
);

export type AvatarVariants = VariantProps<typeof avatarVariant>;
