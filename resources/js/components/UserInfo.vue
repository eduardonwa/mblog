<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

interface Props {
    user: User;
    showEmail?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.user.avatar && props.user.avatar !== '');
</script>

<template>
    <Avatar class="avatar">
        <AvatarImage v-if="showAvatar" :src="user.avatar" :alt="user.name" />
        <AvatarFallback>
            {{ getInitials(user.name) }}
        </AvatarFallback>
    </Avatar>

    <!-- aplicar flex column aqui -->
    <div class="userinfo">
        <span class="name">{{ user.name }}</span>
        <span class="email" v-if="showEmail">{{ user.email }}</span>
    </div>
</template>