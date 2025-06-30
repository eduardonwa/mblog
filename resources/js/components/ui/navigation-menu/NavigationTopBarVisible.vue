<script setup lang="ts">
import UserIcon from '../icons/UserIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';
import MenuIcon from '../icons/MenuIcon.vue';
import type { Auth, Category } from '@/types/index';
import { onMounted } from 'vue';

interface Props {
    categories: Category[];
    isMenuOpen: Boolean,
    auth: Auth;
}

defineProps<Props>();

defineEmits(['openMenu']);

const page = usePage()
const auth = page.props.auth as Auth;
const isAdmin = auth.roles?.includes('admin')
const isMember = auth.roles.includes('member')

function goTo(url: string) {
  window.location.href = url
}
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
        <button class="button" data-type="ghost" v-if="isAdmin" @click="goTo('/admin')">
            <UserIcon />
        </button>
        <button class="button" data-type="ghost" v-else-if="isMember" @click="goTo('/member')">
            <UserIcon />
        </button>
        <Link v-else :href="route('register')">Sign Up</Link>
    </div>
</template>