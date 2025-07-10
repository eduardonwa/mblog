export default {
  beforeMount(el: HTMLElement, binding: any) {
    const ourEl = el as HTMLElement & { __ClickAwayHandler__?: (e: MouseEvent) => void }

    ourEl.__ClickAwayHandler__ = (event: MouseEvent) => {
      if (!(el === event.target || el.contains(event.target as Node))) {
        binding.value(event)
      }
    }

    document.addEventListener('click', ourEl.__ClickAwayHandler__)
  },

  unmounted(el: HTMLElement) {
    const ourEl = el as HTMLElement & { __ClickAwayHandler__?: (e: MouseEvent) => void }

    if (ourEl.__ClickAwayHandler__) {
      document.removeEventListener('click', ourEl.__ClickAwayHandler__)
      delete ourEl.__ClickAwayHandler__
    }
  },
}
