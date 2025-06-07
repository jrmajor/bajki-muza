<script lang="ts">
	import { onMount, tick } from 'svelte';
	import { BROWSER } from 'esm-env';
	import resizedImageUrl from '@/helpers/resizedImageUrl';

	let {
		src,
		size = 'full',
		imageSize = null,
		alt,
		eager = false,
		class: className = '',
	}: {
		src: string;
		size?: 'full' | number;
		imageSize?: number | null;
		alt: string;
		eager?: boolean;
		class?: string;
	} = $props();

	const calculatedImageSize = $derived(imageSize ?? (typeof size === 'number' ? size * 4 : 0));

	let element: HTMLImageElement;
	let mountedAt: number;

	let isHidden = $state(BROWSER);
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
	loading={eager ? 'eager' : 'lazy'}
	class={[
		`size-${size} object-center object-cover`,
		isHidden && 'opacity-0',
		transitionClass && 'transition-opacity duration-300',
		className,
	]}
	{src}
	srcset="
		{resizedImageUrl(src, calculatedImageSize)} 1x,
		{resizedImageUrl(src, calculatedImageSize * 1.5)} 1.5x,
		{resizedImageUrl(src, calculatedImageSize * 2)} 2x
	"
	{alt}
>
