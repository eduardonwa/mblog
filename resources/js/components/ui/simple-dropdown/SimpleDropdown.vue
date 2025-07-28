<script setup lang="ts">
import { computed } from 'vue';
import { onMounted, onBeforeMount, ref } from 'vue';

const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits(['update:modelValue', 'close'])

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const toggle = () => {
  isOpen.value = !isOpen.value;
};


const close = () => {
  emit('close');
};
</script>

<template>
    <div class="dropdown-item">
        <div
            class="header"
            role="button"
            :aria-expanded="isOpen"
            @click="toggle"
        >
            <slot name="header" :isOpen="isOpen" :toggle="toggle" />
        </div>

        <div
            v-if="$slots.content"
            v-show="isOpen"
            role="region"
            class="submenu"
            ref="dropdownRef"
        >
          <slot name="content" />
        </div>
    </div>
</template>