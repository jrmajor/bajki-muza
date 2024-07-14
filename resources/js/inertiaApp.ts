import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { mount, type Component } from 'svelte';

createInertiaApp({
	title: (title) => `${title} - Bajki Polskich Nagrań „Muza”`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob<Component>('./Pages/**/*.svelte')),
	setup({ el, App, props }) {
		mount(App, { target: el, props });
	},
	progress: {
		color: '#ffcc00',
	},
});
