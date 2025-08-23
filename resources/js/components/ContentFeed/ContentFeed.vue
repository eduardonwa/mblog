<script setup lang="ts">
import { BaseItem, NewsItem, Paginated, VideoItem } from '@/types';
import { computed, ref } from 'vue';
import FeedList from './FeedList.vue';
import ViewIcon from '../ui/icons/ViewIcon.vue';
import ListenIcon from '../ui/icons/ListenIcon.vue';

const props = withDefaults(defineProps<{
  readFeed:   Paginated<NewsItem>;
  listenFeed: Paginated<VideoItem>;
  defaultTab?: 'read' | 'listen';
}>(), {
  defaultTab: 'read',
});

const activeTab = ref<'read'|'listen'>('read');
const readItems   = computed<BaseItem[]>(() => props.readFeed?.data ?? []);
const listenItems = computed<BaseItem[]>(() => props.listenFeed?.data ?? []);
const show = (tab: 'read'|'listen') => { activeTab.value = tab; };
</script>

<template>
    <div class="content-feed">
        <nav class="content-feed-header">
            <h2 class="uppercase clr-accent-200">the metal machine</h2>
            <div class="content-feed-header__navigation">
                
                <div
                    class="button"
                    @click="show('read')"
                    :aria-pressed="activeTab==='read'"
                >
                    <ViewIcon /> READ
                </div>
                
                <div
                    class="button"
                    @click="show('listen')"
                    :aria-pressed="activeTab==='listen'"
                >
                    <ListenIcon/> LISTEN
                </div>
            </div>
        </nav>

        <FeedList
            v-if="activeTab==='read'"
            :items="readItems"
            heading="READ"
            poweredBy="Official Feeds / Sitemaps"
        />
        
        <FeedList
            v-else
            :items="listenItems"
            heading="LISTEN"
            poweredBy="YouTube RSS"
        />
    </div>
</template>