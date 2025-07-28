import '../styles/main.scss';
import './tiptap/extensions.js';

import type { DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { initializeTheme } from './composables/useAppearance';
import gsap from 'gsap';
import directives from './directives'
import ScrollTrigger from 'gsap/ScrollTrigger';
import AppNavigation from './components/Navigation/AppNavigation.vue';

gsap.registerPlugin(ScrollTrigger);

/// <reference types="vite/client" />

declare global {
    interface Window {
        Inertia: {
            shouldPreserveScroll: () => boolean;
            on: (event: string, callback: (...args: any[]) => void) => void;
        };
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'sickofmetal';

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
            // directives
            Object.entries(directives).forEach(([name, def]) => {
                app.directive(name, def);
            });

        app.mount(el);
    },
    progress: {
        color: '#BFF625',
        showSpinner: false
    },
});

initializeTheme();