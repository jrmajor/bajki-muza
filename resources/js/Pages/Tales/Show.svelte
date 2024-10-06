<script lang="ts">
	import type { ShowResource } from '@/types/tales';
	import ImageModal from '@/Components/Images/ImageModal.svelte';
	import ResponsiveImage from '@/Components/Images/ResponsiveImage.svelte';
	import Actors from './Components/Actors.svelte';
	import CustomCredits from './Components/CustomCredits.svelte';
	import MainCredits from './Components/MainCredits.svelte';
	import Title from './Components/Title.svelte';

	let { tale, user }: { tale: ShowResource } & SharedProps = $props();

	let modalIsOpen = $state(false);
	let modal: ReturnType<typeof ImageModal> = $state()!;
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

	<div class="mb-2 mt-5 size-48 flex-none sm:my-0 sm:mr-6">
		{#if tale.cover}
			{#if !modalIsOpen}
				<button
					onclick={() => modal.open()}
					class="size-full overflow-hidden rounded-lg bg-cover bg-center shadow-lg"
					style:background-image="url('{tale.cover.placeholder}')"
					style:view-transition-name="image-modal"
				>
					<ResponsiveImage src={tale.cover.url} imageSize={193} alt="Okładka bajki {tale.title}" eager/>
				</button>
			{/if}
		{:else}
			<div class="bg-placeholder-cover size-full rounded-lg shadow-lg"></div>
		{/if}
	</div>

	<div class="flex flex-grow flex-col justify-between gap-3 self-center sm:self-stretch sm:py-2">
		<div class="hidden self-start sm:block">
			<Title {tale} {user}/>
		</div>
		<div>
			<MainCredits {tale}/>
		</div>
	</div>
</div>

<div class="flex w-full flex-col items-center gap-8">
	{#if tale.actors.length}
		<Actors {tale} {user}/>
	{/if}
	{#if Object.keys(tale.customCredits).length}
		<CustomCredits {tale}/>
	{/if}
</div>

{#if tale.cover}
	<ImageModal
		bind:this={modal}
		bind:isOpen={modalIsOpen}
		placeholder={tale.cover.placeholder}
		url={tale.cover.url}
		width={tale.cover.size ?? 100}
		height={tale.cover.size ?? 100}
		alt="Okładka bajki {tale.title}"
	/>
{/if}
