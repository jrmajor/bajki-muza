<script lang="ts">
	import type { ShowResource } from '@/types/tales';
	import ResponsiveImage from '@/Components/ResponsiveImage.svelte';
	import Actors from './Components/Actors.svelte';
	import CustomCredits from './Components/CustomCredits.svelte';
	import MainCredits from './Components/MainCredits.svelte';
	import Title from './Components/Title.svelte';

	let { tale, user }: { tale: ShowResource } & SharedProps = $props();
</script>

<svelte:head>
	<title>{tale.title} - Bajki Polskich Nagrań „Muza”</title>

	{#if tale.cover}
		<meta property="og:image" content={tale.cover.url[384]}>
		<meta name="twitter:image" content={tale.cover.url[384]}>
	{/if}
</svelte:head>

<div class="flex flex-col items-center mb-6 sm:flex-row">
	<div class="text-center sm:hidden">
		<Title {tale} {user}/>
	</div>

	<div class="overflow-hidden flex-none self-center mt-5 mb-2 rounded-lg shadow-lg sm:my-0 sm:mr-6">
		<div
			class="size-48 bg-placeholder-cover"
			style:background-image={tale.cover ? `url("${tale.cover.placeholder}")` : null}
		>
			{#if tale.cover}
				<ResponsiveImage
					src={tale.cover.url}
					size="full"
					imageSize={192}
					loading="eager"
					alt="Okładka bajki {tale.title}"
				/>
			{/if}
		</div>
	</div>

	<div class="flex flex-col flex-grow gap-3 justify-between self-center sm:py-2 sm:self-stretch">
		<div class="hidden self-start sm:block">
			<Title {tale} {user}/>
		</div>
		<div>
			<MainCredits {tale}/>
		</div>
	</div>
</div>

<div class="flex flex-col gap-8 items-center w-full">
	{#if tale.actors.length}
		<Actors {tale} {user}/>
	{/if}
	{#if Object.keys(tale.customCredits).length}
		<CustomCredits {tale}/>
	{/if}
</div>
