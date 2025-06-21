import { ref } from 'vue'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

interface MediaItem {
  index: number
  src: string
  type: string
}

export function useMediaScrollTrigger() {
  const activeIdx = ref<number | null>(null)

  function initTriggers(mediaItems: MediaItem[]) {
    if (window.innerWidth < 1280) return;
    
    ScrollTrigger.getAll().forEach(t => t.kill()) // limpia anteriores
    
    mediaItems.forEach((item, i) => {
      const triggerEl = document.querySelector(`[data-paragraph="${item.index}"]`);
      const nextItem = mediaItems[i + 1]
      const endTriggerEl = nextItem
        ? document.querySelector(`[data-paragraph="${nextItem.index}"]`)
        : document.body

      if (!(triggerEl instanceof HTMLElement)) return;
      

      ScrollTrigger.create({
        trigger: triggerEl,
        start: 'top center',
        endTrigger: endTriggerEl || undefined,
        end: 'top center',
        scrub: true,
        onEnter: () => activeIdx.value = item.index,
        onEnterBack: () => activeIdx.value = item.index,
        onLeave: () => {
          if (i < mediaItems.length - 1) activeIdx.value = null
        },
        onLeaveBack: () => {
          if (i > 0) activeIdx.value = null
        },
        // markers: true,
      })
    })
  }

  function destroyTriggers() {
    ScrollTrigger.getAll().forEach(trigger => trigger.kill());
  }

  return {
    activeIdx,
    initTriggers,
    destroyTriggers
  }
}
