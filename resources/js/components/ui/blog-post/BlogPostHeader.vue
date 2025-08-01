<script setup lang="ts">
import { inject, onMounted, onUnmounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import LikeButton from '@/components/LikeButton.vue';
import type { BlogPostProps } from './index';
import ToggleIcon from '../icons/ToggleIcon.vue';
import ShareMenu from '../share-menu/ShareMenu.vue';
import ReportModal from '@/components/ReportModal.vue';

const props = defineProps<{ url: string }>()

// Obtener datos del post mediante inyección
const post = inject('postData') as BlogPostProps['post'];
const layoutState = inject('layoutState') as {
  state: { value: 'expanded' | 'collapsed' };
  toggle: () => void;
};

// Estado local para likes
const localPost = ref({ ...post });

const isMobile = ref(window.innerWidth < 1280);
function onResize() {
  isMobile.value = window.innerWidth < 1280;
}
onMounted(() => window.addEventListener('resize', onResize));
onUnmounted(() => window.removeEventListener('resize', onResize));
</script>

<template>
  <section
    class="blog-post__header"
    grid-area="header"
  >
    <div class="post-header-sticky-wrapper">

      <header
        class="post-header container"
        data-type="blog-post"
      >
        <!-- Botón para colapsar/expandir -->
        <ToggleIcon
          size="34"
          class="toggle-media-button"
          @click="layoutState.toggle()"
        />
        <!-- Título y metadatos -->
        <article class="post-header__meta-group">
          <h1 class="post-title">{{ post?.title }}</h1>

          <div class="meta-primary">
            <div class="no-decor">
              by
              <Link
                class="author"
                :href="post.user ? route('author.posts', { user: post.user }) : '/'"
              >
              {{ post.user?.username || 'Rattlehead' }}
              </Link>
            </div>

            <p class="date">{{ post?.smart_date }}</p>

            <Link
              class="category"
              :href="route('category.index', { slug: post.category?.slug })"
            >
              {{ post.category?.name }}
            </Link>
          </div>
        </article>
      </header>

      <section class="desktop-interactions-wrapper">
        <LikeButton
          :post="localPost"
          class="stick-this"
          @update:post="updatedPost => localPost = updatedPost"
        />
        <ShareMenu
          :url="props.url"
          class="share-menu share-menu--desktop"
          variant="desktop"
        />
        <ReportModal
          v-if="!isMobile"
          :reportable="{id: post.id, type: 'post'}"
          :popoverId="`reportPopover-desktop-${post.id}`"
        />
      </section>

    </div>
  </section>
</template>