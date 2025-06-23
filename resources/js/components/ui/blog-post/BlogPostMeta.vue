<script setup lang="ts">
import { BlogPostProps } from '.';
import { Head } from '@inertiajs/vue3';

const { meta } = defineProps<{ meta?: BlogPostProps['meta'] }>();
console.log("🧠 META:", meta);
</script>

<template>
    <Head>
      <!-- Básicos para SEO -->
      <title>{{ meta?.title }}</title>
      <meta name="description" :content="meta?.extract" />
      <meta name="author" :content="meta?.author" />

      <!-- Open Graph (Facebook, WhatsApp, LinkedIn...) -->
      <meta property="og:title" :content="meta?.title" />
      <meta property="og:description" :content="meta?.extract" />
      <meta property="og:image" :content="meta?.thumbnail" />
      <meta property="og:url" :content="meta?.url" />
      <meta property="og:type" content="article" />
      <meta property="og:locale" content="es_MX" />

      <!-- Twitter Cards -->
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" :content="meta?.title" />
      <meta name="twitter:description" :content="meta?.extract" />
      <meta name="twitter:image" :content="meta?.thumbnail" />
    </Head>
</template>