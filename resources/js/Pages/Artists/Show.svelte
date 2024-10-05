<script lang="ts">
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/artists';
	import Button from '@/Components/Form/Button.svelte';
	import Discogs from '@/Components/Icons/Discogs.svelte';
	import FilmPolski from '@/Components/Icons/FilmPolski.svelte';
	import Wikipedia from '@/Components/Icons/Wikipedia.svelte';
	import PhotoModal from '@/Components/Images/PhotoModal.svelte';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Title from '@/Components/Title.svelte';
	import AsActor from './Components/AsActor.svelte';
	import Credits from './Components/Credits.svelte';

	let { artist, user }: { artist: ShowResource } & SharedProps = $props();

	let modalIsOpen = $state(false);
	let modal: ReturnType<typeof PhotoModal> = $state()!;
</script>

<svelte:head>
	<title>{artist.name} - Bajki Polskich Nagrań „Muza”</title>

	{#if artist.photo}
		<meta property="og:image" content={artist.photo.url[320]}>
		<meta name="twitter:image" content={artist.photo.url[320]}>
	{/if}
</svelte:head>

<div class="flex flex-col items-center mb-6 sm:flex-row">
	<div class="text-center sm:hidden">
		<Title text={artist.name} href={route('artists.edit', { artist })} hrefIf={!!user}/>
	</div>

	{#if artist.photo}
		<div
			style:aspect-ratio="{artist.photo.width} / {artist.photo.height}"
			class="-p-px mb-2 mt-5 h-40 flex-none self-center rounded-lg shadow-lg sm:my-0 sm:mr-6"
		>
			{#if !modalIsOpen}
				<button onclick={() => modal.open()} class="relative size-full">
					<div
						class="absolute -inset-px rounded-lg bg-gray-400 bg-cover bg-center dark:bg-gray-800"
						style:background-image={artist.photo ? `url("${artist.photo.placeholder}")` : null}
						style:view-transition-name="image-modal"
					>
						<ResponsiveImage
							src={artist.photo.url}
							size="full"
							imageSize={160}
							loading="eager"
							alt={artist.name}
							class="rounded-lg"
						/>
					</div>
				</button>
			{/if}
		</div>
	{:else if artist.discogsPhoto && !user}
		<div class="mb-2 mt-5 h-40 flex-none self-center overflow-hidden rounded-lg shadow-lg sm:my-0 sm:mr-6">
			<img
				src={artist.discogsPhoto}
				alt={artist.name}
				class="h-40 filter grayscale"
			>
		</div>
	{/if}

	<div
		class="flex flex-col flex-grow justify-between space-y-3"
		class:sm:py-2={artist.photo || artist.discogsPhoto}
		class:self-stretch={artist.wikipediaExtract}
	>
		<div
			class="
				hidden sm:block
				{artist.photo || artist.discogsPhoto || artist.wikipediaExtract ? 'self-start' : 'self-center'}
			"
		>
			<Title text={artist.name} href={route('artists.edit', { artist })} hrefIf={!!user}/>
		</div>

		{#if artist.discogsUrl || artist.filmpolskiUrl || artist.wikipediaUrl}
			<div
				class="
					flex flex-col gap-2
					{artist.photo || artist.discogsPhoto || artist.wikipediaExtract ? 'self-stretch' : 'self-center'}
				"
			>
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
	<Button inertia={{ href: route('artists.destroy', { artist }), method: 'delete' }} danger>
		Usuń
	</Button>
{/if}

{#if artist.photo}
	<PhotoModal
		bind:this={modal}
		bind:isOpen={modalIsOpen}
		placeholder={artist.photo.placeholder}
		url={artist.photo.url}
		width={artist.photo.width ?? 100}
		height={artist.photo.height ?? 100}
		alt={artist.name}
	/>
{/if}
