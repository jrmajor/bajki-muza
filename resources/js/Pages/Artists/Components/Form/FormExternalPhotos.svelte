<script lang="ts">
	import type { DiscogsPhotoResource, FilmPolskiPhotoGroupResource } from '@/types/artists';
	import FormExternalPhoto from './FormExternalPhoto.svelte';

	let {
		discogsPhotos,
		filmPolskiPhotos,
		currentPhotoUrl,
		onclick,
	}: {
		discogsPhotos: DiscogsPhotoResource[];
		filmPolskiPhotos: FilmPolskiPhotoGroupResource[];
		currentPhotoUrl: string | null;
		onclick: (uri: string, source: 'discogs' | 'filmpolski') => void;
	} = $props();
</script>

<div class="flex flex-wrap justify-around w-full">
	{#each discogsPhotos as photo}
		<FormExternalPhoto
			url={photo.uri}
			isSelected={currentPhotoUrl === photo.uri}
			onclick={() => onclick(photo.uri, 'discogs')}
		/>
	{/each}
</div>

<div class="flex flex-wrap justify-around w-full">
	{#each filmPolskiPhotos as movie}
		{#each movie.photos as photo}
			<FormExternalPhoto
				url={`https://filmpolski.pl${photo}`}
				title={movie.title}
				year={movie.year}
				isSelected={currentPhotoUrl === `https://filmpolski.pl${photo}`}
				onclick={() => onclick(`https://filmpolski.pl${photo}`, 'filmpolski')}
			/>
		{/each}
	{/each}
</div>
