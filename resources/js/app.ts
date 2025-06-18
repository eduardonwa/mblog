import '../styles/main.scss';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { initializeTheme } from './composables/useAppearance';
import AppNavigation from './components/Navigation/AppNavigation.vue';

/// <reference types="vite/client" />

declare global {
    interface Window {
        Inertia: {
            shouldPreserveScroll: () => boolean;
            on: (event: string, callback: (...args: any[]) => void) => void;
        };
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');
        return resolvePageComponent(`./pages/${name}.vue`, pages);
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('AppNavigation', AppNavigation);
        app.mount(el);
    },
    progress: {
        color: '#BFF625',
        showSpinner: false
    },
});

initializeTheme();