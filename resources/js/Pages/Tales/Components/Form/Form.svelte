<script lang="ts">
	import { route } from 'ziggy-js';
	import { useForm } from '@inertiajs/svelte';
	import type { EditResource } from '@/types/tales';
	import randomKey from '@/helpers/randomKey';
	import Discogs from '@/Components/Icons/Discogs.svelte';
	import Label from '@/Components/Form/Label.svelte';
	import CoverForm from './CoverForm.svelte';
	import CreditsForm from './CreditsForm.svelte';
	import ActorsForm from './ActorsForm.svelte';
	import Button from '@/Components/Form/Button.svelte';

	let { tale, action }: {
		tale: EditResource;
		action: 'create' | 'edit';
	} = $props();

	const form = useForm({
		title: tale.title,
		year: tale.year,
		nr: tale.nr,
		discogs: tale.discogs,
		notes: tale.notes,
		cover: null as File | null,
		removeCover: false,
		credits: tale.credits.map((credit) => ({
			...credit,
			key: randomKey(),
		})),
		actors: tale.actors.map((actor) => ({
			...actor,
			key: randomKey(),
			isDragged: false,
			isDraggedOver: null as null | 'fromAbove' | 'fromBelow',
			noTransitions: false,
			hasDeletedElement: null as null | 'above' | 'below',
		})),
	});

	let errors = $derived(Object.values($form.errors));

	let discogsUrl = $derived($form.discogs ? `https://www.discogs.com/release/${$form.discogs}` : null);

	function updatedDiscogs() {
		const id = String($form.discogs).match(/discogs\.com\/(?:.*\/)?release\/([0-9]+)/);
		if (id !== null) $form.discogs = parseInt(id[1]);
	}

	function onsubmit(event: SubmitEvent) {
		event.preventDefault();

		$form.transform((data) => ({
			_method: action === 'create' ? 'post' : 'put',
			title: data.title,
			year: data.year,
			nr: data.nr,
			discogs: data.discogs,
			notes: data.notes,
			cover: data.cover,
			remove_cover: data.removeCover,
			credits: data.credits.map((credit) => ({
				artist: credit.artist,
				type: credit.type,
				as: credit.as,
				nr: credit.nr,
			})),
			actors: data.actors.map((actor, index) => ({
				credit_nr: index,
				artist: actor.artist,
				characters: actor.characters,
			})),
		})).post(
			action === 'create'
				? route('tales.store')
				: route('tales.update', { tale }),
		);
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

	<div class="flex flex-col gap-2 sm:flex-row sm:gap-5">
		<div class="flex flex-col w-full sm:w-1/2">
			<Label for="title">Tytuł</Label>
			<input type="text" id="title" bind:value={$form.title} class="w-full form-input">
		</div>
		<div class="flex gap-5 w-full sm:w-1/2">
			<div class="flex flex-col items-stretch w-1/2">
				<Label for="year">Rok</Label>
				<input type="text" id="year" bind:value={$form.year} class="w-full form-input">
			</div>
			<div class="flex flex-col items-stretch w-1/2">
				<Label for="nr">№</Label>
				<input type="text" id="nr" bind:value={$form.nr} class="w-full form-input">
			</div>
		</div>
	</div>

	<div class="flex flex-col">
		<Label for="discogs">Discogs</Label>
		<div class="flex gap-5 items-center">
			<input
				type="text"
				id="discogs"
				bind:value={$form.discogs}
				oninput={updatedDiscogs}
				class="w-full form-input"
			>
			{#if discogsUrl}
				<div class="flex-grow-0">
					<a href={discogsUrl} target="_blank"><Discogs/></a>
				</div>
			{/if}
		</div>
	</div>

	<CoverForm currentCover={tale.cover} {form} {action}/>

	<CreditsForm {form}/>

	<ActorsForm {form}/>

	<div class="flex flex-col w-full">
		<Label for="notes">Notatki</Label>
		<textarea bind:value={$form.notes} id="notes" rows="5" class="w-full form-input"></textarea>
	</div>

	<Button class="self-center">Zapisz</Button>
</form>
