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

    <AppLayout :breadcrumbs="breadcrumbs">
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
    </AppLayout>
</template>
