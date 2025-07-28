// composables/useMediaAnimation.ts
import { ref, watch, onMounted, type ComponentPublicInstance } from 'vue'
import gsap from 'gsap'

type MediaItem = { index: number; src: string; type: string }

export function useMediaAnimation(items: MediaItem[], activeIndex: () => number | null) {
  const mediaEls = ref<(HTMLElement | null)[]>([])

  function setMediaEl(i: number) {
    return (el: Element | ComponentPublicInstance | null) => {
      if (el instanceof HTMLElement) {
        mediaEls.value[i] = el
      } else {
        mediaEls.value[i] = null
      }
    }
  }

  onMounted(() => {
    gsap.set(mediaEls.value, { autoAlpha: 0, y: 50 })
  })

  watch(activeIndex, idx => {
    gsap.to(mediaEls.value, { autoAlpha: 0, y: 50, duration: 0.3 })

    if (idx !== null) {
      const el = mediaEls.value[ items.findIndex(m => m.index === idx) ]
      if (el) {
        gsap.to(el, { autoAlpha: 1, y: 0, duration: 0.6, ease: 'power3.out' })
      }
    }
  })

  return {
    mediaEls,
    setMediaEl,
  }
}
