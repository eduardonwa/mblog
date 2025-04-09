<script setup lang="ts">
  import NavigationMenu from '@/components/ui/navigation-menu/NavigationMenu.vue';
import { Head, Link } from '@inertiajs/vue3'; // ¡Asegúrate de importar Link!
</script>

<template>
  <div>
    <Head>
      <title v-if="$page.props.meta?.title">{{ $page.props.meta.title }}</title>
      <title v-else>s1ck0fm3t4L</title>
      <meta name="description" content="Descripción general del sitio">
      <meta name="csrf-token" :content="$page.props.csrf_token">
      <link rel="icon" href="/favicon.ico">
      <!-- Agrega las fuentes de Inter aquí para que estén disponibles globalmente -->
      <link rel="preconnect" href="https://rsms.me/" />
      <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <AppNavigation />

    <main>
      <slot />
    </main>
  </div>
</template>