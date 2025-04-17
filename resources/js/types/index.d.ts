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

export interface Post {
    id: number;
    title: string;
    extract?: string;
    body?: string;
    is_liked_by_user: boolean;
    likes_count: number;
    author: {
        name: string;
    };
    smart_date: string;
    category: {
        slug: string;
        name: string;
    };
    tags?: Array<{
        id: number;
        slug: { en: string };
        name: { en: string };
    }>;
    thumbnail_urls?: {
        max?: string;
        lg?: string;
    };
}

export interface Meta {
    title?: string;
    description?: string;
    author?: string;
}

export {};