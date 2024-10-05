import { mount } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import { resolve } from './common';

// @ts-expect-error
document.startViewTransition ??= (cb: () => void) => cb();

createInertiaApp({
	resolve,
	setup({ el, App, props }) {
		mount(App, { target: el, props });
	},
	progress: {
		color: '#ffcc00',
	},
});
