<script lang="ts">
	import { route } from 'ziggy-js';
	import { inertia } from '@inertiajs/svelte';
	import type { ShowResource } from '@/types/artists';
	import Layout from '@/Layouts/Layout.svelte';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';
	import Discogs from '@/Components/Icons/Discogs.svelte';
	import FilmPolski from '@/Components/Icons/FilmPolski.svelte';
	import Wikipedia from '@/Components/Icons/Wikipedia.svelte';
	import AsActor from './Components/AsActor.svelte';
	import Credits from './Components/Credits.svelte';

	let { artist, user }: { artist: ShowResource } & SharedProps = $props();
</script>

<svelte:head>
	<title>{artist.name} - Bajki Polskich Nagrań „Muza”</title>

	{#if artist.photo}
		<meta property="og:image" content={artist.photo.url[320]}>
		<meta name="twitter:image" content={artist.photo.url[320]}>
	{/if}
</svelte:head>

{#snippet name()}
	{#each artist.name.split(' ') as word}
		<span class="shadow-title">{word}</span>
		<span class="hidden"></span>
	{/each}
{/snippet}

<Layout {user}>
	<div class="flex flex-col items-center mb-6 sm:flex-row">
		<div class="text-center sm:hidden">
			<h2 class="text-2xl font-medium">
				{#if user}
					<a href={route('artists.edit', { artist })}>{@render name()}</a>
				{:else}
					{@render name()}
				{/if}
			</h2>
		</div>

		{#if artist.photo}
			<div
				style="width: {(artist.photo.aspectRatio ?? 1) * 10}rem"
				class="overflow-hidden relative flex-none self-center mt-5 mb-2 h-40 rounded-lg shadow-lg sm:my-0 sm:mr-6 -p-px"
			>
				<div
					class="absolute -inset-px bg-gray-400 bg-center bg-cover dark:bg-gray-800"
					style={artist.photo ? `background-image: url("${artist.photo.placeholder}")` : null}
				>
					<!-- todo: alt -->
					<ResponsiveImage
						image={artist.photo}
						size="full"
						imageSize={160}
						loading="eager"
						alt=""
					/>
				</div>
			</div>
		{:else if artist.discogsPhoto && !user}
			<div class="overflow-hidden flex-none self-center mt-5 mb-2 h-40 rounded-lg shadow-lg sm:my-0 sm:mr-6">
				<!-- todo: alt -->
				<!-- svelte-ignore a11y_missing_attribute -->
				<img src={artist.discogsPhoto} class="h-40 filter grayscale">
			</div>
		{/if}

		<div
			class="flex flex-col flex-grow justify-between space-y-3"
			class:sm:py-2={artist.photo || artist.discogsPhoto}
			class:self-stretch={artist.wikipediaExtract}
		>
			<div class="
				hidden sm:block
				{artist.photo || artist.discogsPhoto || artist.wikipediaExtract ? 'self-start' : 'self-center'}
			">
				<h2 class="text-2xl font-medium">
					{#if user}
						<a href={route('artists.edit', { artist })}>{@render name()}</a>
					{:else}
						{@render name()}
					{/if}
				</h2>
			</div>

			{#if artist.discogsUrl || artist.filmpolskiUrl || artist.wikipediaUrl}
				<div class="
					flex flex-col gap-2
					{artist.photo || artist.discogsPhoto || artist.wikipediaExtract ? 'self-stretch' : 'self-center'}
				">
					{#if artist.wikipediaExtract}
						<div>{artist.wikipediaExtract}</div>
					{/if}
					<div class="flex gap-5 items-center self-center sm:self-start">
						{#if artist.discogsUrl}
							<a href={artist.discogsUrl} target="_blank"><Discogs/></a>
						{/if}
						{#if artist.filmpolskiUrl}
							<a href={artist.filmpolskiUrl} target="_blank"><FilmPolski/></a>
						{/if}
						{#if artist.wikipediaUrl}
							<a href={artist.wikipediaUrl} target="_blank"><Wikipedia/></a>
						{/if}
					</div>
				</div>
			{/if}
		</div>
	</div>

	<div class="flex flex-col gap-6 w-full">
		{#if artist.asActor.length}
			<AsActor {artist}/>
		{/if}
		<Credits {artist}/>
	</div>

	{#if user && artist.appearances === 0}
		<button
			use:inertia={{ href: route('artists.destroy', { artist }), method: 'delete' }}
			class="
				px-3 py-1.5 bg-red-700 text-red-100 text-sm tracking-wide font-medium uppercase shadow-lg rounded-full
				hover:bg-red-600 active:bg-red-800 transition-colors duration-150 ease out
			"
		>Usuń</button>
	{/if}
</Layout>