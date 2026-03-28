import type { Component } from 'svelte';
import { createInertiaApp } from '@inertiajs/svelte';
import type { ResolvedComponent } from '@inertiajs/svelte';
import { BROWSER } from 'esm-env';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import posthog from 'posthog-js';
import Layout from '@/Layouts/Layout.svelte';
import { Ziggy } from './ziggy/index.js';

if (BROWSER && window.config.posthogToken) {
	posthog.init(window.config.posthogToken, {
		api_host: 'https://eu.i.posthog.com',
		defaults: '2025-05-24',
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
