import { ref, onMounted } from 'vue'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export function useScrollSpy(selector = '.paragraph-block') {
  const activeIdx = ref<number | null>(null)

  onMounted(() => {
    document.querySelectorAll(selector).forEach(el => {
      const idx = Number(el.getAttribute('data-index'))
      ScrollTrigger.create({
        trigger: el,
        start: 'top center',
        end: 'bottom center',
        onEnter: () => activeIdx.value = idx,
        onEnterBack: () => activeIdx.value = idx,
        markers: true,
      })
    })
  })

  return { activeIdx }
}
