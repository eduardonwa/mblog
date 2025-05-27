<script setup lang="ts">
    import UserIcon from '../icons/UserIcon.vue';
    import { Link } from '@inertiajs/vue3';
    import MenuIcon from '../icons/MenuIcon.vue';
    import type { Category } from '@/types/index';
    
    interface Props {
        categories: Category[];
        isMenuOpen: Boolean,
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
            <Link
                v-for="category in categories"
                :key="category.id"
                :href="route('category.index', { slug: category.slug })"
                class="clr-primary-100 no-decor"
            >
                {{ category.name }}
            </Link>
        </template>
    </div>
    
    <!-- login/register -->
    <div class="nav__visible__auth">
        <Link v-if="$page.props.auth.user" :href="route('dashboard')">
            <UserIcon/>
        </Link>
        <Link v-else :href="route('register')">Sign Up</Link>
    </div>
</template>