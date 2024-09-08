<script lang="ts">
	import { inertia } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import { type Loop } from '@/helpers/addLoop';
	import Appearances from '@/Components/Appearances.svelte';

	let {
		artist,
		loop,
		before = '',
		genetivus = false,
	}: {
		artist: {
			slug: string;
			name: string;
			genetivus?: string | null;
			appearances: number;
		};
		loop: Loop;
		before?: string;
		genetivus?: boolean;
	} = $props();
</script>

<span class="whitespace-nowrap">
	{#if loop.isFirst}
		{before}
	{:else if loop.isLast}
		&#32;i
	{/if}

	<a
		use:inertia
		href={route('artists.show', { artist })}
		class="inline-flex items-center"
	>
		{genetivus ? artist.genetivus ?? artist.name : artist.name}
		<Appearances count={artist.appearances} small/>
	</a>{loop.remaining >= 2 ? ',' : ''}
</span>
<span class="hidden"></span>
