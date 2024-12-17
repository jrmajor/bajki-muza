<script module lang="ts">
	import type { Box } from './box';

	export type CropValue = Box;
</script>

<script lang="ts">
	import clamp from '@/helpers/clamp';
	import { round, constrainToBoundary, constrainToRatio } from './box';

	let {
		src,
		crop = $bindable(),
		aspectRatio = null,
	}: {
		src: string;
		crop: CropValue;
		aspectRatio?: number | null;
	} = $props();

	let el: HTMLImageElement;

	type Direction = 'nw' | 'ne' | 'sw' | 'se' | 'n' | 's' | 'w' | 'e';
	type Handle = { name: Direction; position: [number, number]; cursor: string };

	const handles: Handle[] = [
		{ name: 'n', position: [0.5, 0], cursor: 'ns' },
		{ name: 's', position: [0.5, 1], cursor: 'ns' },
		{ name: 'w', position: [0, 0.5], cursor: 'ew' },
		{ name: 'e', position: [1, 0.5], cursor: 'ew' },
		{ name: 'nw', position: [0, 0], cursor: 'nwse' },
		{ name: 'ne', position: [1, 0], cursor: 'nesw' },
		{ name: 'sw', position: [0, 1], cursor: 'nesw' },
		{ name: 'se', position: [1, 1], cursor: 'nwse' },
	];

	function scaled(value: number) {
		if (!el) return value;
		return el.clientWidth / el.naturalWidth * value;
	}

	function unscaled(value: number) {
		return el.naturalWidth / el.clientWidth * value;
	}

	let currentlyDragging: Handle | 'anchor' | null = $state(null);
	let oldCrop: CropValue;
	let dragStart: { x: number; y: number };

	function onmousedown(event: MouseEvent, handle: Handle | 'anchor') {
		currentlyDragging = handle;
		oldCrop = crop;
		dragStart = { x: event.clientX, y: event.clientY };
		event.preventDefault();
	}

	function onmousemove(event: MouseEvent) {
		if (currentlyDragging === 'anchor') {
			onAnchorMove(event);
		} else if (currentlyDragging) {
			onHandleMove(event, currentlyDragging);
		}
	}

	function onmouseup() {
		currentlyDragging = null;
	}

	function onHandleMove(event: MouseEvent, handle: Handle) {
		const { name: direction } = handle;

		const origin: [number, number] = [
			1 - handle.position[0],
			1 - handle.position[1],
		];

		const bounds = el.getBoundingClientRect();
		const mouseX = clamp(bounds.left, event.clientX, bounds.right);
		const mouseY = clamp(bounds.top, event.clientY, bounds.bottom);

		const dragsHorizontal = direction.includes('w') || direction.includes('e');
		const dragsVertical = direction.includes('n') || direction.includes('s');
		const x = dragsHorizontal ? unscaled(mouseX - dragStart.x) : 0;
		const y = dragsVertical ? unscaled(mouseY - dragStart.y) : 0;

		let newBox = {
			x: direction.includes('w') ? oldCrop.x + x : oldCrop.x,
			y: direction.includes('n') ? oldCrop.y + y : oldCrop.y,
			width: oldCrop.width + (direction.includes('e') ? x : -x),
			height: oldCrop.height + (direction.includes('s') ? y : -y),
		};

		if (aspectRatio) {
			const primaryDirection = direction.length === 1
				? direction
				: direction[Math.abs(x) > Math.abs(y) ? 1 : 0];
			const ratioMode = ['w', 'e'].includes(primaryDirection) ? 'height' : 'width';
			newBox = constrainToRatio(newBox, aspectRatio, origin, ratioMode);
		}

		newBox = constrainToBoundary(newBox, el.naturalWidth, el.naturalHeight, origin);

		crop = round(newBox);
	}

	function onAnchorMove(event: MouseEvent) {
		const x = unscaled(event.clientX - dragStart.x);
		const y = unscaled(event.clientY - dragStart.y);

		crop = round({
			x: clamp(0, oldCrop.x + x, el.naturalWidth - oldCrop.width),
			y: clamp(0, oldCrop.y + y, el.naturalHeight - oldCrop.height),
			width: oldCrop.width,
			height: oldCrop.height,
		});
	}

	const cursor = $derived.by(() => {
		if (!currentlyDragging) return;
		if (currentlyDragging === 'anchor') return 'move';
		return `${currentlyDragging.cursor}-resize`;
	});
</script>

<svelte:document {onmousemove} {onmouseup}/>

<div class="cropper" style:cursor>
	<img
		bind:this={el}
		{src}
		alt=""
		class="original"
	>
	<div
		class="border"
		style:top={`${scaled(crop.y) - 1}px`}
		style:left={`${scaled(crop.x) - 1}px`}
		style:width={`${scaled(crop.width) + 2}px`}
		style:height={`${scaled(crop.height) + 2}px`}
	></div>
	<div class="handles">
		{#each handles as handle}
			{@const { position: [x, y], cursor } = handle}
			<!-- svelte-ignore a11y_no_static_element_interactions -->
			<div
				onmousedown={(e) => onmousedown(e, handle)}
				style:cursor="{cursor}-resize"
				style:left="{scaled(crop.x + (crop.width * x))}px"
				style:top="{scaled(crop.y + (crop.height * y))}px"
			></div>
		{/each}
	</div>
	<!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
	<img
		{src}
		alt=""
		onmousedown={(e) => onmousedown(e, 'anchor')}
		class="cropped"
		style:clip="rect({scaled(crop.y)}px, {scaled(crop.x + crop.width)}px, {scaled(crop.y + crop.height)}px, {scaled(crop.x)}px)"
	>
</div>

<style>
	.cropper {
		position: relative;
	}

	.original {
		filter: brightness(0.5);
	}

	.cropped {
		position: absolute;
		top: 0;
		left: 0;
		cursor: move;
	}

	.border {
		position: absolute;
		border: 1px dashed #000;
	}

	.handles > * {
		position: absolute;
		width: 10px;
		height: 10px;
		background-color: #fff;
		border: 1px solid #000;
		border-radius: 5px;
		translate: -50% -50%;
		z-index: 1;
	}
</style>
