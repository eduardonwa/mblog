<script setup lang="ts">
import { useRolesPanel } from '@/composables/useRolesPanel';
import UserIcon from './ui/icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';

const { isAdmin, isMember, goTo } = useRolesPanel();
</script>

<template>
    <button
        v-if="isAdmin"
        class="button"
        data-type="ghost"
        @click="goTo('/admin')"
    >
        <UserIcon />
    </button>

    <button
        v-else-if="isMember"
        class="button"
        data-type="ghost"
        @click="goTo('/member')"
    >
        <UserIcon />
    </button>

    <Link v-else :href="route('register')">Sign Up</Link>
</template>