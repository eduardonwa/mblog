<script setup lang="ts">
import Sheet from '@/components/ui/sheet/Sheet.vue';
import SheetContent from '@/components/ui/sheet/SheetContent.vue';
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';
import { SIDEBAR_WIDTH_MOBILE, useSidebar } from './utils';

defineOptions({
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        side?: 'left' | 'right';
        variant?: 'sidebar' | 'floating' | 'inset';
        collapsible?: 'offcanvas' | 'icon' | 'none';
        class?: HTMLAttributes['class'];
    }>(),
    {
        side: 'left',
        variant: 'sidebar',
        collapsible: 'offcanvas',
    },
);

const { isMobile, state, openMobile, setOpenMobile } = useSidebar();
</script>

<template>
    <!-- version no colapsable -->
    <div
        v-if="collapsible === 'none'"
        :class="cn('', props.class)"
        v-bind="$attrs"
    >
        <slot />
    </div>

    <!-- mobile -->
    <Sheet v-else-if="isMobile" :open="openMobile" v-bind="$attrs" @update:open="setOpenMobile">
        <SheetContent
            data-sidebar="sidebar"
            data-mobile="true"
            :side="side"
            class="dashbar"
            :style="{
                '--sidebar-width': SIDEBAR_WIDTH_MOBILE,
            }"
        >
            <div class="dashbar__mobile">
                <slot />
            </div>
        </SheetContent>
    </Sheet>
    
    <!-- version dekstop -->
    <div
        v-else
        :data-state="state"
        :data-collapsible="state === 'collapsed' ? collapsible : ''"
        :data-variant="variant"
        :data-side="side"
        class="dashbar"
    >
        <!-- This is what handles the sidebar gap on desktop  -->
        <div
            :class="
                cn(
                    '',
                    '',
                    '',
                    variant === 'floating' || variant === 'inset'
                        ? ''
                        : '',
                )
            "
        />
        <!-- es como el wrapper que abraza el contenido de la navbar -->
        <div
            :class="
                cn(
                    '',
                    side === 'left'
                        ? ''
                        : '',
                    // Adjust the padding for floating and inset variants.
                    variant === 'floating' || variant === 'inset'
                        ? ''
                        : '',
                    props.class,
                )
            "
            v-bind="$attrs"
        >
            <!-- es el espacio real del contenido -->
            <div
                data-sidebar="sidebar"
                class=""
            >
                <slot />
            </div>
        </div>
    </div>
</template>