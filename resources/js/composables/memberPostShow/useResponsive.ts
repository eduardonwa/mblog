import { ref, onMounted, onUnmounted } from 'vue';

export function useResponsive() {
  const isMobile = ref(window.innerWidth < 1280);

  function onResize() {
    isMobile.value = window.innerWidth < 1280;
  }
  onMounted(() => window.addEventListener('resize', onResize));
  onUnmounted(() => window.removeEventListener('resize', onResize));

  return { isMobile };
}
