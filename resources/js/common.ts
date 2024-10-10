import type { ResolvedComponent } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Layout from '@/Layouts/Layout.svelte';

export async function resolve(name: string): Promise<ResolvedComponent> {
	const page = await resolvePageComponent(
		`./Pages/${name}.svelte`,
		import.meta.glob<ResolvedComponent>('./Pages/*/*.svelte'),
	);
	// @ts-expect-error
	return { default: page.default, layout: Layout };
}
