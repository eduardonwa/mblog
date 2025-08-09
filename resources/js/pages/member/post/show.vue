<script setup lang="ts">
import { Post, Comment, MentionableUser, Channel } from '@/types';
import { BlogPostProps } from '@/components/ui/blog-post';
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommentBox from '@/components/ui/comments/CommentBox.vue';
import CommentForm from '@/components/ui/comments/CommentForm.vue';
import LikeButton from '@/components/LikeButton.vue';
import ShareMenu from '@/components/ui/share-menu/ShareMenu.vue';
import ReportModal from '@/components/ReportModal.vue';
import Lightbox from '@/components/Lightbox.vue';
import RelatedPosts from '@/components/RelatedPosts.vue';
import {
  useThumbnail,
  useSanitizedHtml,
  useResponsive,
} from '@/composables/memberPostShow'


const props = defineProps<{
    post: Post;
    comments: Comment[];
    channel?: Channel;
    mentionableUsers?: BlogPostProps['mentionableUsers'];
    url: string;
}>();

const mentionableUsersArr = computed<MentionableUser[]>(() => 
  Array.isArray(props.mentionableUsers) ? props.mentionableUsers : []
);

const localPost = ref<Post>(props.post);
const { sanitizedHtml } = useSanitizedHtml(props.post);
const { hasThumbnail } = useThumbnail();
const { isMobile } = useResponsive();

const relatedPosts = computed(() => props.post?.series?.posts ?? [])

</script>

<template>
    <SiteLayout>
        <section class="post-member container" data-type="blog-post">
            <header class="post-header">
                <h2 class="title">{{ post.title }}</h2>
                <p class="extract" v-if="post.post_template === 'standard'" v-html="post?.extract"></p>
                
                <div class="meta-primary">
                    <Link
                        :href="route('author.posts', {user: post.user?.username})"
                        class="author"
                    >
                        {{ post?.user?.username || 'Rattlehead' }}
                    </Link>
                    <span class="date">{{ post.smart_date }}</span>

                    <Link
                        v-if="post.channel"
                        :href="route('channel.show', { slug: post.channel.slug })"
                        class="channel"
                    >
                        {{ post.channel?.name }}
                    </Link>

                    <Link
                        v-else-if="post.category"
                        class="category"
                        :href="route('category.index', { slug: post.category.slug })"
                    >
                        {{ post.category?.name }}
                    </Link>
                </div>

                <article class="blog-post__body__subheader img-standard-template | flow">
                    <Lightbox v-if="hasThumbnail(post.thumbnail_urls)" :post="post"/>
                </article>
            </header>
            
            <!-- body -->
            <article class="post-body">
                <div v-if="sanitizedHtml" v-html="sanitizedHtml"></div>
                <div v-else v-html="post.body"></div>

                <div class="desktop-interactions-wrapper">
                <LikeButton
                    :post="localPost"
                    class="stick-this"
                    @update:post="(updatedPost: Post) => localPost = updatedPost"
                />
                <ShareMenu class="share-menu share-menu--desktop" :url="props.url" variant="desktop" />
                <ReportModal
                    v-if="!isMobile"
                    class="repport-wrapper"
                    :reportable="{id: post.id, type: 'post'}"
                    :popoverId="`reportPopover-desktop-${post.id}`"
                />
                </div>

                <hr class="straight-large">

                <RelatedPosts :related-posts="relatedPosts" />

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
                    @update:post="(newPost: Post) => localPost = newPost"
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