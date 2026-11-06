import type { Component } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import type { ResolvedComponent } from '@inertiajs/svelte';
import { BROWSER } from 'esm-env';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Layout from '@/Layouts/Layout.svelte';
import { Ziggy } from './ziggy/index.js';

if (BROWSER && window.config.posthogToken) {
	const token = window.config.posthogToken;

	// load posthog in a separate chunk, so it doesn't block the app
	void import('posthog-js').then(({ default: posthog }) => {
		posthog.init(token, {
			api_host: 'https://eu.i.posthog.com',
			defaults: '2026-08-30',
		});
	});
}

globalThis.Ziggy = Ziggy;

createInertiaApp({
	async resolve(name: string) {
		const page = await resolvePageComponent(
			`./Pages/${name}.svelte`,
			import.meta.glob<ResolvedComponent>('./Pages/*/*.svelte'),
		);

		return { ...page, layout: Layout as Component };
	},
	progress: {
		color: '#ffcc00',
	},
});
