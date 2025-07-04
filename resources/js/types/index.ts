import type { LucideIcon } from 'lucide-vue-next';
import type { PageProps } from '@inertiajs/core';
import './types/inertia'

declare module '@inertiajs/core' {
  interface PageProps {
    [key: string]: any; // Firma de índice para propiedades dinámicas
  }
}

export interface Auth {
    user: User | null;
    roles: string[];
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
    deleted_at?: string | null;
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
    user_id: number | null;
    user: User | null;
    author: {
        name: string;
        id: number;
    };
    slug: string;
    smart_date: string;
    short_date: string;
    published_at: string;
    updated_at: string;
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
    user_id: number | null;
    commentable_type: string;
    commentable_id: number;
    is_approved: boolean;
    created_at: string;
    updated_at: string;
    comments?: Comment[];
    commentator?: User | null;
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
    id: number;
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
    url: string;
    sticker_url: ThumbnailUrls;
    created_at?: string;
    updated_at?: string;
    published_at?: string;
    posts_count?: number;
}

export interface ChannelPosts {
    data: Post[];
    next_page_url: string | null;
}

export interface ChannelListProps {
  channels: Channel[];
}

export interface SingleChannelProps {
  channel: Channel;
  posts: Post[];
}

export interface MAReleases {
  band: string;
  releaseTitle: string;
  type: string;
  genre: string;
  releaseDate: string;
  addedDate: string;
  cover: string;
  albumUrl: string;
  bandUrl: string;
}

export interface MAAlbumsResponse {
  albums: MAReleases[];
}

export type SidebarState = 'expanded' | 'collapsed' | undefined;

export {};