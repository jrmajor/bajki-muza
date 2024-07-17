<script lang="ts">
	import { inertia } from '@inertiajs/svelte';
	import { type Snippet } from 'svelte';
	import type { MouseEventHandler } from 'svelte/elements';

	let {
		type = null,
		onclick = null,
		inertia: inertiaProp = null,
		danger = false,
		class: className = '',
		children,
	}: {
		type?: null | 'submit' | 'button';
		onclick?: MouseEventHandler<HTMLButtonElement> | null;
		inertia?: Parameters<typeof inertia>[1] | null;
		danger?: boolean;
		class?: string;
		children: Snippet;
	} = $props();

	let classes = $derived(`
		py-2 px-4 text-sm font-medium tracking-wide rounded-full shadow-md
		${danger || 'bg-white dark:bg-gray-800'}
		${danger && 'text-red-100 bg-red-700 hover:bg-red-600 active:bg-red-800'}
		transition-colors duration-150 ease out
		${className}
	`);
</script>

{#if inertiaProp}
	<button {type} use:inertia={inertiaProp} class={classes}>
		{@render children()}
	</button>
{:else}
	<button {type} {onclick} class={classes}>
		{@render children()}
	</button>
{/if}
