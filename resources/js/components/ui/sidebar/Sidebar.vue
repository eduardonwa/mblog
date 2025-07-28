<script setup lang="ts">
import type { SidebarProps } from '.'
import { cn } from '@/lib/utils'
import { Sheet, SheetContent } from '@/components/ui/sheet'
import SheetHeader from '@/components/ui/sheet/SheetHeader.vue'
import { SIDEBAR_WIDTH_MOBILE, useSidebar } from './utils'
import AppLogo from '@/components/AppLogo.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
  inheritAttrs: false,
})

const props = withDefaults(defineProps<SidebarProps>(), {
  side: 'left',
  variant: 'sidebar',
  collapsible: 'offcanvas',
})

const { isMobile, state, openMobile, setOpenMobile } = useSidebar()
</script>

<template>
  <div
    v-show="collapsible === 'none'"
    data-slot="sidebar"
    :class="cn(props.class)"
    v-bind="$attrs"
  >
    <slot />
  </div>

  <Sheet v-show="isMobile" :open="openMobile" v-bind="$attrs" @update:open="setOpenMobile">
    <SheetContent
      class="dashbar-mobile"
      data-sidebar="sidebar"
      data-slot="sidebar"
      data-mobile="true"
      :class="{ 'is-open': openMobile }"
      :side="side"
      :style="{
        '--sidebar-width': SIDEBAR_WIDTH_MOBILE,
      }"
    >
      <SheetHeader>
        <Link class="no-decor" :href="route('dashboard.index')">
            <AppLogo />
        </Link>
      </SheetHeader>
      <!-- sidebar items -->
      <div>
        <slot />
      </div>
    </SheetContent>
  </Sheet>

  <!-- hermano de sidebar inset -->
  <div
    v-show="!isMobile && collapsible !== 'none'"
    class="group peer"
    data-slot="sidebar"
    :data-state="state"
    :data-collapsible="state === 'collapsed' ? collapsible : ''"
    :data-variant="variant"
    :data-side="side"
  >
    <!-- This is what handles the sidebar gap on desktop  -->
    <div
      :class="cn(
        // 'relative w-(--sidebar-width) bg-transparent transition-[width] duration-200 ease-linear',
        'group-data-[collapsible=offcanvas]',
        'group-data-[side=right]',
        variant === 'floating' || variant === 'inset'
          ? 'group-data-[collapsible=icon]'
          : 'group-data-[collapsible=icon]',
      )"
    />
    <div
      :class="cn(
        'sidebar-wrapper',
        side === 'left'
          ? 'group-data-[collapsible=offcanvas]'
          : 'group-data-[collapsible=offcanvas]',
        // Adjust the padding for floating and inset variants.
        variant === 'floating' || variant === 'inset'
          ? 'group-data-[collapsible=icon]'
          : 'group-data-[collapsible=icon] group-data-[side=left] group-data-[side=right]',
        props.class,
      )"
      v-bind="$attrs"
    >
      <div
        data-sidebar="sidebar"
        class="dashbar-desktop group-data-[variant=floating] group-data-[variant=floating] group-data-[variant=floating] group-data-[variant=floating]"
      >
        <slot />
      </div>
    </div>
  </div>
</template>