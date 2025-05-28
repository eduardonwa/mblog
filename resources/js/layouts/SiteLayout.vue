<script setup lang="ts">
  import { Head, usePage } from '@inertiajs/vue3';
  import { computed } from 'vue';
  import AppFooter from '@/components/AppFooter.vue';
  
  const categories = computed(() => usePage().props.categories);
</script>

<template>
  <div>
    <Head>
      <title v-if="$page.props.meta?.title">{{ $page.props.meta.title }}</title>
      <title v-else>s1ck0fm3t4L</title>
      <meta name="description" content="Heavy Metal Blog">
      <meta name="csrf-token" :content="$page.props.csrf_token">
      <link rel="icon" href="/favicon.ico">
      <!-- <link rel="preconnect" href="https://rsms.me/" />
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css" /> -->
    </Head>

    <AppNavigation :categories="categories" />

    <main>
      <slot />
    </main>

    <AppFooter />
  </div>
</template>