import { type Component } from 'svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Layout from '@/Layouts/Layout.svelte';

export async function resolve(name: string) {
	const page = await resolvePageComponent(
		`./Pages/${name}.svelte`,
		import.meta.glob<{ default: Component }>('./Pages/*/*.svelte'),
	);
	return { default: page.default, layout: Layout };
}
