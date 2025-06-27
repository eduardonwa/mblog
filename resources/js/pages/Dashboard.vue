<script setup lang="ts">
declare const Ziggy: any;
import { usePage, Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import type { User, Post, UserStats } from '@/types';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import DeleteIcon from '@/components/ui/icons/DeleteIcon.vue';
import EditIcon from '@/components/ui/icons/EditIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';
import ArticleCardHorizontal from '@/components/ArticleCardHorizontal.vue';

const { 
  auth: { user },
  posts,
  user_stats
} = usePage<{
  auth: { user: User | null };
  posts: Post[];
  user_stats?: UserStats;
}>().props;

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
// console.log(Ziggy.routes);
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="dashboard-user">
            <!-- info del usuario -->
            <section v-if="user">
                <Link class="dashboard-user__info | no-decor" :href="route('profile.edit')">
                    <Avatar
                        size="xl"
                        :src="user.avatar_url"
                        :alt="user.name"
                    />
                    <h2>{{ user.name }}</h2>
                </Link>
            </section>
    
            <!-- user stats -->
            <section v-if="user_stats" class="dashboard-user__stats">
                <div class="card">
                    <h2>{{ user_stats.likes_received_count }}</h2>
                    <p>Total uphails </p>
                </div>
                <div class="card">
                    <h2>{{ user_stats.comments_count }}</h2>
                    <p>Total comments</p>
                </div>
                <div class="card">
                    <h2>2,374</h2>
                    <p>Total posts views</p>
                </div>
            </section>
    
            <!-- posts del usuario -->
            <section class="dashboard-user__posts" v-if="posts">
                <div v-for="post in posts" :key="post.id">
                    <Link :href="route('post.show', { slug: post.slug })" class="dashboard-user__posts__wrapper | no-decor">
                        <div class="header">
                            <h2 class="post-title">{{ post.title }}</h2>
                        </div>

                        <div class="middle">
                            <div class="uphail-count">
                                <UphailIcon />
                                <span>{{ post.likes_count }}</span>
                            </div>
                            <div class="comment-count">
                                <CommentIcon />
                                <span>{{ post.comments_count }}</span>
                            </div>
                        </div>

                        <div class="footer">
                            <Link class="edit-post" href="#">
                                <EditIcon />
                            </Link>

                            <form class="delete-post" action="">
                                <DeleteIcon />
                            </form>
                        </div>
                    </Link>
                </div>
            </section>
        </div>
    </AppLayout>
</template>