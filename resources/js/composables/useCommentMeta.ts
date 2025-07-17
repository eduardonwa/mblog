import { computed, toRaw } from 'vue';
import { useDateFormat } from '@/composables/useDateFormat';

export function useCommentMeta(comment: any) {
  const rawComment = computed(() => toRaw(comment));

  const hasReplies = computed(() => {
    return rawComment.value.children?.length > 0;
  });

  const { shortDate } = useDateFormat();

  return {
    rawComment,
    hasReplies,
    shortDate,
  };
}