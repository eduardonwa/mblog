<script setup lang="ts">
import { useShare } from '@/composables/useShare';
import ShareIcon from '../icons/ShareIcon.vue';
import FacebookIcon from '../icons/social-media/FacebookIcon.vue';
import XIcon from '../icons/social-media/XIcon.vue';

const props = defineProps<{
    url: string;
    variant?: 'desktop' | 'mobile';
}>()

const { showMenu, copyLink, openShare, shareOnFacebook } = useShare(props.url);
</script>

<template>
    <article class="share-wrapper">
        <ShareIcon color="#fff" @click="showMenu = !showMenu" />

        <div v-if="showMenu" :class="['share-menu', `share-menu--${variant}`]">
            <div class="button fw-bold" data-type="ghost" @click="copyLink" style="text-transform: uppercase;">url</div>
            <div class="button" data-type="ghost" @click="shareOnFacebook()">
                <FacebookIcon />
            </div>
            <div class="button" data-type="ghost" @click="openShare(`https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}`)">
                <XIcon />
            </div>
        </div>

    </article>
</template>