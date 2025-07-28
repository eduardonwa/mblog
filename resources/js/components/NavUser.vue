<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import UserMenuContent from './UserMenuContent.vue';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
</script>

<template>
    <SidebarMenu>
        <!-- wrapper del boton -->
        <SidebarMenuItem>
            <!-- wrapper de algo -->
            <DropdownMenu class="dropdown-menu">
                <!-- el boton -->
                <DropdownMenuTrigger as-child>
                    <!-- abarca toda la info del boton -->
                    <SidebarMenuButton class="dropdown-menu__trigger" size="lg">
                        <UserInfo :user="user" />
                        <ChevronsUpDown />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <!-- contenido (wrapper) -->
                <DropdownMenuContent class="dropdown-menu__content" side="bottom" align="start" :side-offset="4">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>

        </SidebarMenuItem>
    </SidebarMenu>
</template>