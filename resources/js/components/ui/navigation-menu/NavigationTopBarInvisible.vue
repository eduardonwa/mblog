<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import PanelRoles from '@/components/PanelRoles.vue';

defineProps({
    isMenuOpen: Boolean
});

defineEmits(['closeMenu']);
</script>

<template>
    <div @click="$emit('closeMenu')">
        <span class="close-icon">✕</span>
    </div>

    <Link href="/" class="nav__hidden__top__logo">sickofmetal</Link>
    
    <div class="nav__hidden__auth">
        <PanelRoles  />
    </div>
</template>

<style scoped>
    .close-icon {
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
    }
</style>