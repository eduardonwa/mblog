<script setup lang="ts">
import ChannelCard from '@/components/ChannelCard.vue';
import SiteLayout from '@/layouts/SiteLayout.vue';
import type { ChannelListProps } from '@/types'

const props = defineProps<ChannelListProps>()
</script>

<template>
    <SiteLayout>
        <h2 class="channels-header">Channels</h2>
        <section class="container">
            <article class="channel-cards-wrapper">
                <ChannelCard v-for="channel in props.channels" :key="channel.slug" :channel="channel" />
            </article>
        </section>
    </SiteLayout>
</template> 