<script lang="ts">
	import { route } from 'ziggy-js';
	import { useForm } from '@inertiajs/svelte';
	import type { EditResource } from '@/types/artists';
	import Label from '@/Components/Form/Label.svelte';
	import Button from '@/Components/Form/Button.svelte';
	import ServicePicker from '@/Components/Pickers/ServicePicker.svelte';
	import PhotoForm from './PhotoForm.svelte';
	import FormExternalLinks from './FormExternalLinks.svelte';
	import FormExternalPhotos from './FormExternalPhotos.svelte';

	let { artist }: { artist: EditResource } = $props();

	let form = useForm({
		name: artist.name,
		genetivus: artist.genetivus,
		discogs: artist.discogs,
		filmpolski: artist.filmpolski,
		wikipedia: artist.wikipedia,
		photo: {
			file: null as File | null,
			url: null as string | null,
			crop: artist.photo?.crop
			?? { face: { x: 0, y: 0, size: 0 }, image: { x: 0, y: 0, width: 0, height: 0 } },
			remove: false,
			source: artist.photo?.source ?? null,
			grayscale: artist.photo?.grayscale ?? true,
		},
	});

	let errors = $derived(Object.values($form.errors));

	let photoForm = $state() as PhotoForm;

	function onsubmit(event: SubmitEvent) {
		event.preventDefault();

		$form.transform((data) => ({
			_method: 'put',
			name: data.name,
			genetivus: data.genetivus,
			discogs: data.discogs,
			filmpolski: data.filmpolski,
			wikipedia: data.wikipedia,
			photo: data.photo.file,
			photo_uri: data.photo.url,
			photo_crop: data.photo.crop,
			remove_photo: data.photo.remove,
			photo_source: data.photo.source,
			photo_grayscale: data.photo.grayscale,
		})).post(route('artists.update', { artist }));
	}
</script>

<form {onsubmit} class="flex flex-col gap-5">
	{#if errors.length}
		<ul class="text-red-700">
			{#each errors as error}
				<li>{error}</li>
			{/each}
		</ul>
	{/if}

	<div class="flex flex-col">
		<Label for="name">Imię i nazwisko</Label>
		<input type="text" id="name" bind:value={$form.name} class="w-full form-input">
	</div>

	<div class="flex flex-col">
		<Label for="genetivus">Dopełniacz</Label>
		<input type="text" id="genetivus" bind:value={$form.genetivus} class="w-full form-input">
	</div>

	<div class="flex flex-col gap-2 sm:flex-row sm:gap-5">
		<div class="flex gap-5 w-full sm:w-1/2">
			<div class="flex flex-col items-stretch w-1/2">
				<Label for="discogs">Discogs</Label>
				<ServicePicker service="discogs" bind:value={$form.discogs}/>
			</div>

			<div class="flex flex-col items-stretch w-1/2">
				<Label for="filmPolski">Film Polski</Label>
				<ServicePicker service="filmPolski" bind:value={$form.filmpolski}/>
			</div>
		</div>

		<div class="flex flex-col w-full sm:w-1/2">
			<Label for="wikipedia">Wikipedia</Label>
			<ServicePicker service="wikipedia" bind:value={$form.wikipedia}/>
		</div>
	</div>

	<PhotoForm bind:this={photoForm} currentPhoto={artist.photo} {form}/>

	<Button class="self-center">Zapisz</Button>

	<div class="flex flex-col gap-3 items-center">
		<FormExternalLinks name={$form.name}/>
		<!-- todo: load them dynamically -->
		<FormExternalPhotos
			discogsPhotos={artist.discogsPhotos}
			filmPolskiPhotos={artist.filmPolskiPhotos}
			currentPhotoUrl={$form.photo.url}
			onclick={photoForm.setPhotoUri}
		/>
	</div>
</form>
