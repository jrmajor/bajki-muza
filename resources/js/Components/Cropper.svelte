<script context="module" lang="ts">
	import Croppr from 'croppr';

	export type CropValue = Croppr.CropValue;
</script>

<script lang="ts">
	import { onDestroy } from 'svelte';
	import Cropper from '@/helpers/cropper';

	let {
		src,
		crop = $bindable(),
		aspectRatio = null,
		minSize,
		startSize,
		startCrop,
	}: {
		src: string;
		crop: CropValue;
		aspectRatio?: number | null;
		minSize: [number, number, 'px' | '%'];
		startSize: [number, number, 'px' | '%'];
		startCrop: CropValue | null;
	} = $props();

	let performedInitalResize = $state(false);
	let cropper: Cropper;

	function onload(event: Event) {
		let dynamicOptions = aspectRatio ? { aspectRatio } : {};

		cropper = new Cropper(event.target as HTMLImageElement, {
			minSize: minSize,
			startSize: startSize,
			onInitialize: (c: Cropper) => {
				if (startCrop) {
					c.resizeToScaled(startCrop.width, startCrop.height);
					c.moveToScaled(startCrop.x, startCrop.y);
				}
				performedInitalResize = true;
			},
			onCropMove: oncropchanged,
			onCropEnd: oncropchanged,
			...dynamicOptions,
		});
	}

	function oncropchanged(newCrop: CropValue) {
		if (!performedInitalResize) return;
		crop = newCrop;
	}

	onDestroy(() => {
		if (cropper) cropper.destroy();
	});
</script>

<!-- svelte-ignore a11y_missing_attribute -->
<img {src} {onload}>
