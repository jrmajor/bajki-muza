<script lang="ts">
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/artists';
	import Button from '@/Components/Form/Button.svelte';
	import Discogs from '@/Components/Icons/Discogs.svelte';
	import FilmPolski from '@/Components/Icons/FilmPolski.svelte';
	import Wikipedia from '@/Components/Icons/Wikipedia.svelte';
	import ImageModal from '@/Components/Images/ImageModal.svelte';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Title from '@/Components/Title.svelte';
	import AsActor from './Components/AsActor.svelte';
	import Credits from './Components/Credits.svelte';

	let { artist, user }: { artist: ShowResource } & SharedProps = $props();

	let hasAnyLinks = $derived(artist.discogsUrl || artist.filmpolskiUrl || artist.wikipediaUrl);
	let hasPhoto = $derived(artist.photo || artist.discogsPhoto);
	let hasExtract = $derived(!!artist.wikipediaExtract);

	let modalIsOpen = $state(false);
	let modal: ReturnType<typeof ImageModal> = $state()!;
</script>

<svelte:head>
	<title>{artist.name} - Bajki Polskich Nagrań „Muza”</title>

	{#if artist.photo}
		<meta property="og:image" content={artist.photo.url[320]}>
		<meta name="twitter:image" content={artist.photo.url[320]}>
	{/if}
</svelte:head>

<div class="flex w-full flex-col items-center gap-6">
	{@render header()}

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
	<ImageModal
		bind:this={modal}
		bind:isOpen={modalIsOpen}
		placeholder={artist.photo.placeholder}
		url={artist.photo.url}
		width={artist.photo.width ?? 100}
		height={artist.photo.height ?? 100}
		alt={artist.name}
	/>
{/if}

{#snippet header()}
	<div class="flex flex-col items-center sm:flex-row">
		<div class="sm:hidden">
			<Title text={artist.name} href={route('artists.edit', { artist })} hrefIf={!!user}/>
		</div>

		{#if hasPhoto}
			<div class="mb-2 mt-5 flex-none sm:my-0 sm:mr-6">
				{@render photo()}
			</div>
		{/if}

		<div
			class="flex flex-grow flex-col justify-between space-y-3"
			class:sm:py-2={hasPhoto}
			class:self-stretch={hasExtract}
			class:items-center={!hasPhoto && !hasExtract}
		>
			<div class="hidden sm:block">
				<Title text={artist.name} href={route('artists.edit', { artist })} hrefIf={!!user}/>
			</div>

			{#if hasAnyLinks}
				<div class="flex flex-col gap-2 items-center sm:items-start">
					{#if hasExtract}
						<div>{artist.wikipediaExtract}</div>
					{/if}
					{@render links()}
				</div>
			{/if}
		</div>
	</div>
{/snippet}

{#snippet photo()}
	{#if artist.photo}
		<div class="h-40" style:aspect-ratio="{artist.photo.width} / {artist.photo.height}">
			{#if !modalIsOpen}
				<button
					onclick={() => modal.open()}
					class="size-full overflow-hidden rounded-lg bg-cover bg-center shadow-lg"
					style:background-image="url('{artist.photo.placeholder}')"
					style:view-transition-name="image-modal"
				>
					<ResponsiveImage src={artist.photo.url} imageSize={160} eager alt={artist.name}/>
				</button>
			{/if}
		</div>
	{:else if artist.discogsPhoto && !user}
		<div class="h-40 overflow-hidden rounded-lg shadow-lg">
			<img src={artist.discogsPhoto} alt={artist.name} class="h-40 filter grayscale">
		</div>
	{/if}
{/snippet}

{#snippet links()}
	<div class="flex gap-5 items-center">
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
{/snippet}
