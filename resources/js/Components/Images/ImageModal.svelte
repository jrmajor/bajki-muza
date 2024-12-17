<script lang="ts">
	import { onDestroy } from 'svelte';
	import { DEV } from 'esm-env';
	import FullSizeImage from './FullSizeImage.svelte';

	let {
		isOpen = $bindable(false),
		placeholder,
		url,
		width,
		height,
		alt,
	}: {
		isOpen: boolean;
		placeholder: string;
		url: string;
		width: number;
		height: number;
		alt: string;
	} = $props();

	let dialog: HTMLDialogElement = $state()!;
	let imageWrapper: HTMLDivElement = $state()!;
	let closeButton: HTMLButtonElement = $state()!;

	export function open() {
		document.startViewTransition(() => {
			isOpen = true;
			dialog.showModal();
		});
	}

	function close() {
		document.startViewTransition(() => {
			isOpen = false;
			dialog.close();
		});
	}

	function onbodyclick(event: MouseEvent) {
		if (!dialog.contains(event.target as Node)) return;
		if (imageWrapper.contains(event.target as Node)) return;
		if (closeButton.contains(event.target as Node)) return;
		close();
	}

	if (DEV) {
		// prevent dialog from being closed after HMR refresh
		const itervalId = setInterval(() => {
			if (isOpen && !dialog.open) dialog.showModal();
		}, 100);
		onDestroy(() => clearInterval(itervalId));
	}
</script>

<svelte:document
	onclick={onbodyclick}
	onkeydown={(e) => {
		if (e.key !== 'Escape') return;
		e.preventDefault();
		close();
	}}
/>

<dialog
	bind:this={dialog}
	class="max-h-full max-w-full overflow-visible bg-transparent p-0 outline-none backdrop:bg-black/90"
>
	<div class="modal-container">
		<div
			bind:this={imageWrapper}
			class="image-wrapper overflow-hidden rounded-xl"
			style:aspect-ratio="{width} / {height}"
			style:--imageWidth="{width}px"
			style:--imageHeight="{height}px"
		>
			<div
				class="h-svh max-h-full w-svw max-w-full bg-cover bg-center"
				style:background-image="url('{placeholder}')"
			>
				<FullSizeImage src={url} {alt}/>
			</div>
		</div>
		<button
			bind:this={closeButton}
			class="close-button select-none rounded-full bg-gray-900 font-black text-brand-lighter outline-none hover:text-brand-primary"
			onclick={close}
		>X</button>
	</div>
</dialog>

<style lang="postcss">
	dialog {
		--closeButtonSize: 2rem;
		--closeButtonGap: 1rem;
		--closeButtonTotalSize: calc(var(--closeButtonSize) + var(--closeButtonGap));
		--closeButtonAddedWidth: 0rem;
		--closeButtonAddedHeight: var(--closeButtonTotalSize);
		--dialogMargin: 2.5rem;

		@media screen(sm) {
			/* doubled, because we add left margin to keep the image centered */
			--closeButtonAddedWidth: calc(var(--closeButtonTotalSize) * 2);
			--closeButtonAddedHeight: 0rem;
			--dialogMargin: 4rem;
		}

		&[open] .image-wrapper {
			view-transition-name: image-modal;
		}
	}

	.modal-container {
		display: flex;
		flex-direction: column;
		align-items: end;
		gap: var(--closeButtonGap);

		@media screen(sm) {
			flex-direction: row;
			align-items: start;
			margin-left: var(--closeButtonTotalSize);
		}
	}

	.image-wrapper {
		max-width: min(var(--imageWidth), 100svw - var(--dialogMargin) - var(--closeButtonAddedWidth));
		max-height: min(var(--imageHeight), 100svh - var(--dialogMargin) - var(--closeButtonAddedHeight));
	}

	.close-button {
		width: var(--closeButtonSize);
		height: var(--closeButtonSize);
	}
</style>
