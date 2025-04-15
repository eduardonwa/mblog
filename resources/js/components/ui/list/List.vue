<script setup>
defineProps({
    type: {
        type: String,
        default: 'default'
    }
});
</script>

<template>
    <article class="list-container border-radius-1">
        <div class="list-content">
            <slot></slot>
        </div>

        <div class="list-footer">
            <slot name="footer"></slot>
        </div>
    </article>
</template>