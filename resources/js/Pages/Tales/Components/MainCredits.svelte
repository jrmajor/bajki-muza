<script lang="ts">
	import type { CreditResource, ShowResource } from '@/types/tales';
	import addLoop from '@/helpers/addLoop';
	import ArtistName from '@/Components/ArtistName.svelte';

	let { tale }: { tale: ShowResource } = $props();

	let c = $derived(tale.mainCredits);

	function groupByCreditLabel(credits: CreditResource[], defaultLabel: string): Array<[string, CreditResource[]]> {
		// @ts-expect-error Object.groupBy returns Partial
		return Object.entries(Object.groupBy(credits, (c) => c.as ?? defaultLabel));
	}
</script>

<div class="flex flex-col">
	{#if c.text && c.author}
		<div>
			<strong>{c.text[0].as ?? 'Słowa'}:</strong>
			{#each addLoop(c.text) as [writer, loop]}
				<ArtistName artist={writer} {loop}/>
			{/each}
			{#each addLoop(c.author) as [author, loop]}
				<ArtistName artist={author} {loop} before="wg." genetivus/>
			{/each}
		</div>
	{:else if c.text}
		{#each groupByCreditLabel(c.text, 'Słowa') as [label, writers]}
			<div>
				<strong>{label}:</strong>
				{#each addLoop(writers) as [writer, loop]}
					<ArtistName artist={writer} {loop}/>
				{/each}
			</div>
		{/each}
	{:else if c.author}
		{#each groupByCreditLabel(c.author, 'Autor') as [label, authors]}
			<div>
				<strong>{label}:</strong>
				{#each addLoop(authors) as [author, loop]}
					<ArtistName artist={author} {loop}/>
				{/each}
			</div>
		{/each}
	{/if}

	{#if c.lyrics}
		{#each groupByCreditLabel(c.lyrics, 'Teksty piosenek') as [label, lyricists]}
			<div>
				<strong>{label}:</strong>
				{#each addLoop(lyricists) as [lyricist, loop]}
					<ArtistName artist={lyricist} {loop}/>
				{/each}
			</div>
		{/each}
	{/if}

	{#if c.music}
		{#each groupByCreditLabel(c.music, 'Muzyka') as [label, composers]}
			<div>
				<strong>{label}:</strong>
				{#each addLoop(composers) as [composer, loop]}
					<ArtistName artist={composer} {loop}/>
				{/each}
			</div>
		{/each}
	{/if}
</div>
