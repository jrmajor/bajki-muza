<script lang="ts">
	import { onMount } from 'svelte';
	import resizedImageUrl from '@/helpers/resizedImageUrl';

	let {
		src,
		size,
		imageSize = null,
		alt,
		loading = 'lazy',
		class: className = '',
	}: {
		src: string;
		size: 'full' | number;
		imageSize?: number | null;
		loading?: 'lazy' | 'eager';
		alt: string;
		class?: string;
	} = $props();

	const calculatedImageSize = $derived(imageSize ?? (typeof size === 'number' ? size * 4 : 0));

	let element: HTMLImageElement;
	let isLoaded = $state(false);

	onMount(() => {
		if (element.complete) isLoaded = true;
	});
</script>

<img
	bind:this={element}
	onload={() => isLoaded = true}
	{loading}
	class="size-{size} object-center object-cover transition-opacity duration-300 {className}"
	class:opacity-0={!isLoaded}
	src={resizedImageUrl(src, calculatedImageSize * 2)}
	srcset="
		{resizedImageUrl(src, calculatedImageSize)} 1x,
		{resizedImageUrl(src, calculatedImageSize * 1.5)} 1.5x,
		{resizedImageUrl(src, calculatedImageSize * 2)} 2x
	"
	{alt}
>
