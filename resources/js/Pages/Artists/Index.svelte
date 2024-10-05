<script lang="ts">
	import { onMount } from 'svelte';
	import { inertia, router } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import type { IndexResource } from '@/types/artists';
	import Appearances from '@/Components/Appearances.svelte';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Pagination from '@/Components/Pagination/Pagination.svelte';

	let {
		data: artists,
		meta,
		user,
	}: PaginatedResource<IndexResource> & SharedProps = $props();

	let search = $state('');

	let timeout: ReturnType<typeof setTimeout>;

	function debounceSearch() {
		if (timeout) clearTimeout(timeout);
		timeout = setTimeout(submitSearch, 500);
	}

	function submitSearch() {
		let params = new URLSearchParams(window.location.search);
		search ? params.set('search', search) : params.delete('search');
		params.delete('page');
		router.get(`${route('artists.index')}?${params}`);
	}

	onMount(() => {
		let params = new URLSearchParams(window.location.search);
		search = params.get('search') ?? '';
	});
</script>

<svelte:head>
	<title>Artyści - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<div class="w-full">
	<div class="flex flex-col gap-3 items-center">
		<!-- svelte-ignore a11y_autofocus -->
		<input
			type="search"
			bind:value={search}
			oninput={debounceSearch}
			autocomplete="off"
			autofocus
			class="
				w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 dark:bg-gray-900
				border-none focus:outline-none focus:ring focus:ring-brand-primary focus:ring-opacity-25
			"
		>
		{#each artists as artist (artist.slug)}
			<a
				use:inertia
				href={route('artists.show', { artist })}
				class="flex overflow-hidden items-center w-full h-12 bg-gray-50 rounded-lg shadow-lg sm:h-14 dark:bg-gray-900"
			>
				<div
					class="flex-none size-12 bg-placeholder-artist sm:size-14"
					style:background-image={artist.photo ? `url("${artist.photo.placeholder}")` : null}
				>
					{#if artist.photo}
						<ResponsiveImage src={artist.photo.url} size={14} alt={artist.name} class="size-12 sm:size-14"/>
					{:else if artist.discogsPhotoThumb && !user}
						<img src={artist.discogsPhotoThumb} alt={artist.name} class="object-cover size-12 sm:size-14 grayscale">
					{/if}
				</div>
				<div class="flex-grow p-2 pl-3">
					<span class="flex-shrink-0 font-medium">{artist.name}</span>
				</div>
				<div class="flex-none pr-4">
					<Appearances count={artist.appearances}/>
				</div>
			</a>
		{:else}
			<div class="pt-6 text-lg font-medium leading-tight text-gray-700 dark:text-gray-400">Nie ma takich zwierząt.</div>
		{/each}
	</div>

	<div class="flex flex-col items-center mt-8 w-full">
		<Pagination {meta}/>
	</div>
</div>
