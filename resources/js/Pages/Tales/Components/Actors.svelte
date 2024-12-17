<script lang="ts">
	import { inertia } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/tales';
	import { formatList } from '@/helpers/intl';
	import Appearances from '@/Components/Appearances.svelte';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Title from '@/Components/Title.svelte';

	let { tale, user }: { tale: ShowResource; user: SharedUser } = $props();
</script>

<div class="flex flex-col gap-3 items-center w-full">
	<Title sub text="Obsada"/>

	<div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
		{#each tale.actors as actor}
			<a
				use:inertia
				href={route('artists.show', { artist: actor })}
				class="flex overflow-hidden items-center w-full h-14 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900"
			>
				<div
					class="flex-none size-14 bg-placeholder-artist"
					style:background-image={actor.photo ? `url('${actor.photo.placeholder}')` : null}
				>
					{#if actor.photo}
						<ResponsiveImage src={actor.photo.url} size={14} alt={actor.name}/>
					{:else if actor.discogsPhotoThumb && !user}
						<img
							src={actor.discogsPhotoThumb}
							alt={actor.name}
							class="object-cover size-14 filter grayscale"
						>
					{/if}
				</div>
				<div class="flex flex-col grow justify-between p-2 pl-3">
					<div class="text-sm font-medium leading-tight sm:text-base">
						{actor.name}
					</div>
					{#if actor.characters}
						<small>jako {formatList(actor.characters)}</small>
					{/if}
				</div>
				<div class="flex-none pr-4">
					<Appearances count={actor.appearances}/>
				</div>
			</a>
		{/each}
	</div>
</div>
