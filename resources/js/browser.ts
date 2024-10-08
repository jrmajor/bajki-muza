import { mount } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import { resolve } from './common';

// @ts-expect-error
document.startViewTransition ??= (cb: () => void) => cb();

createInertiaApp({
	resolve,
	setup({ el, App, props }) {
		// @ts-expect-error
		globalThis.Ziggy = props.initialPage.props.routes;

		// @ts-expect-error
		mount(App, { target: el, props });
	},
	progress: {
		color: '#ffcc00',
	},
});
