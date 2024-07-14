<script lang="ts">
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/tales';
	import addLoop from '@/helpers/addLoop';
	import Appearances from '@/Components/Appearances.svelte';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';

	let { tale, user }: { tale: ShowResource; user: SharedUser } = $props();
</script>

<div class="flex flex-col gap-3 items-center w-full">
	<h3 class="text-xl font-medium shadow-subtitle">
		Obsada
	</h3>
	<div class="flex flex-col gap-2.5 w-full md:w-5/6 xl:w-2/3">
		{#each tale.actors as actor}
			<a
				href={route('artists.show', { artist: actor })}
				class="flex overflow-hidden items-center w-full h-14 bg-gray-50 rounded-lg shadow-lg dark:bg-gray-900"
			>
				<div
					class="flex-none size-14 bg-placeholder-artist"
					style={actor.photo ? `background-image: url("${actor.photo.facePlaceholder}")` : null}
				>
					{#if actor.photo}
						<!-- todo: alt -->
						<ResponsiveImage image={actor.photo} size={14} alt=""/>
					{:else if actor.discogsPhotoThumb && !user}
						<!-- todo: alt -->
						<!-- svelte-ignore a11y_missing_attribute -->
						<img
							src={actor.discogsPhotoThumb}
							class="object-cover size-14 filter grayscale"
						>
					{/if}
				</div>
				<div class="flex flex-col flex-grow justify-between p-2 pl-3">
					<div class="text-sm font-medium leading-tight sm:text-base">
						{actor.name}
					</div>
					{#if actor.characters}
						<small>
							jako
							{#each addLoop(actor.characters) as [character, loop]}
								{character}<!--
							-->{loop.remaining > 1 ? ', ' : loop.remaining > 0 ? ' i ' : ''}
							{/each}
						</small>
					{/if}
				</div>
				<div class="flex-none pr-4">
					<Appearances count={actor.appearances}/>
				</div>
			</a>
		{/each}
	</div>
</div>
