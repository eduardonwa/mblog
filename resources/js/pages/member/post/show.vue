<script setup lang="ts">
import { Post, Comment, MentionableUser, Channel } from '@/types';
import { BlogPostProps } from '@/components/ui/blog-post';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import LikeButton from '@/components/LikeButton.vue';
import ShareMenu from '@/components/ui/share-menu/ShareMenu.vue';
import ReportModal from '@/components/ReportModal.vue';
import DOMPurify from 'dompurify';

const props = defineProps<{
    post: Post;
    comments: Comment[];
    channel: Channel;
    mentionableUsers?: BlogPostProps['mentionableUsers'];
    url: string;
}>();

const mentionableUsersArr = computed<MentionableUser[]>(() => 
  Array.isArray(props.mentionableUsers) ? props.mentionableUsers : []
);

const localPost = ref<Post>(props.post);

// cosas para que el reportmodal se dispare
const isMobile = ref(window.innerWidth < 1280);
function onResize() {
  isMobile.value = window.innerWidth < 1280;
}
onMounted(() => window.addEventListener('resize', onResize));
onUnmounted(() => window.removeEventListener('resize', onResize));

DOMPurify.addHook('uponSanitizeElement', (node, data) => {
  if (data.tagName === 'iframe') {
    const src = node.getAttribute('src') || ''
    if (
      src.includes('bandcamp.com/EmbeddedPlayer') ||
      src.includes('youtube.com/embed')
    ) {
      node.setAttribute('allow', 'autoplay; encrypted-media')
      node.setAttribute('allowfullscreen', 'true')
      node.setAttribute('loading', 'lazy')
    } else {
      node.parentNode?.removeChild(node)
    }
  }
})

DOMPurify.setConfig({
  ADD_TAGS: ['iframe'],
  ADD_ATTR: [
    'allow',
    'allowfullscreen',
    'frameborder',
    'scrolling',
    'src',
    'height',
    'width',
    'style',
    'seamless',
    'loading'
  ],
})

const sanitizedHtml = computed(() => {
  return props.post.list_data_html
    ? DOMPurify.sanitize(props.post.list_data_html)
    : ''
})

</script>

<template>
    <SiteLayout>
        <section class="post-member container" data-type="blog-post">
            <header class="post-header">
                <h2 class="title">{{ post.title }}</h2>
                <div class="meta-primary">
                    <Link
                        :href="route('author.posts', {user: post.user?.username})"
                        class="author"
                    >
                        {{ post?.user?.username || 'Rattlehead' }}
                    </Link>
                    <span class="date">{{ post.smart_date }}</span>
                    <Link
                        :href="route('channel.show', {slug: channel.slug})"
                        class="channel"
                    >
                        {{ post.channel?.name }}
                    </Link>
                </div>

                <div class="desktop-interactions-wrapper">
                    <LikeButton
                        :post="localPost"
                        class="stick-this"
                        @update:post="updatedPost => localPost = updatedPost"
                    />
                    <ShareMenu class="share-menu share-menu--desktop" :url="props.url" variant="desktop" />
                    <ReportModal
                        v-if="!isMobile"
                        class="repport-wrapper"
                        :reportable="{id: post.id, type: 'post'}"
                        :popoverId="`reportPopover-desktop-${post.id}`"
                    />
                </div>
            </header>
            
            <!-- body -->
            <article class="post-body">
                <div v-if="sanitizedHtml" v-html="sanitizedHtml"></div>
                <div v-else v-html="post.body"></div>

                <CommentForm :post="post" />
                <CommentBox
                    :post="post"
                    :comments="comments"
                    :users="mentionableUsersArr"
                    :depth="0"
                />
            </article>

            <!-- interacciones -->
            <footer class="mobile-interactions-wrapper">
                <LikeButton
                    :post="localPost"
                    @update:post="(newPost) => localPost = newPost"
                />
                <ShareMenu class="share-menu share-menu--mobile" :url="props.url" variant="mobile"/>
                <ReportModal
                    class="report"
                    v-if="isMobile"
                    :reportable="{ id: post.id, type: 'post' }"
                    :popoverId="`reportPopover-mobile-${post.id}`"
                />
            </footer>
        </section>
    </SiteLayout>
</template>