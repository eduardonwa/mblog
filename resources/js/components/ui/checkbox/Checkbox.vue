<script setup>
import { ref } from 'vue';

const props = defineProps({
  modelValue: Boolean,
  disabled: Boolean
});

const emit = defineEmits(['update:modelValue']);

const checked = ref(props.modelValue);

const toggleCheckbox = () => {
  if (!props.disabled) {
    checked.value = !checked.value;
    emit('update:modelValue', checked.value);
  }
};
</script>

<template>
  <button
    type="button"
    role="checkbox"
    :aria-checked="checked"
    :disabled="disabled"
    @click="toggleCheckbox"
    class="custom-checkbox"
    :class="{
      'checked': checked,
      'disabled': disabled
    }"
  >
    <span class="check-icon" v-if="checked">✓</span>
  </button>
</template>