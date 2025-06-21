import { ref, computed } from 'vue'

export function useLayoutState() {
  const layoutState = ref<'collapsed' | 'expanded'>('collapsed')
  const isCollapsed = computed(() => layoutState.value === 'collapsed')
  const toggle = () => {
    layoutState.value = layoutState.value === 'collapsed' ? 'expanded' : 'collapsed'
  }

  return { layoutState, isCollapsed, toggle }
}
