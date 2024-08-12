import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { type Component } from 'svelte';

export function resolve(name: string) {
	return resolvePageComponent(
		`./Pages/${name}.svelte`,
		import.meta.glob<Component>('./Pages/**/*.svelte'),
	);
}
