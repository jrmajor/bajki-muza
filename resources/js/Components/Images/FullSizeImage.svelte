<script lang="ts">
	import { tick, onMount } from 'svelte';

	let { src, alt }: {
		src: string;
		alt: string;
	} = $props();

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
	loading="lazy"
	class={{
		'size-full': true,
		'opacity-0': isHidden,
		'transition-opacity duration-300': transitionClass,
	}}
	{src}
	{alt}
>
