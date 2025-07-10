<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  categoryId: number
  modelValue: boolean
}>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const toggle = () => {
  isOpen.value = !isOpen.value;
};
</script>

<template>
    <div class="category-dropdown-item">
        <div
            class="category-dropdown-item__header"
            @click="toggle"
            role="button"
            :aria-expanded="isOpen"
        >
            <slot name="header" :isOpen="isOpen" :toggle="toggle" />
        </div>

        <div
            v-show="isOpen"
            class="category-dropdown-item__content"
            role="region"
        >
            <slot name="content" />
        </div>
    </div>
</template>