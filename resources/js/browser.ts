import { mount, hydrate } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import { resolve } from './common';

// @ts-expect-error
document.startViewTransition ??= (cb: () => void) => cb();

createInertiaApp({
	resolve,
	setup({ el, App, props }) {
		if (document.querySelector('[data-server-rendered]')) {
			// @ts-expect-error
			hydrate(App, { target: el, props });
		} else {
			// @ts-expect-error
			mount(App, { target: el, props });
		}
	},
	progress: {
		color: '#ffcc00',
	},
});
