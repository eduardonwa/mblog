<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import type { SingleChannelProps } from '@/types'
import ChannelHeader from '@/components/ChannelHeader.vue';

const props = defineProps<SingleChannelProps>()
</script>

<template>
    <SiteLayout>
        <section class="container">
            
            <header>
                <ChannelHeader :channel="props.channel" />
            </header>

            <article>

            </article>
        </section>
    </SiteLayout>
</template>