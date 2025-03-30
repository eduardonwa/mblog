<template>
    <div>
        <!-- Navbar simple (opcional) -->
        <header v-if="$page.props.auth.user">
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>