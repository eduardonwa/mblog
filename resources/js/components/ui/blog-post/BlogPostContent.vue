<script setup lang="ts">
import { computed, inject } from 'vue';
import { Head } from '@inertiajs/vue3';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import Lightbox from '@/components/Lightbox.vue';
import type { BlogPostProps } from './index';
import { MentionableUser } from '@/types';

// Obtener datos mediante inyección
const props = defineProps<{
  comments: BlogPostProps['comments'];
  mentionableUsers?: BlogPostProps['mentionableUsers'];
  meta?: BlogPostProps['meta'];
}>();

const post = inject('postData') as BlogPostProps['post'];

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
    <Head>
      <title>{{ meta?.title }}</title>
      <meta name="description" :content="meta?.description">
      <meta name="user" :content="meta?.author">
    </Head>

    <article class="blog-post__body__subheader | flow">
      <Lightbox :post="post"/>
      <p class="extract" v-html="post?.extract"></p>
      <div v-html="post?.body"></div>
    </article>

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