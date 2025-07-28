import { computed } from 'vue'

export function usePostBlocks(body: string) {
  const blocks = computed(() =>
    body
      .split(/<\/p>/)
      .filter(Boolean)
      .map((frag, i) => ({
        html: frag + '</p>',
        origIdx: i,
        hasMedia: /<(img|iframe)/.test(frag),
      }))
  )

  const textBlocks = computed(() =>
    blocks.value
      .filter(b => !b.hasMedia)
      .map((b, i) => ({ html: b.html, origIdx: b.origIdx, seqIdx: i }))
  )

  const mediaItems = computed(() => {
    const items: Array<{ index: number; src: string; type: string }> = []
    blocks.value
      .filter(b => b.hasMedia)
      .forEach(b => {
        const nextTb = textBlocks.value.find(tb => tb.origIdx > b.origIdx)
        const seq = nextTb ? nextTb.seqIdx : textBlocks.value.length - 1
        const match = b.html.match(/src="([^"]+)"/)
        if (!match) return
        items.push({
          index: seq,
          src: match[1],
          type: b.html.includes('<img') ? 'image' : 'video'
        })
      })
    return items
  })

  return { blocks, textBlocks, mediaItems }
}
