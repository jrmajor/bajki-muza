<script lang="ts">
	import { route } from 'ziggy-js';
	import { inertia } from '@inertiajs/svelte';
	import type { ShowResource } from '@/types/artists';
	import { ucfirst } from '@/helpers/intl';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';

	let { artist }: { artist: ShowResource } = $props();
</script>

{#each Object.entries(artist.credits) as [type, tales]}
	<div class="flex flex-col gap-3 items-center w-full">
		<h3 class="text-xl font-medium shadow-subtitle">
			{ucfirst(type)}
		</h3>
		<div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
			{#each tales as tale}
				<a
					href={route('tales.show', { tale })}
					use:inertia
					class="flex overflow-hidden items-center w-full bg-gray-50 rounded-lg shadow-lg h-13 dark:bg-gray-900"
				>
					<div
						class="flex-none bg-placeholder-cover size-13"
						style={tale.cover ? `background-image: url("${tale.cover.placeholder}")` : null}
					>
						{#if tale.cover}
							<ResponsiveImage
								image={tale.cover}
								size={13}
								imageSize={60}
								alt="Okładka bajki {tale.title}"
							/>
						{/if}
					</div>
					<div class="flex-grow p-2 pl-3 text-sm font-medium leading-tight sm:text-base">
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
