<script lang="ts">
	import { onMount } from 'svelte';
	import { inertia, router } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import type { IndexResource } from '@/types/tales';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Pagination from '@/Components/Pagination/Pagination.svelte';

	let { data: tales, meta }: PaginatedResource<IndexResource> = $props();

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
		router.get(`${route('tales.index')}?${params}`);
	}

	onMount(() => {
		let params = new URLSearchParams(window.location.search);
		search = params.get('search') ?? '';
	});
</script>

<svelte:head>
	<title>Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<div class="w-full">
	<div class="flex flex-col gap-5 items-center">
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
		{#each tales as tale (tale.slug)}
			<a
				use:inertia
				href={route('tales.show', { tale })}
				class="flex overflow-hidden items-center w-full h-32 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900"
			>
				<div
					class="flex-none size-32 bg-placeholder-cover"
					style:background-image={tale.cover ? `url("${tale.cover.placeholder}")` : null}
				>
					{#if tale.cover}
						<ResponsiveImage src={tale.cover.url} size={32} alt="Okładka bajki {tale.title}"/>
					{/if}
				</div>
				<div class="flex flex-col flex-grow justify-between self-stretch p-4 sm:p-5 sm:pl-6">
					<div class="text-lg font-medium leading-tight sm:text-xl">
						{tale.title}
					</div>
					<small>{tale.year}</small>
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
