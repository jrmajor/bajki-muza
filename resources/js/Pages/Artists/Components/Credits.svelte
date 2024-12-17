<script lang="ts">
	import { inertia } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/artists';
	import { ucfirst } from '@/helpers/intl';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Title from '@/Components/Title.svelte';

	let { artist }: { artist: ShowResource } = $props();
</script>

{#each Object.entries(artist.credits) as [type, tales]}
	<div class="flex flex-col gap-3 items-center w-full">
		<Title sub text={ucfirst(type)}/>

		<div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
			{#each tales as tale}
				<a
					use:inertia
					href={route('tales.show', { tale })}
					class="flex overflow-hidden items-center w-full bg-gray-50 rounded-lg shadow-lg h-13 dark:bg-gray-900"
				>
					<div
						class="flex-none bg-placeholder-cover size-13"
						style:background-image={tale.cover ? `url('${tale.cover.placeholder}')` : null}
					>
						{#if tale.cover}
							<ResponsiveImage src={tale.cover.url} size={13} imageSize={60} alt="Okładka bajki {tale.title}"/>
						{/if}
					</div>
					<div class="grow p-2 pl-3 text-sm font-medium leading-tight sm:text-base">
						{tale.title}
					</div>
					<div class="pr-5">
						<small>{tale.year}</small>
					</div>
				</a>
			{/each}
		</div>
	</div>
{/each}
