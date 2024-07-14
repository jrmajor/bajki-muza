<script lang="ts">
	import { onMount } from 'svelte';
	import { route } from 'ziggy-js';
	import { inertia, router } from '@inertiajs/svelte';
	import type { IndexResource } from '@/types/tales';
	import Layout from '@/Layouts/Layout.svelte';
	import Pagination from '@/Components/Pagination/Pagination.svelte';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';

	let { data: tales, meta, user }: PaginatedResource<IndexResource> & SharedProps = $props();

	let search = $state('');

	let timeout: number;

	function debounceSearch() {
		if (timeout) clearTimeout(timeout);
		timeout = setTimeout(submitSearch, 500);
	}

	function submitSearch() {
		let params = new URLSearchParams(window.location.search);
		search ? params.set('search', search) : params.delete('search');
		params.delete('page');
		router.get(route('tales.index') + '?' + params.toString());
	}

	onMount(() => {
		let params = new URLSearchParams(window.location.search);
		search = params.get('search') ?? '';
	});
</script>

<Layout {user}>
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
			{#each tales as tale (tale.id)}
				<a
					href={route('tales.show', tale)}
					use:inertia
					class="flex overflow-hidden items-center w-full h-32 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900"
				>
					<div
						class="flex-none size-32 bg-placeholder-cover"
						style={tale.cover ? `background-image: url("${tale.cover.placeholder}")` : null}
					>
						{#if tale.cover}
							<ResponsiveImage image={tale.cover} size={32} alt="Okładka bajki {tale.title}"/>
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
</Layout>
