<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import NavigationTopBarVisible from '@/components/ui/navigation-menu/NavigationTopBarVisible.vue';
import NavigationTopBarInvisible from '@/components/ui/navigation-menu/NavigationTopBarInvisible.vue';
import HomeIcon from './../ui/icons/HomeIcon.vue';
import GroupsIcon from './../ui/icons/GroupsIcon.vue';
import type { Category, SharedData } from '@/types';
import AppCategoriesMenu from '../AppCategoriesMenu.vue';

const page = usePage<SharedData>();

const logout = () => {
  router.post(route('logout'))
}

computed(() => usePage().url);

const isMenuOpen = ref(false);
// importamos las categories tipadas desde el middleware de inertia
const categories = computed<Category[]>(() => usePage().props.categories as Category[]);

// todo esto para quitar la clase "overflow" del body.
// Función específica para abrir
const openMenu = () => {
    isMenuOpen.value = true;
    document.body.style.overflow = 'hidden';
    document.body.classList.add('menu-open');
};

// Función específica para cerrar el menu con un boton
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
                    :auth="page.props.auth"
                    :isMenuOpen="isMenuOpen"
                    @open-menu="openMenu"
                    :categories="categories"
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
                            <HomeIcon color="#e6e9f3" hoverColor="#fff" />
                            Home
                        </Link>

                        <Link
                            :href="route('channel.index')"
                            class="clr-primary-100"
                        >
                            <GroupsIcon color="#e6e9f3" hoverColor="#fff"/>
                            Channels
                        </Link>
                    </div>

                    <div class="nav__hidden__menu__blog">
                        <h2 class="uppercase clr-secondary-300">blog</h2>
                        <template v-if="categories?.length">
                            <AppCategoriesMenu
                                :categories="categories"
                                variant="sidebar"
                            />
                        </template>
                    </div>

                    <div class="nav__hidden__menu__settings">
                        <h2 class="uppercase clr-secondary-300">settings</h2>
                        <form v-if="$page.props.auth.user" @submit.prevent="logout">
                            <button
                                type="submit"
                                class="logout-btn | button no-decor"
                                data-type="ghost"
                            >Logout
                            </button>
                        </form>
                        <Link
                            v-else :href="route('login')"
                            class="login-btn | button no-decor"
                            data-type="ghost"
                        >
                            Login
                        </Link>
                    </div>
                 </div>
            </div>
        </nav>
    </header>
</template>