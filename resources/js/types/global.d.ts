declare module '@inertiajs/svelte' {
	import type { Action } from 'svelte/action';
	import { Router } from '@inertiajs/core';
	export const inertia: Action;
	export const router: Router;
}
