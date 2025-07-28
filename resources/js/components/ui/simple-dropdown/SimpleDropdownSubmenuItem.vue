<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  href: string
  text: string
}>()
</script>

<template>
    <li class="submenu-item">
        <Link :href="href">
            {{ text }}
        </Link>
    </li>
</template>