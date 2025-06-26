import type { LucideIcon } from 'lucide-vue-next';
import type { PageProps } from '@inertiajs/core';
import './types/inertia'

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export interface User {
    id: number;
    name: string;
    slug: string;
    email: string;
    avatar?: string;
    avatar_url?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface UserStats {
  posts_count: number;
  likes_received_count: number;
  comments_count: number;
}

export interface ProfileForm {
    [key: string]: any;
    name: string;
    email: string;
    avatar: File | null;
    avatarPreview: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

interface ThumbnailUrls {
  max: string
  lg: string
  md?: string
  sm: string;
}

export interface Post {
    id: number;
    title: string;
    extract: string;
    excerpt: string;
    body: string;
    is_liked_by_user: boolean;
    likes_count: number;
    user: User
    author: {
        name: string;
        id: number;
    };
    slug: string;
    smart_date: string;
    short_date: string;
    category: {
        slug: string;
        name: string;
    };
    tags: Array<{
        id: number;
        slug: { en: string };
        name: { en: string };
    }>;
    thumbnail_urls?: ThumbnailUrls
    comments?: Comment[];
    comments_count: number;
}

interface ShowPost extends Post {
    title: string;
    body: string;
    thumbnails_urls: ThumbnailUrls;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
    parent_id: number | null;
    children?: Category[];
}

export interface CategoryTree extends Category {
    children: Category[];
}

export interface Meta {
    title: string;
    extract: string;
    author: string;
    url: string;
    thumbnail: string;
}

export interface Comment {
    replies: any;
    id: number;
    comment: string;
    user_id: number;
    commentable_type: string;
    commentable_id: number;
    is_approved: boolean;
    created_at: string;
    updated_at: string;
    comments?: Comment[];
    commentator?: User;
    children: Comment[];
    _lft?: number;
    _rgt?: number;
}

export interface MentionableUser {
  id: number;
  name: string;
  avatar: string;
}

export interface Channel {
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
    url: string;
    sticker_url: ThumbnailUrls;
}

export interface ChannelListProps {
  channels: Channel[];
}

export interface SingleChannelProps {
  channel: Channel;
}
export type SidebarState = 'expanded' | 'collapsed' | undefined;

export {};