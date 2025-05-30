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
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

interface ThumbnailUrls {
  max: string
  lg: string
  md?: string
}

export interface Post {
    id: number;
    title: string;
    extract?: string;
    excerpt?: string;
    body?: string;
    is_liked_by_user: boolean;
    likes_count: number;
    user?: User
    author?: {
        name: string;
    };
    slug: string;
    smart_date: string;
    short_date?: string;
    category?: {
        slug: string;
        name: string;
    };
    tags?: Array<{
        id: number;
        slug: { en: string };
        name: { en: string };
    }>;
    thumbnail_urls?: ThumbnailUrls
    comments: Comment[];
    comments_count: number;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    parent_id: number | null;
    children?: Category[];
}

export interface CategoryTree extends Category {
    children: Category[];
}

export interface Meta {
    title?: string;
    description?: string;
    author?: string;
}

interface Comment {
    id: number;
    comment: string;
    user: User;
    created_at: string;
}

export {};