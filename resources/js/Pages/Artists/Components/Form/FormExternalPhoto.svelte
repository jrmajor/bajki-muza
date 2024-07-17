<script lang="ts">
	let {
		url,
		title = null,
		year = null,
		isSelected,
		onclick,
	}: {
		url: string;
		title?: string | null;
		year?: number | null;
		isSelected: boolean;
		onclick: () => void;
	} = $props();

	let naturalWidth = $state() as number;
	let naturalHeight = $state() as number;
	let dimensions = $derived(naturalWidth + '×' + naturalHeight);
</script>

<button
	type="button"
	{onclick}
	class="group relative m-1.5 shadow-lg rounded-lg overflow-hidden focus:outline-none"
>
	<!-- svelte-ignore a11y_missing_attribute -->
	<img src={url} bind:naturalWidth bind:naturalHeight class="h-40">

	{#if title !== null}
		<div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-75 transition-all duration-300"></div>
		<div class="absolute bottom-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
			<div class="flex flex-col p-2 text-white">
				<small class="text-xs">{year}</small>
				<span class="text-sm font-medium leading-tight">{title}</span>
			</div>
		</div>
	{/if}

	<div
		class="absolute top-0 right-0 pl-8 pb-2
		opacity-0 group-hover:opacity-100 transition-all duration-300"
		style="background-image: radial-gradient(ellipse farthest-side at top right, rgba(0, 0, 0, .4), transparent);"
	>
		<span class="px-2 text-white text-2xs">{dimensions}</span>
	</div>

	<div class="
		absolute inset-0 rounded-lg group-focus:inset-shadow-light
		transition-all duration-300
		{isSelected ? 'inset-shadow-hard opacity-100' : ''}
	"></div>
</button>
