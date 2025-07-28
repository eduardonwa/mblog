<script setup lang="ts">
import { ref, onMounted, nextTick, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  endpoint: { type: String, required: true },
  dataKey: { type: String, default: 'data' },
  extractNextPage: { type: Function, default: (res: any) => res.next_page_url },
  initialItems: { type: Array, default: () => [] },
  initialNextPage: { type: String, default: null },
});

const emit = defineEmits(['loaded']);

const items = ref(props.initialItems);
const nextPageUrl = ref(props.initialNextPage || props.endpoint);
const loading = ref(false);
const landmark = ref(null);

const loadMore = async () => {
  if (!nextPageUrl.value || loading.value) return;
  loading.value = true;

  try {
    const res = await axios.get(nextPageUrl.value);
    const newItems = res.data[props.dataKey] || [];
    nextPageUrl.value = props.extractNextPage(res.data);
    items.value = [...new Map([...items.value, ...newItems].map(item => [item.id, item])).values()];
    emit('loaded', newItems);
  } catch (error) {
    console.error('Error en scroll infinito:', error);
  } finally {
    loading.value = false;
  }
};

const observer = new IntersectionObserver(
  (entries) => {
    if (entries[0].isIntersecting) {
      loadMore();
    }
  },
  { rootMargin: '0px 0px 150px 0px' }
);

onMounted(async () => {
  await nextTick(); // Espera a que el DOM se renderice
  if (landmark.value) { // Verifica que landmark exista
    observer.observe(landmark.value);
  }
  if (props.initialItems.length === 0) {
    loadMore();
  }
});

// Limpia el observer al desmontar
onUnmounted(() => {
  observer.disconnect();
});
</script>

<template>
  <div>
    <slot :items="items" />
    <div ref="landmark" style="height: 1px;"></div> <!-- Asegura que landmark exista -->
  </div>
</template>