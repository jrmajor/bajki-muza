<script lang="ts">
	import { type Snippet } from 'svelte';
	import type { MouseEventHandler } from 'svelte/elements';
	import { inertia } from '@inertiajs/svelte';

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

	let classes = $derived([
		'rounded-full px-4 py-2 text-sm font-medium tracking-wide shadow-md',
		danger
			? 'bg-red-700 text-red-100 hover:bg-red-600 active:bg-red-800'
			: 'bg-white dark:bg-gray-800',
		'ease-out transition-colors duration-150',
		className,
	]);
</script>

{#if inertiaProp}
	<button use:inertia={inertiaProp} {type} class={classes}>
		{@render children()}
	</button>
{:else}
	<button {type} {onclick} class={classes}>
		{@render children()}
	</button>
{/if}
