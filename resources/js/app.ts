import '../styles/main.scss';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { initializeTheme } from './composables/useAppearance';
import AppNavigation from './components/AppNavigation.vue';

/// <reference types="vite/client" />

// Extiende solo lo que necesites adicionalmente
interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;
    // Agrega aquí otras variables de entorno personalizadas si las tienes
}

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

        app.config.globalProperties.$preserveScroll = (callback: () => void) => {
            const scrollPosition = window.scrollY;
            document.documentElement.style.overflow = 'hidden';
            
            const restoreScroll = () => {
                window.scrollTo({ top: scrollPosition, behavior: 'instant' });
                document.documentElement.style.overflow = '';
            };

            try {
                return callback();
            } finally {
                requestAnimationFrame(restoreScroll);
            }
        };

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
        showSpinner: false
    },
});

initializeTheme();