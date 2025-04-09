<script setup>
    import { ref, computed } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';

    const currentRoute = computed(() => usePage().url); // Ej: '/category1'

    const isMenuOpen = ref(false);

    const openMenu = () => {
        isMenuOpen.value = true;
        document.body.style.overflow = 'hidden';
    };

    const closeMenu = () => {
        isMenuOpen.value = false;
        document.body.style.overflow = '';
    };
</script>

<template>
    <header class="header">
      <nav class="nav">
        <button
            @click="openMenu"
            class="nav__toggle"
            :class="{ 'hidden': isMenuOpen }"    
        >
            <svg class="nav__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path id="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 6.25)" fill="#292d32"/>
                <path id="Vector-2" data-name="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 11.25)" fill="#292d32"/>
                <path id="Vector-3" data-name="Vector" d="M18.75,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h18a.755.755,0,0,1,.75.75A.755.755,0,0,1,18.75,1.5Z" transform="translate(2.25 16.25)" fill="#292d32"/>
                <path id="Vector-4" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
            </svg>
        </button>
  
        <Link href="/" class="nav__logo-mobile">sickofmetal</Link>
  
        <div class="nav__auth">
          <Link v-if="$page.props.auth.user" :href="route('dashboard')">Dashboard</Link>
          <Link v-else :href="route('register')">Join</Link>
        </div>
  
        <div :class="{ 'active': isMenuOpen }" class="nav__menu">
            <button @click="closeMenu" class="nav-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="25" viewBox="0 0 10 20">
                    <path d="M.747,8.5a.742.742,0,0,1-.53-.22.754.754,0,0,1,0-1.06l7-7a.75.75,0,0,1,1.06,1.06l-7,7A.742.742,0,0,1,.747,8.5Z" fill="#292d32"/>
                    <path d="M7.747,8.5a.742.742,0,0,1-.53-.22l-7-7A.75.75,0,0,1,1.277.218l7,7a.754.754,0,0,1,0,1.06A.742.742,0,0,1,7.747,8.5Z" fill="#292d32"/>
                </svg>
            </button>

            <div class="nav__categories nav__categories--left">
                <Link
                    href="/posts"
                    :class="{ 'active-link': currentRoute === '/posts' }"
                >
                    Category 1
                </Link>
                <Link href="#">Category 2</Link>
            </div>

            <Link
                href="/"
                class="nav__logo-desktop"
                :class="{ 'active-link': currentRoute === '/' }"
            >
                sickofmetal
            </Link>

            <!-- Categorías derecha (desktop) -->
            <div class="nav__categories nav__categories--right">
                <Link href="#">Category 3</Link>
                <Link href="#">Category 4</Link>
            </div>
        </div>
      </nav>
    </header>
  </template>