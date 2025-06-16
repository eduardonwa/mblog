<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
    },
    {
        title: 'Password',
        href: '/settings/password',
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
    },
];

const currentPath = window.location.pathname;
</script>

<template>
    <div class="container" data-type="">
        
        <div class="heading-settings">
            <p>Settings</p>
            <p>Manage your profile and account settings</p>
        </div>

        <div>
            <!-- botones profile, pw y appearance -->
            <aside class="margin-block-7">
                <nav class="flex-group">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        data-type="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <!-- contenido de cada seccion -->
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>