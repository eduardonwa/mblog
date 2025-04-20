<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    name?: string;
}>();
</script>

<template>
    <Head title="Dashboard" />
    <h1 class="text-center fw-bold margin-block-15">Coming soon.</h1>
<!--     <AppLayout :breadcrumbs="breadcrumbs">
        <div>
            <div>
                <div>
                    <PlaceholderPattern />
                </div>
                <div>
                    <PlaceholderPattern />
                </div>
                <div>
                    <PlaceholderPattern />
                </div>
            </div>
            <div>
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout> -->
</template>
