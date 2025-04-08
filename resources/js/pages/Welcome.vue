<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div>
        <header>
            <nav>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
    </div>

    
</template>
