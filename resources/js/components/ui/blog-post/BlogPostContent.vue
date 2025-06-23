<script setup lang="ts">
import { computed, inject } from 'vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import Lightbox from '@/components/Lightbox.vue';
import ParagraphBlock from '@/components/ParagraphBlock.vue';
import type { BlogPostProps } from './index';
import { MentionableUser } from '@/types';
import BlogPostMeta from './BlogPostMeta.vue';

// Obtener datos mediante inyección
const props = defineProps<{
  comments: BlogPostProps['comments'];
  mentionableUsers?: BlogPostProps['mentionableUsers'];
  meta?: BlogPostProps['meta'];
  textBlocks: Array<{ html: string; seqIdx: number }>;
  rawBody?: string;
  isDesktop?: boolean;
}>();

const post = inject('postData') as BlogPostProps['post'];
// const textBlocks = inject('textBlocks') as Array<{ html: string; seqIdx: number }>

const mentionableUsersArr = computed<MentionableUser[]>(() =>
  Array.isArray(props.mentionableUsers) ? props.mentionableUsers : []
);
</script>

<template>
  <section
    class="blog-post__body | container"
    data-type="blog-post"
    data-align="start"
    grid-area="content"
  >
    <BlogPostMeta :meta="props.meta" />

    <article class="blog-post__body__subheader | flow">
      <Lightbox :post="post"/>
      <p class="extract" v-html="post?.extract"></p>
    </article>

    <div v-if="isDesktop">
      <ParagraphBlock
        v-for="block in props.textBlocks"
        :key="block.seqIdx"
        :html="block.html"
        :index="block.seqIdx"
        :is-desktop="isDesktop"
      />
    </div>
    
    <div v-else v-html="props.rawBody" />
    <hr class="straight-large">
    
    <article class="blog-post__comments">
      <CommentForm :post="post" />
      <CommentBox
        :post="post"
        :comments="comments"
        :users="mentionableUsersArr"
        :depth="0"
      />
    </article>
  </section>
</template>