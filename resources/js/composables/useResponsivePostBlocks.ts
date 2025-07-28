import { shallowRef, ref } from 'vue'
import { usePostBlocks } from './usePostBlocks'

interface Block {
  html: string;
  origIdx: number;
  hasMedia: boolean;
}
interface TextBlock {
  html: string;
  seqIdx: number;
  origIdx?: number;
}
interface MediaItem {
  index: number;
  src: string;
  type: string;
}

export function useResponsivePostBlocks(postBody: string) {
  const isDesktop = ref(false)
  const blocks = shallowRef<Block[]>([])
  const textBlocks = shallowRef<TextBlock[]>([])
  const mediaItems = shallowRef<MediaItem[]>([])
  const rawBody = shallowRef<string>('')

  const updateLayout = () => {
    isDesktop.value = window.innerWidth >= 1280

    if (isDesktop.value) {
      const postData = usePostBlocks(postBody)
      blocks.value = postData.blocks.value
      textBlocks.value = postData.textBlocks.value
      mediaItems.value = postData.mediaItems.value
      rawBody.value = ''
    } else {
      rawBody.value = postBody
      blocks.value = []
      textBlocks.value = []
      mediaItems.value = []
    }
  }

  return {
    isDesktop,
    blocks,
    textBlocks,
    mediaItems,
    rawBody,
    updateLayout,
  }
}