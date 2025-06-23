import BlogPostRoot from './BlogPostRoot.vue';
import BlogPostHeader from './BlogPostHeader.vue';
import BlogPostContent from './BlogPostContent.vue';
import BlogPostMedia from './BlogPostMedia.vue';
import { MentionableUser, Meta, Post, Comment } from '@/types/index';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

export {
    BlogPostRoot,
    BlogPostHeader,
    BlogPostContent,
    BlogPostMedia
};

export default BlogPostRoot;

const mentionableUsers = computed<MentionableUser[]>(() => {
  try {
    return (page.props.mentionableUsers as unknown as MentionableUser[]) || [];
  } catch (e) {
    return [];
  }
});

export interface BlogPostProps {
    post: Post;
    comments: Comment[];
    meta: Meta;
    mentionableUsers: MentionableUser;
    url: string;
}

export type LayoutState = 'expanded' | 'collapsed';