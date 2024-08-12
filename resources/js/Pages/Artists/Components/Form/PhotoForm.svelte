<script lang="ts">
	import { tick } from 'svelte';
	import { type InertiaForm } from '@inertiajs/svelte';
	import prettyBytes from 'pretty-bytes';
	import type { EditPhotoResource, ArtistPhotoCrop } from '@/types/artists';
	import Label from '@/Components/Form/Label.svelte';
	import Cropper, { type CropValue } from '@/Components/Cropper.svelte';

	let { currentPhoto, form }: {
		currentPhoto: EditPhotoResource | null;
		form: InertiaForm<{
			photo: {
				file: File | null;
				url: string | null;
				crop: ArtistPhotoCrop;
				remove: boolean;
				source: string | null;
				grayscale: boolean;
			};
		}>;
	} = $props();

	let activePicker: Picker = $state({ type: 'current' });

	type Picker =
		| { type: 'current' }
		| { type: 'upload'; file: File }
		| { type: 'remove' }
		| { type: 'uri'; uri: string; uriFrom: 'discogs' | 'filmpolski' };

	$effect(() => {
		$form.photo.file = activePicker.type === 'upload' ? activePicker.file : null;
		$form.photo.url = activePicker.type === 'uri' ? activePicker.uri : null;
		$form.photo.remove = activePicker.type === 'remove';
	});

	$effect(() => {
		if (activePicker.type === 'remove') $form.photo.source = null;
		$form.photo.grayscale = activePicker.type === 'current' ? (currentPhoto?.grayscale ?? true) : true;
	});

	let faceCrop = $state() as CropValue;

	$effect(() => {
		if (!faceCrop) return;
		$form.photo.crop.face.x = faceCrop.x;
		$form.photo.crop.face.y = faceCrop.y;
		$form.photo.crop.face.size = faceCrop.width;
	});

	export function setPhotoUri(uri: string, source: 'discogs' | 'filmpolski') {
		if (activePicker.type === 'uri' && activePicker.uri === uri) {
			return resetPickerToCurrent();
		}

		activePicker = { type: 'uri', uri, uriFrom: source };
		$form.photo.source = source;
		$form.photo.grayscale = true;
	}

	let filesInput: HTMLInputElement;

	function updateSelectedFile() {
		let file = filesInput.files![0] ?? null;

		if (file) {
			activePicker = { type: 'upload', file };
			$form.photo.source = null;
			$form.photo.grayscale = true;
		} else {
			resetPickerToCurrent();
		}
	}

	function resetPickerToCurrent() {
		activePicker = { type: 'current' };

		$form.photo.source = currentPhoto?.source ?? null;
		$form.photo.grayscale = currentPhoto?.grayscale ?? true;
	}

	function setPickerToRemove() {
		activePicker = { type: 'remove' };

		$form.photo.source = null;
		$form.photo.grayscale = true;
	}

	let previewUrl = $derived.by(() => {
		if (activePicker.type === 'current') return currentPhoto?.url ?? null;
		if (activePicker.type === 'upload') return URL.createObjectURL(activePicker.file);
		if (activePicker.type === 'uri') return activePicker.uri;
		return null;
	});

	let labelText = $derived.by(() => {
		if (activePicker.type === 'upload') return activePicker.file!.name;
		if (activePicker.type === 'uri') {
			if (activePicker.uriFrom === 'discogs') return 'Wybrano z Discogsa';
			if (activePicker.uriFrom === 'filmpolski') return 'Wybrano z FilmuPolskiego';
		}
		return 'Wybierz plik';
	});

	let showCropper = $state(true);

	$effect(() => {
		previewUrl;
		showCropper = false;
		// cropper does not support updating src
		tick().then(() => showCropper = true);
	});
</script>

