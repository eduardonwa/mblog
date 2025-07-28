<script setup lang="ts">
import { onMounted, watch } from 'vue';
import { useMentionTextarea } from '@/composables/useMentionTextarea';
import { MentionableUser } from '@/types';

const props = defineProps<{
  users?: MentionableUser[],
  modelValue: string;
}>();

const emit = defineEmits<{
  (event: 'update:modelValue', value: string): void;
}>();

const {
  text,
  textareaRef,
  focusTextarea,
  showSuggestions,
  filteredUsers,
  handleInput,
  selectMention,
  updateValue
} = useMentionTextarea(props.modelValue);

watch(() => props.modelValue, (newVal) => {
  if (newVal !== text.value) updateValue(newVal);
});

const onInput = () => {
  emit('update:modelValue', text.value);
  handleInput(props.users);
};

const handleSelectMention = (username: string) => {
  selectMention(username);
  emit('update:modelValue', text.value);
};

defineExpose({ focusTextarea });

onMounted(() => {
  focusTextarea();
});
</script>


<template>
  <div class="suggestions-wrapper">
    <textarea
      ref="textareaRef"
      v-model="text"
      @input="onInput"
      class="textarea"
      data-type="reply-box"
      placeholder="Use '@' to mention somebody"
    ></textarea>
       
    <ul 
      v-if="showSuggestions && filteredUsers.length > 0" 
      class="suggestions"
    >
      <li
        v-for="user in filteredUsers"
        :key="user.id"
        @click="selectMention(user.username)"
        class="suggestions__item"
      >
        {{ user.username }}
      </li>
    </ul>
  </div>
</template>