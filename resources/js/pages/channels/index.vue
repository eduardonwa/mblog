<script setup lang="ts">
import ChannelCard from '@/components/ChannelCard.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import type { ChannelListProps } from '@/types'

const props = defineProps<ChannelListProps>()
</script>

<template>
    <SiteLayout>
        <section class="container margin-block-start-10">
            <article class="channel-cards-wrapper">
                <h2>Channels</h2>
                <ChannelCard v-for="channel in props.channels" :key="channel.slug" :channel="channel" />
            </article>
        </section>
    </SiteLayout>
</template>