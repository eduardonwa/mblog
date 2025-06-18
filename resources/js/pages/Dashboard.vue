<script setup lang="ts">
declare const Ziggy: any;
import { usePage, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import type { User, Post, UserStats } from '@/types';
import { Link } from '@inertiajs/vue3';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';

const { 
  auth: { user },
  posts,
  user_stats
} = usePage<{
  auth: { user: User | null };
  posts: Post[];
  user_stats?: UserStats;
}>().props;

// console.log('Datos recibidos:', user_stats);

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
        <!-- info del usuario -->
        <section v-if="user">
            <header class="user-info">
                <Avatar
                    size="lg"
                    :src="user.avatar_url"
                    :alt="user.name"
                />
                <h2>{{ user.name }}</h2>
            </header>
        </section>

        <!-- user stats -->
        <section v-if="user_stats" class="user-stats">
            <div class="stat-card">
                <h2>{{ user_stats.likes_received_count }}</h2>
                <p>Total uphails </p>
            </div>
            <div class="stat-card">
                <h2>{{ user_stats.comments_count }}</h2>
                <p>Total comments</p>
            </div>
            <div class="stat-card">
                <h2>2,374</h2>
                <p>Total posts views</p>
            </div>
        </section>

        <!-- posts del usuario -->
        <section v-if="posts" class="user-posts-wrapper">
            <div v-for="post in posts">
                <article class="user-post">
                    <header>
                        <h2>{{ post.title }}</h2>
                    </header>
                    
                    <footer>
                        <article class="post-interactions">
                            <div>
                                <div class="uphail-count">
                                    <UphailIcon
                                    />
                                    <span>{{ post.likes_count }}</span>
                                </div>

                                <div class="comment-count">
                                    <CommentIcon
                                    
                                    />
                                    <span>{{ post.comments_count }}</span>
                                </div>
                            </div>

                            <form action="">
                                delete
                            </form>
                            
                            <Link
                                href="#"
                            >
                                edit
                            </Link>
                        </article>
                    </footer>
                </article>
            </div>
        </section>

    </AppLayout>
</template>