<script setup lang="ts">
    import UserIcon from '../icons/UserIcon.vue';
    import { Link } from '@inertiajs/vue3';
    import MenuIcon from '../icons/MenuIcon.vue';
    
    defineProps({
        isMenuOpen: Boolean
    });

    defineEmits(['openMenu']);
</script>

<template>
    <!-- toggle -->
    <div @click="$emit('openMenu')">
      <MenuIcon class="nav__visible__toggle" />
    </div>

    <!-- logo -->
    <Link href="/" class="nav__visible__logo">sickofmetal</Link>

    <!-- categories -->
    <div class="nav__visible__links">
        <Link
            href="/"
            class="clr-primary-100 no-decor"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100 no-decor"
        >
            Category 2
        </Link>
                <Link
            href="/"
            class="clr-primary-100 no-decor"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100 no-decor"
        >
            Category 2
        </Link>
                <Link
            href="/"
            class="clr-primary-100 no-decor"
        >
            Category 1
        </Link>

        <Link
            href="#"
            class="clr-primary-100 no-decor"
        >
            Category 2
        </Link>
    </div>
    
    <!-- login/register -->
    <div class="nav__visible__auth">
        <Link v-if="$page.props.auth.user" :href="route('dashboard')">
            <UserIcon/>
        </Link>
        <Link v-else :href="route('register')">Sign Up</Link>
    </div>
</template>