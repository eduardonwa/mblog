<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
const props = defineProps<{ order: string }>()
import CrushingIcon from '@/components/ui/icons/CrushingIcon.vue';
import NewestIcon from '@/components/ui/icons/NewestIcon.vue';
import GroupsIcon from '../ui/icons/GroupsIcon.vue';
</script>

<template>
    <div class="order-filters">
        <Link
            preserve-scroll
            :href="route('home', { order: 'crushing' })"
            :class="{ active: props.order === 'crushing' }"
        >
            <CrushingIcon size="24px"/>
            Top Picks
        </Link>
        
        <Link
            preserve-scroll
            :href="route('home', { order: 'newest' })"
            :class="{ active: props.order === 'newest' }"
        >
            <NewestIcon size="24px"/>
            Newest
        </Link>
    
        <Link
            preserve-scroll
            :href="route('home', { order: 'channel' })"
            :class="{ active: props.order === 'channel' }"
        >
            <GroupsIcon
                color="#fff"    
                size="24px"/>
            Channels
        </Link>
    </div>
</template>