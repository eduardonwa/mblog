<script setup lang="ts">
defineProps<{
    message?: string;
    class?: string;
}>();
</script>

<template>
    <transition name="fade">
        <div v-if="message" :class="['input-error-message', $attrs.class]">
            {{ message }}
        </div>
    </transition>
</template>

<style scoped>
.input-error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: bold;
    text-align: center;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>