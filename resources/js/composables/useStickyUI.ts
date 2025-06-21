import { ref, onMounted, onUnmounted } from 'vue'

export function useStickyUI(threshold = 100) {
  const shouldShow = ref(false)
  const lastScroll = ref(0)

  const handleScroll = () => {
    const current = window.scrollY || window.pageYOffset
    shouldShow.value = current > lastScroll.value && current > threshold
    lastScroll.value = current
  }

  onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }))
  onUnmounted(() => window.removeEventListener('scroll', handleScroll))

  return { shouldShow }
}
