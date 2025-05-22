<script setup lang="ts">
    import UserIcon from '../icons/UserIcon.vue';
    import { Link } from '@inertiajs/vue3';

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
        <Link v-if="$page.props.auth.user" :href="route('dashboard')">
            <UserIcon/>
        </Link>
        <Link v-else :href="route('register')">Sign Up</Link>
    </div>
</template>

<style scoped>
    .close-icon {
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
    }
</style>