<script setup lang="ts">
    import UserIcon from '../icons/UserIcon.vue';
    import { Link } from '@inertiajs/vue3';
    import MenuIcon from '../icons/MenuIcon.vue';

    const props = defineProps({
        isMenuOpen: Boolean, // <- Recibe el estado del menú
        showCloseButton: Boolean // <- Nuevo prop para alternar entre íconos
    });

    const emit = defineEmits(['toggleMenu']); // <- Emite el evento
</script>

<template>
    <div @click="$emit('toggleMenu')">
      <MenuIcon class="nav__visible__toggle" v-if="!showCloseButton" />
      <span v-else class="close-icon">✕</span>
    </div>

    <Link href="/" class="nav__visible__logo">sickofmetal</Link>

    <div class="nav__visible__links">
        <Link
            href="/"
            class="clr-primary-100"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100"
        >
            Category 2
        </Link>
                <Link
            href="/"
            class="clr-primary-100"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100"
        >
            Category 2
        </Link>
                <Link
            href="/"
            class="clr-primary-100"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100"
        >
            Category 2
        </Link>
    </div>
    
    <div class="nav__visible__auth">
        <Link v-if="$page.props.auth.user" :href="route('dashboard.index')">
            <UserIcon/>
        </Link>
        <Link v-else :href="route('register')">Sign Up</Link>
    </div>
</template>

