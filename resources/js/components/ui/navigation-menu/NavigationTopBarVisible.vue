<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import MenuIcon from '../icons/MenuIcon.vue';
import type { Auth, Category } from '@/types/index';
import AppCategoriesMenu from '@/components/AppCategoriesMenu.vue';
import PanelRoles from '@/components/PanelRoles.vue';

interface Props {
    categories: Category[];
    isMenuOpen: Boolean,
    auth: Auth;
}

defineProps<Props>();

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
        <template v-if="categories?.length">
            <AppCategoriesMenu
                :categories="categories"
                variant="topbar"
            />
        </template>
    </div>
    
    <!-- login/register -->
    <div class="nav__visible__auth">
        <PanelRoles :user="auth.user" />
    </div>
</template>