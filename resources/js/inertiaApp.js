import '../css/style.css';

import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { mount } from 'svelte';

createInertiaApp({
    title: (title) => `${title} - Bajki Polskich Nagrań „Muza”`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
    setup({ el, App, props }) {
        mount(App, { target: el, props })
    },
    progress: {
        color: '#ffcc00',
    },
});