<div class="flex flex-col gap-3">
	<div class="flex flex-col">
		<Label for="photo">Zdjęcie</Label>
		<div class="flex gap-5">
			<label class="flex overflow-hidden flex-grow items-center h-10 bg-white rounded-md border cursor-pointer dark:border-gray-900 dark:bg-gray-800">
				<div class="flex-none size-10 bg-placeholder-artist">
					{#if previewUrl}
						<img
							src={previewUrl}
							class="object-cover size-10"
							alt={activePicker.type === 'current' ? 'Aktualne zdjęcie' : 'Przesłane zdjęcie'}
						>
					{/if}
				</div>
				<span class="py-2 px-3">
					<span>{labelText}</span>
					<small class="pl-1 text-xs font-medium">
						{activePicker.type === 'upload' ? prettyBytes(activePicker.file.size) : ''}
					</small>
				</span>
				<input
					bind:this={filesInput}
					type="file"
					id="photo"
					class="hidden"
					onchange={updateSelectedFile}
				>
			</label>
			{#if activePicker.type === 'remove'}
				<button
					type="button"
					onclick={resetPickerToCurrent}
					class="
						flex-none px-3 py-2 bg-red-600 text-red-100 rounded-md border-red-600 font-medium text-sm
						hover:bg-red-500 hover:border-red-500 hover:text-white
						active:bg-white active:text-black
						dark:active:bg-gray-800 dark:active:text-gray-100 dark:border-gray-900
						transition-colors duration-150
					"
				>Nie usuwaj</button>
			{:else}
				<button
					type="button"
					onclick={setPickerToRemove}
					class="
						flex-none px-3 py-2 bg-white rounded-md border font-medium text-sm
						hover:bg-red-100 hover:text-red-700
						active:bg-red-600 active:cover-red-600 active:text-red-100
						dark:bg-gray-800 dark:text-gray-100 dark:border-gray-900
						dark:hover:bg-red-800 dark:hover:text-red-100
						transition-colors duration-150
					"
				>Usuń</button>
			{/if}
		</div>
	</div>

	<div class="flex flex-row gap-5 items-center">
		<div class="flex flex-row flex-grow gap-2 items-center">
			<Label for="photo-source" inline small>Źródło</Label>
			<input type="text" id="photo-source" bind:value={$form.photo.source} class="py-1 px-2 w-full text-sm form-input">
		</div>

		<div class="flex flex-row flex-none items-center">
			<Label for="photo-grayscale" inline small>Cz-B.</Label>
			<input type="checkbox" id="photo-grayscale" bind:checked={$form.photo.grayscale} class="rounded border-gray-300">
		</div>
	</div>

	{#if activePicker.type !== 'remove' && previewUrl && showCropper}
		<div class="flex gap-5 justify-center items-center">
			<div class="flex justify-end max-w-1/2">
				<Cropper
					src={previewUrl}
					bind:crop={faceCrop}
					aspectRatio={1}
					minSize={[50, 50, 'px']}
					startSize={[100, 100, '%']}
					startCrop={activePicker.type === 'current'
						? {
							x: currentPhoto!.crop.face.x,
							y: currentPhoto!.crop.face.y,
							width: currentPhoto!.crop.face.size,
							height: currentPhoto!.crop.face.size,
						}
						: null}
				/>
			</div>
			<table>
				<tbody>
					<tr>
						<td class="px-1 text-sm font-medium text-right">x:</td>
						<td class="px-1">{$form.photo.crop.face.x}</td>
					</tr>
					<tr>
						<td class="px-1 text-sm font-medium text-right">y:</td>
						<td class="px-1">{$form.photo.crop.face.y}</td>
					</tr>
				</tbody>
			</table>
			<table>
				<tbody>
					<tr>
						<td class="px-1 text-sm font-medium text-right">width:</td>
						<td class="px-1">{$form.photo.crop.face.size}</td>
					</tr>
					<tr>
						<td class="px-1 text-sm font-medium text-right">height:</td>
						<td class="px-1">{$form.photo.crop.face.size}</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="flex gap-5 justify-center items-center">
			<div class="flex justify-end max-w-1/2">
				<Cropper
					src={previewUrl}
					bind:crop={$form.photo.crop.image}
					minSize={[50, 50, 'px']}
					startSize={[100, 100, '%']}
					startCrop={activePicker.type === 'current' ? currentPhoto!.crop.image : null}
				/>
			</div>
			<table>
				<tbody>
					<tr>
						<td class="px-1 text-sm font-medium text-right">x:</td>
						<td class="px-1">{$form.photo.crop.image.x}</td>
					</tr>
					<tr>
						<td class="px-1 text-sm font-medium text-right">y:</td>
						<td class="px-1">{$form.photo.crop.image.y}</td>
					</tr>
				</tbody>
			</table>
			<table>
				<tbody>
					<tr>
						<td class="px-1 text-sm font-medium text-right">width:</td>
						<td class="px-1">{$form.photo.crop.image.width}</td>
					</tr>
					<tr>
						<td class="px-1 text-sm font-medium text-right">height:</td>
						<td class="px-1">{$form.photo.crop.image.height}</td>
					</tr>
				</tbody>
			</table>
		</div>
	{/if}
</div>
