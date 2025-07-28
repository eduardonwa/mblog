<script setup lang="ts">
defineProps<{
    message?: string;
    class?: string;
}>();
</script>

<template>
    <transition name="fade">
        <div v-if="message" :class="['input-error-message clr-error-100', $attrs.class]">
            {{ message }}
        </div>
    </transition>
</template>

<style scoped>
.input-error-message {
    font-size: 0.875rem;
    margin-block-end: 1rem;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>