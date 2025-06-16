<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';

defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();
</script>

<template>
    <header>
        <SidebarTrigger />
        <template v-if="breadcrumbs.length > 0">
            <Breadcrumbs class="breadcrumbs" :breadcrumbs="breadcrumbs" />
        </template>
    </header>
</template>
