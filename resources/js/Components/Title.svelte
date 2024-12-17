<script lang="ts">
	import { inertia } from '@inertiajs/svelte';

	let {
		sub = false,
		text,
		href = null,
		hrefIf = true,
	}: {
		sub?: boolean;
		text: string;
		href?: string | null;
		hrefIf?: boolean;
	} = $props();
</script>

{#snippet title()}
	{#each text.split(' ') as word}
		<span class="title" class:sub>{word}</span>
		<span class="hidden"></span>
	{/each}
{/snippet}

{#snippet link()}
	{#if href && hrefIf}
		<a use:inertia {href}>{@render title()}</a>
	{:else}
		{@render title()}
	{/if}
{/snippet}

{#if sub}
	<h3 class="text-xl font-medium">{@render link()}</h3>
{:else}
	<h2 class="text-2xl font-medium">{@render link()}</h2>
{/if}

<style>
	.title {
		position: relative;
	}

	.title::after {
		background-color: light-dark(var(--color-brand-primary), var(--color-brand-primary-dark));
		content: '';
		display: block;
		position: absolute;
		z-index: -1;
		top: calc(100% - 0.6rem);
		left: -0.375rem;
		width: calc(100% + 0.75rem);
		height: 0.57rem;
	}

	.sub::after {
		background-color: light-dark(var(--color-brand-lighter), var(--color-brand-lighter-dark));
		top: calc(100% - 0.455rem);
		left: -0.25rem;
		width: calc(100% + 0.5rem);
		height: 0.4rem;
	}
</style>
