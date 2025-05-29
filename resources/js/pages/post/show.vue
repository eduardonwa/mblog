<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import LikeButton from '@/components/LikeButton.vue';
import { ref, computed } from 'vue'
import Avatar from '@/components/ui/avatar/Avatar.vue';
import ReplyIcon from '@/components/ui/icons/ReplyIcon.vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';

const page = usePage();

const categories = computed(() => page.props.categories);

const { post, meta } = defineProps({
  post: Object,
  meta: Object,
});

const localPost = ref({ ...post });
const openLightbox = ref(false);
</script>

<template>
  <SiteLayout :categories="categories">

    <Head>
      <title>{{ meta?.title }}</title>
      <meta
        name="description"
        :content="meta?.description"
      >
      <meta
        name="user"
        :content="meta?.user"
      >
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
                  :href="route('author.posts', post?.user?.name)"
                >
                {{ post?.user?.name || 'Rattlehead' }}
                </Link>
              </div>


              <p class="date">{{ post?.smart_date }}</p>

              <Link
                class="category"
                :href="route('category.index', post?.category.slug)"
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
              :href="route('tag.show', tag.slug.en)"
            >
            #{{ tag.name.en }}
            </Link>
          </div>

          <!-- uphail post -->
          <!-- <div class="uphail-post-wrapper">
              <LikeButton
                  variant="mobile"
                  :post="localPost"
                  class="stick-this"
                  @update:post="updatedPost => localPost = updatedPost" 
              />
          </div> -->
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
          <picture
            class="image"
            @click="openLightbox = true"
          >
            <source
              media="(min-width: 1536px)"
              :srcset="post?.thumbnail_urls?.max"
            >
            <source
              media="(min-width: 1280px)"
              :srcset="post?.thumbnail_urls?.lg"
            >
            <img
              :src="post?.thumbnail_urls?.lg"
              :alt="post?.title"
              class="post-thumbnail"
              loading="lazy"
            >
          </picture>

          <div
            v-if="openLightbox"
            class="lightbox"
            @click.self="openLightbox = false"
          >
            <button
              class="close-btn"
              @click="openLightbox = false"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
              </svg>
            </button>
            <img
              :src="post?.thumbnail_urls?.max"
              :alt="post?.title"
              class="lightbox-image"
            >
          </div>
          <p class="extract">{{ post?.extract }}</p>
        </article>

        <article v-html="post?.body"></article>

        <hr class="straight-large">
        <!-- comentarios -->
        <article class="blog-post__comments">
          <!-- Formulario para comentar -->
          <CommentForm :post="post" />
          <!-- contenedor general de comentarios -->
          <CommentBox :post="post" />
        </article>
      </section>
    </main>
  </SiteLayout>
</template>