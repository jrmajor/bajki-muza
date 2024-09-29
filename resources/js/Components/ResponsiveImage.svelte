<script lang="ts">
	import { tick, onMount } from 'svelte';
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
	let mountedAt: number;

	let isHidden = $state(true);
	let transitionClass = $state(false);

	onMount(() => {
		mountedAt = performance.now();
		if (element.complete) unhideImage();
	});

	async function unhideImage() {
		const msSinceMounted = performance.now() - mountedAt;
		if (msSinceMounted > 50) {
			transitionClass = true;
			await tick();
		}

		isHidden = false;
	}
</script>

<img
	bind:this={element}
	onload={unhideImage}
	{loading}
	class="
		size-{size} object-center object-cover {className}
		{ transitionClass ? 'transition-opacity duration-300' : '' }
	"
	class:opacity-0={isHidden}
	src={resizedImageUrl(src, calculatedImageSize * 2)}
	srcset="
		{resizedImageUrl(src, calculatedImageSize)} 1x,
		{resizedImageUrl(src, calculatedImageSize * 1.5)} 1.5x,
		{resizedImageUrl(src, calculatedImageSize * 2)} 2x
	"
	{alt}
>
