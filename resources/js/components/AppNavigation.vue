<script setup lang="ts">
    import { ref, computed, onMounted, onUnmounted } from 'vue';
    import { Link, usePage, router } from '@inertiajs/vue3';
    import NavigationTopBarVisible from '@/components/ui/navigation-menu/NavigationTopBarVisible.vue';
    import NavigationTopBarInvisible from '@/components/ui/navigation-menu/NavigationTopBarInvisible.vue';
    import HomeIcon from './ui/icons/HomeIcon.vue';
    import GroupsIcon from './ui/icons/GroupsIcon.vue';

    const currentRoute = computed(() => usePage().url);

    const isMenuOpen = ref(false);

    // todo esto para quitar la clase "overflow" del body.
    // Función específica para abrir
    const openMenu = () => {
        isMenuOpen.value = true;
        document.body.style.overflow = 'hidden';
        document.body.classList.add('menu-open');
    };

    // Función específica para cerrar
    const closeMenu = () => {
        isMenuOpen.value = false;
        document.body.style.overflow = '';
        document.body.classList.remove('menu-open');
    };

    // Función para alternar el menú
    onMounted(() => {
    window.addEventListener('popstate', closeMenu);
        // Escuchar el evento de clic en el documento
        const removeListener = router.on('navigate', () => {
            closeMenu();
            return () => removeListener();
        });
    });

    // Cerrar el menú al hacer clic fuera de él
    onUnmounted(() => {
        window.removeEventListener('popstate', closeMenu);
        document.body.style.overflow = '';
        document.body.classList.remove('menu-open');
    });
</script>

<template>
    <header>
        <nav class="nav" aria-label="Main navigation">
            <div class="nav__visible">
                <NavigationTopBarVisible
                    :isMenuOpen="isMenuOpen"
                    @open-menu="openMenu"
                />
            </div>

            <div :class="{ 'active': isMenuOpen }" class="nav__hidden">
                <div class="nav__hidden__top">
                    <NavigationTopBarInvisible
                        :isMenuOpen="isMenuOpen"
                        @close-menu="closeMenu"
                    />
                </div>

                <!-- menu -->
                 <div class="nav__hidden__menu">
                    <div class="nav__hidden__menu__links">
                        <Link
                            href="/"
                            class="clr-primary-100"
                        >
                            <HomeIcon />
                            Home
                        </Link>

                        <Link
                            href="#"
                            class="clr-primary-100"
                        >
                            <GroupsIcon />
                            Groups
                        </Link>
                    </div>

                    <div class="nav__hidden__menu__blog">
                        <h2 class="uppercase clr-secondary-300">blog</h2>
                        
                        <Link
                            href="/posts"
                            :class="{ 'active-link': currentRoute === '/posts' }"
                            class="no-decor"
                        >
                            Category 1
                        </Link>
                        <Link class="no-decor" href="#">Category 2</Link>
                        <Link class="no-decor" href="#">Category 3</Link>
                    </div>

                    <div class="nav__hidden__menu__groups">
                        <h2 class="uppercase clr-secondary-300">following</h2>
                        <Link class="no-decor" href="#">metall</Link>
                        <Link class="no-decor" href="#">metall</Link>
                        <Link class="no-decor" href="#">metall</Link>
                        <Link class="no-decor" href="#">metall</Link>
                    </div>

                    <div class="nav__hidden__menu__settings">
                        <h2 class="uppercase clr-secondary-300">settings</h2>
                        <Link class="no-decor" href="#">Logout</Link>
                    </div>
                 </div>
            </div>
        </nav>
    </header>
</template>