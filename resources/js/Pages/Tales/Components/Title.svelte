<script lang="ts">
	import { route } from 'ziggy-js';
	import { inertia } from '@inertiajs/svelte';
	import type { ShowResource } from '@/types/tales';

	let { tale, user }: { tale: ShowResource; user: SharedUser } = $props();
</script>

{#snippet title()}
	{#each tale.title.split(' ') as word}
		<span class="shadow-title">{word}</span>
		<span class="hidden"></span>
	{/each}
{/snippet}

<h2 class="text-2xl font-medium">
	{#if user}
		<a href={route('tales.edit', { tale })} use:inertia>{@render title()}</a>
	{:else}
		{@render title()}
	{/if}
</h2>
{#if tale.year}
	<div class="mt-1">{tale.year}</div>
{/if}
