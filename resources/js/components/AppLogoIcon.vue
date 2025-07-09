<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <h3 class="ff-logo fs-700 clr-neutral-100 no-decor">sickofmetal</h3>
</template>
