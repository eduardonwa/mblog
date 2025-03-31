<template>
    <div>
        <Head>
            <title v-if="$page.props.meta?.title">{{ $page.props.meta.title }}</title>
            <title v-else>s1ck0fm3t4L</title>
            <meta name="description" content="Descripción general del sitio">
            <meta name="csrf-token" :content="$page.props.csrf_token">
            <link rel="icon" href="/favicon.ico">
        </Head>

        <header v-if="$page.props.auth.user">
            <!-- navbar -->
        </header>
        
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
    import { Head } from '@inertiajs/vue3';
</script>