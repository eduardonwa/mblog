<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteLayout from '@/layouts/SiteLayout.vue';
import CommunityPostCard from '@/components/CommunityFeed/CommunityPostCard.vue';
import AuthorIcon from '@/components/ui/icons/AuthorIcon.vue';
import UphailIcon from '@/components/ui/icons/UphailIcon.vue';
import CommentIcon from '@/components/ui/icons/CommentIcon.vue';

const { posts, category } = defineProps({
    posts: Object,
    category: Object
});
</script>

<template>
    <SiteLayout>
        <section class="categories-wrapper | container">
            <!-- Título de la categoría -->
            <div class="categories-wrapper__header">
                <h2 class="clr-neutral-100">{{ category?.name }}</h2>
                <p class="fs-400 clr-primary-300">{{ category?.description }}</p>
            </div>

            <!-- Lista de posts -->
            <div v-if="posts?.data.length">
                <div v-for="post in posts?.data" :key="post.id">
                    <CommunityPostCard :post="post" class="categories | no-decor">
                        <template #header="{ post }">
                            <div class="categories__header">
                                <div class="uphail-icon">
                                    <UphailIcon
                                        color="#D3D7EA"
                                        hoverColor="#1e90ff"
                                        viewBox="0 4 25 26"
                                        size="28px"
                                    ></UphailIcon>
                                    <span>{{ post.likes_count }}</span>
                                </div>

                                <div class="comment-icon">
                                    <CommentIcon
                                        color="#D3D7EA"
                                        hoverColor="#1e90ff"
                                        viewBox="0 0 29 29"
                                    ></CommentIcon>
                                    <span>2</span>
                                </div>
                            </div>
                        </template>

                        <template #middle="{ post }">
                            <div class="categories__middle">
                                <h2>
                                    {{ post.title }}
                                </h2>
                                <p>
                                    {{ post.excerpt }}
                                </p>
                            </div>
                        </template>

                        <template #footer="{ post }">
                            <div class="categories__footer">
                                <AuthorIcon
                                    color="#D3D7EA"
                                    hoverColor="#1e90ff"
                                    size="24px"
                                    style="margin-right: .4rem;"
                                />

                                <Link
                                    :href="route('author.posts', { user: post?.user?.name })"
                                    class="no-decor"
                                    aria-label="More about this author"
                                >{{ post.user?.name }}</Link>
                                <span>{{ post.smart_date }}</span>
                            </div>
                        </template>
                    </CommunityPostCard>
                    <hr class="hr-straight-medium">
                </div>
            </div>

            <div v-else>
                Nothing to see here... yet!
            </div>

            <!-- Paginación -->
            <div class="pagination-wrapper" v-if="posts?.links">
                <template
                    v-for="(link, index) in posts?.links"
                    :key="index"
                >
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        :class="{ '': link.active }"
                        class="button"
                        data-type="pagination"
                        :innerHTML="link.label"
                    />
                    <span
                        v-else
                        :innerHTML="link.label"
                    />
                </template>
            </div>
        </section>
    </SiteLayout>
</template>