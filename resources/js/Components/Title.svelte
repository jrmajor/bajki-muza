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
		<span class="title {sub ? 'sub' : ''}">{word}</span>
		<span class="hidden"></span>
	{/each}
{/snippet}

{#snippet link()}
	{#if href && hrefIf}
		<a href={href} use:inertia>{@render title()}</a>
	{:else}
		{@render title()}
	{/if}
{/snippet}

{#if sub}
	<h3 class="text-xl font-medium">{@render link()}</h3>
{:else}
	<h2 class="text-2xl font-medium">{@render link()}</h2>
{/if}

<style lang="postcss">
	.title {
		position: relative;
		@apply after:bg-brand-primary after:dark:bg-brand-primary-dark;
	}

	.sub {
		@apply after:bg-brand-lighter after:dark:bg-brand-lighter-dark;
	}

	.title::after {
		display: block;
		position: absolute;
		z-index: -1;
		top: calc(100% - 0.6rem);
		left: -0.375rem;
		width: calc(100% + 0.75rem);
		height: 0.57rem;
	}

	.sub::after {
		top: calc(100% - 0.455rem);
		left: -0.25rem;
		width: calc(100% + 0.5rem);
		height: 0.4rem;
	}
</style>
