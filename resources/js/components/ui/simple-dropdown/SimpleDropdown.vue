<script setup lang="ts">
import { computed } from 'vue';
import UpArrow from '../icons/UpArrow.vue';
import DownArrow from '../icons/DownArrow.vue';

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
</script>

<template>
    <div class="category-dropdown-item">
        <div
            class="category-dropdown-item__header"
            @click="isOpen = !isOpen"
            role="button"
            :aria-expanded="isOpen"
        >
            <slot name="header" :isOpen="isOpen" />
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