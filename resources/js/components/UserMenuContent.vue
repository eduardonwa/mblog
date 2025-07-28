<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel>
        <div>
            <!-- aplicar flex column dentro de UserInfo -->
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>

    <DropdownMenuGroup class="dropdown-menu__group">
        <DropdownMenuItem class="dropdown-menu__group__item" :as-child="true">
            <Link :href="route('profile.edit')" class="button" data-type="ghost">
                <Settings />
                Settings
            </Link>
        </DropdownMenuItem>
    
        <DropdownMenuItem class="dropdown-menu__group__item" :as-child="true">
            <Link method="post" :href="route('logout')" class="button" data-type="ghost">
                <LogOut />
                Log out
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
</template>