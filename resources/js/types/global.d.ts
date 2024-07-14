declare module '@inertiajs/svelte' {
	import type { Action } from 'svelte/action';
	import { Router, VisitOptions } from '@inertiajs/core';
	export const inertia: Action<HTMLAnchorElement, VisitOptions | undefined>;
	export const router: Router;
}
