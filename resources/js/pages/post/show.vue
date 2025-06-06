<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import LikeButton from '@/components/LikeButton.vue';
import { ref, computed } from 'vue'
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import Lightbox from '@/components/Lightbox.vue';
import type { Post, Comment } from '@/types/index';

const page = usePage();
const categories = computed(() => page.props.categories);

interface MentionableUser {
  id: number;
  name: string;
}

const { post, comments } = defineProps<{
  post: Post;
  comments: Comment[];
  meta?: Record<string, any>;
}>();


const mentionableUsers = computed<MentionableUser[]>(() => {
  try {
    return (page.props.mentionableUsers as unknown as MentionableUser[]) || [];
  } catch (e) {
    return [];
  }
});

const localPost = ref({ ...post });
</script>

<template>
  <SiteLayout :categories="categories">

    <Head>
      <title>{{ meta?.title }}</title>
      <meta name="description" :content="meta?.description">
      <meta name="user" :content="meta?.user">
    </Head>

    <main
      class="blog-post container"
      data-type="extra-wide"
    >
      <!-- info del post -->
      <section class="blog-post__header">
        <header
          class="post-header container"
          data-type="blog-post"
        >
          <div class="post-header__meta-group">
            <!-- titulo -->
            <div class="post-title">
              <h2>{{ post?.title }}</h2>
            </div>

            <!-- autor fecha y categoria -->
            <div class="meta-primary">
              <div class="no-decor clr-primary-300">
                by
                <Link
                  class="author"
                  :href="post?.user ? route('author.posts', { user: post.user.slug }) : '#'"
                >
                {{ post?.user?.name || 'Rattlehead' }}
                </Link>
              </div>


              <p class="date">{{ post?.smart_date }}</p>

              <Link
                class="category"
                :href="route('category.index', {slug: post?.category.slug})"
              >
              {{ post?.category.name }}
              </Link>
            </div>
          </div>

          <!-- Tags -->
          <div v-if="post?.tags?.length">
            <Link
              v-for="tag in post?.tags"
              :key="tag.id"
              :href="route('tag.show', {tag: tag.slug.en})"
            >
            #{{ tag.name.en }}
            </Link>
          </div>
        </header>

        <div class="uphail-post-wrapper">
          <LikeButton
            variant="mobile"
            :post="localPost"
            class="stick-this"
            @update:post="updatedPost => localPost = updatedPost"
          />
        </div>
      </section>

      <!-- Contenido principal -->
      <section
        class="blog-post__body | container"
        data-type="blog-post"
      >
        <!-- Imagen y extracto -->
        <article class="blog-post__body__subheader | flow">
          <Lightbox :post="post"/> 

          <p class="extract">{{ post?.extract }}</p>
        </article>

        <article v-html="post?.body"></article>

        <hr class="straight-large">
        <!-- comentarios -->
        <article class="blog-post__comments">
          <!-- Formulario para comentar -->
          <CommentForm :post="post" />
          <!-- contenedor general de comentarios -->
          <CommentBox
            :post="post"
            :comments="comments"
            :users="mentionableUsers"
          />
        </article>
      </section>
    </main>
  </SiteLayout>
</template>