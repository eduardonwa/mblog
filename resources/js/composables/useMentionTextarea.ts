import { ref, nextTick, type Ref } from 'vue';

export function useMentionTextarea(initialValue = '') {
  const text = ref(initialValue);
  const textareaRef = ref<HTMLTextAreaElement | null>(null);
  const showSuggestions = ref(false);
  const filteredUsers = ref<{ id: number; username: string }[]>([]);
  const lastWord = ref('');

  const focusTextarea = () => {
    if (textareaRef.value) {
      textareaRef.value.focus();
      textareaRef.value.setSelectionRange(
        textareaRef.value.value.length,
        textareaRef.value.value.length
      );
    }
  };

  const updateValue = (value: string) => {
    text.value = value;
  };

  const handleInput = (users?: { id: number; username: string }[]) => {
    if (!users || users.length === 0) {
      showSuggestions.value = false;
      return;
    }

    const words = text.value.split(/\s+/);
    lastWord.value = words[words.length - 1];

    if (lastWord.value.startsWith('@')) {
      const search = lastWord.value.slice(1).toLowerCase();
      filteredUsers.value = users.filter(user =>
        user.username.toLowerCase().includes(search)
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
    showSuggestions.value = false;
  };

  return {
    text,
    textareaRef,
    showSuggestions,
    filteredUsers,
    focusTextarea,
    updateValue,
    handleInput,
    selectMention,
  };
}
