<script lang="ts">
	import { route } from 'ziggy-js';
	import { inertia } from '@inertiajs/svelte';
	import type { ShowResource } from '@/types/artists';
	import { formatList } from '@/helpers/intl';
	import Title from '@/Components/Title.svelte';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';

	let { artist }: { artist: ShowResource } = $props();
</script>

<div class="flex flex-col gap-3 items-center w-full">
	<Title sub text="Aktor"/>

	<div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
		{#each artist.asActor as tale}
			<a
				href={route('tales.show', { tale })}
				use:inertia
				class="flex overflow-hidden items-center w-full bg-gray-50 rounded-lg shadow-lg h-15 dark:bg-gray-900"
			>
				<div
					class="flex-none bg-placeholder-cover size-15"
					style={tale.cover ? `background-image: url("${tale.cover.placeholder}")` : null}
				>
					{#if tale.cover}
						<ResponsiveImage image={tale.cover} size={15} alt="Okładka bajki {tale.title}"/>
					{/if}
				</div>
				<div class="flex flex-col flex-grow justify-between p-2 pl-3">
					<div class="text-sm font-medium leading-tight sm:text-base">
						{tale.title}
					</div>
					{#if tale.characters}
						<small>jako {formatList(tale.characters)}</small>
					{/if}
				</div>
				<div class="hidden flex-none pr-4 sm:block">
					<small>{tale.year}</small>
				</div>
			</a>
		{/each}
	</div>
</div>
