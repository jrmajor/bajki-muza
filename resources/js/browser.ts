import { hydrate, mount } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import posthog from 'posthog-js';
import { resolve } from './common';

posthog.init(window.config.posthogToken, {
	api_host: 'https://eu.i.posthog.com',
	defaults: '2025-05-24',
});

// @ts-expect-error
document.startViewTransition ??= (cb: () => void) => cb();

createInertiaApp({
	resolve,
	setup({ el, App, props }) {
		if ((el as HTMLElement).dataset.serverRendered) {
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
