<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{ 
  users?: MentionableUser[],
  modelValue: string;
}>();

const emit = defineEmits<{
  (event: 'update:modelValue', value: string): void
}>();

interface MentionableUser {
  id: number;
  name: string;
}

const text = ref(props.modelValue);
const showSuggestions = ref(false);
const filteredUsers = ref<{ id: number; name: string }[]>([]);
const lastWord = ref('');

watch(() => props.modelValue, (newValue) => {
  if (newValue !== text.value) text.value = newValue;
});

const onInput = () => {
  emit('update:modelValue', text.value);

  // Salir si no hay usuarios
  if (!props.users || props.users.length === 0) {
    showSuggestions.value = false;
    return;
  }

  const words = text.value.split(/\s+/);
  lastWord.value = words[words.length - 1];

  if (lastWord.value.startsWith('@')) {
    const search = lastWord.value.slice(1).toLowerCase();
    filteredUsers.value = props.users.filter(user =>
      user.name.toLowerCase().includes(search)
    );
    showSuggestions.value = filteredUsers.value.length > 0;
  } else {
    showSuggestions.value = false;
  }
};

const selectMention = (name: string) => {
  const words = text.value.split(/\s+/);
  words[words.length - 1] = `@${name}`;
  text.value = words.join(' ') + ' ';
  emit('update:modelValue', text.value);
  showSuggestions.value = false;
};

const textareaRef = ref<HTMLTextAreaElement | null>(null);

const focusTextarea = () => {
  if (textareaRef.value) {
    textareaRef.value.focus();
    // Mover el cursor al final del texto
    textareaRef.value.setSelectionRange(
      textareaRef.value.value.length,
      textareaRef.value.value.length
    );
  }
};

// Exponer el método para que pueda ser llamado desde el padre
defineExpose({
  focusTextarea
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
        @click="selectMention(user.name)"
        class="suggestions__item"
      >
        {{ user.name }}
      </li>
    </ul>
  </div>
</template>