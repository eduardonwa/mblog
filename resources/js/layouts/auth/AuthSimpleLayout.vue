<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="text-center flow margin-block-8">
        <Link :href="route('home')" class="no-decor">
            <AppLogoIcon />
        </Link>
        <div class="flow">
            <!-- <h2 class="clr-primary-300">{{ title }}</h2> -->
            <p class="margin-block-end-5 fs-600 padding-inline-5 clr-primary-100">{{ description }}</p>
        </div>
    </div>
    <slot />
</template>
