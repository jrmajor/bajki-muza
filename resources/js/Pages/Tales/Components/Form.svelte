<script lang="ts">
	import { tick } from 'svelte';
	import { route } from 'ziggy-js';
	import { useForm } from '@inertiajs/svelte';
	import prettyBytes from 'pretty-bytes';
	import type { EditResource } from '@/types/tales';
	import Discogs from '@/Components/Icons/Discogs.svelte';
	import ArtistPicker from '@/Components/ArtistPicker.svelte';
	import { creditLabels } from '@/helpers/creditLabels';

	let {
		tale,
		action,
	}: {
		tale: EditResource;
		action: 'create' | 'edit';
	} = $props();

	let form = useForm({
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

	let filesInput: HTMLInputElement;
	let coverFile: File | null = $state(null);
	let coverPreview = $derived(coverFile ? URL.createObjectURL(coverFile) : null);
	$effect(() => {
		$form.cover = coverFile;
		if (coverFile) $form.removeCover = false;
	});
	$effect(() => {
		if ($form.removeCover) {
			deselectFile();
		}
	});

	function deselectFile() {
		filesInput.value = '';
		updateSelectedFile();
	}

	function updateSelectedFile() {
		coverFile = filesInput.files![0] ?? null;
	}

	function addCredit() {
		$form.credits = [...$form.credits, { artist: '', type: 'text', as: '', nr: 0, key: randomKey() }];
	}

	function addActor() {
		$form.actors = [...$form.actors, {
			artist: '',
			characters: '',
			key: randomKey(),
			isDragged: false,
			isDraggedOver: null,
			noTransitions: false,
			hasDeletedElement: null,
		}];
	}

	function removeCredit(index: number) {
		$form.credits = [...$form.credits.slice(0, index), ...$form.credits.slice(index + 1)];
	}

	function removeActor(index: number) {
		$form.actors = [...$form.actors.slice(0, index), ...$form.actors.slice(index + 1)];
	}

	function onDragStart(event: DragEvent, index: number) {
		event.dataTransfer!.setData('index', index.toString());
		$form.actors[index].isDragged = true;
	}

	function onDragEnd(index: number) {
		$form.actors[index].isDragged = false;
	}

	function onDragOver(event: DragEvent, destination: number) {
		event.preventDefault();

		const currentIndex = parseInt(event.dataTransfer!.getData('index'));

		if (currentIndex === destination) return;

		if (currentIndex < destination) {
			$form.actors[destination].isDraggedOver = 'fromAbove';
		} else {
			$form.actors[destination].isDraggedOver = 'fromBelow';
		}
	}

	function onDragLeave(index: number) {
		$form.actors[index].isDraggedOver = null;
	}

	function onDrop(event: DragEvent, destination: number) {
		const currentIndex = parseInt(event.dataTransfer!.getData('index'));

		if (currentIndex === destination) return;

		let actors = $form.actors;

		const priorDestinationElement = actors[destination];

		priorDestinationElement.noTransitions = true;
		priorDestinationElement.isDraggedOver = null;

		const dragged = actors[currentIndex];

		// if the element is dragged from above, insert it below
		// if the element is dragged from below, insert it above
		if (currentIndex < destination) destination += 1;

		actors.splice(destination, 0, dragged);

		// if element was inserted above original location,
		// its index increased by one
		const indexToDelete = destination < currentIndex ? currentIndex + 1 : currentIndex;

		actors.splice(indexToDelete, 1);

		// this element will be used to imitate place after moved element
		// by adding padding, which will then be transitioned back to normal
		const elementNearDeleted = actors[currentIndex];

		elementNearDeleted.noTransitions = true;

		if (currentIndex < destination) {
			elementNearDeleted.hasDeletedElement = 'above';
		} else {
			elementNearDeleted.hasDeletedElement = 'below';
		}

		tick().then(() => {
			elementNearDeleted.noTransitions = false;
			elementNearDeleted.hasDeletedElement = null;

			priorDestinationElement.noTransitions = false;
		});

		actors.forEach((a) => a.isDraggedOver = null);

		$form.actors = actors;
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

	function randomKey(): string {
		return Math.random().toString(20).substring(2);
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
			<label for="title" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Tytuł</label>
			<input type="text" id="title" bind:value={$form.title} class="w-full form-input">
		</div>
		<div class="flex gap-5 w-full sm:w-1/2">
			<div class="flex flex-col items-stretch w-1/2">
				<label for="year" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Rok</label>
				<input type="text" id="year" bind:value={$form.year} class="w-full form-input">
			</div>
			<div class="flex flex-col items-stretch w-1/2">
				<label for="nr" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">№</label>
				<input type="text" id="nr" bind:value={$form.nr} class="w-full form-input">
			</div>
		</div>
	</div>

	<div class="flex flex-col">
		<label for="discogs" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Discogs</label>
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

	<div class="flex flex-col">
		<label for="cover" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Okładka</label>
		<div class="flex gap-5">
			<label class="flex overflow-hidden flex-grow items-center h-10 bg-white rounded-md border cursor-pointer dark:border-gray-900 dark:bg-gray-800">
				<div class="flex-none size-10 bg-placeholder-cover">
					{#if tale.cover}
						<img
							src={tale.cover.url[128]}
							class="object-cover size-10 bg-cover"
							style={`background-image: url("${tale.cover.placeholder}")`}
							class:hidden={coverFile || $form.removeCover}
							alt="Okładka bajki {tale.title}"
						>
					{/if}
					{#if coverPreview}
						<img src={coverPreview} class="object-cover size-10" alt="Okładka bajki {tale.title}">
					{/if}
				</div>
				<span class="py-2 px-3">
					<span>{coverFile ? coverFile.name : 'Wybierz plik'}</span>
					<small class="pl-1 text-xs font-medium">{coverFile ? prettyBytes(coverFile.size) : ''}</small>
				</span>
				{#if coverFile}
					<button type="button" onclick={deselectFile} class="flex-none"></button>
				{/if}
				<input
					type="file"
					id="cover"
					class="hidden"
					bind:this={filesInput}
					onchange={updateSelectedFile}
				>
			</label>
			{#if action === 'edit'}
				{#if $form.removeCover}
					<button
						type="button"
						onclick={() => $form.removeCover = false}
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
						onclick={() => $form.removeCover = true}
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
			{/if}
		</div>
	</div>

	<div class="flex flex-col">
		<div class="relative -space-y-1 mb-0.5">
			<span class="w-full font-medium text-gray-700 dark:text-gray-400">Solidna robota</span>
			<div class="flex gap-2 items-center w-full">
				<div class="px-1 w-1/2 flex-shrink-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
				<div class="flex-shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Robota</span></div>
				<div class="flex-shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Jako</span></div>
				<div class="px-1 w-8 flex-0"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
				<div class="w-5"></div>
			</div>
			<div class="flex absolute top-0 right-0 items-center h-full">
				<button
					type="button"
					onclick={addCredit}
					class="flex justify-center items-center size-5 text-green-100 bg-green-500 rounded-full dark:bg-green-600 focus:bg-green-700"
				>
					<span>+</span>
				</button>
			</div>
		</div>
		<div class="flex flex-wrap gap-1.5 w-full">
			{#each $form.credits as credit, index (credit.key)}
				<div class="flex items-center gap-2 w-full">
					<div class="w-1/2 flex-shrink-1">
						<ArtistPicker bind:value={credit.artist}/>
					</div>
					<div class="flex-shrink-0 w-1/4">
						<select bind:value={credit.type} class="w-full form-select">
							{#each Object.entries(creditLabels) as [type, label]}
								<option value={type}>{label}</option>
							{/each}
						</select>
					</div>
					<div class="flex-shrink-0 w-1/4">
						<input type="text" bind:value={credit.as} class="w-full form-input">
					</div>
					<div class="flex justify-center items-center self-stretch w-8 flex-0">
						<input type="text" bind:value={credit.nr} class="w-8 px-1.5 py-1.5 text-center text-sm font-bold form-input">
					</div>
					<button
						type="button"
						onclick={() => removeCredit(index)}
						class="flex flex-none justify-center items-center size-5 text-red-100 bg-red-500 rounded-full dark:bg-red-600 focus:bg-red-700"
					>
						<span>-</span>
					</button>
				</div>
			{/each}
		</div>
	</div>

	<div class="flex flex-col">
		<div class="relative -space-y-1 mb-0.5">
			<span class="w-full font-medium text-gray-700 dark:text-gray-400">Obsada</span>
			<div class="flex items-center gap-2 w-full">
				<div class="px-1 w-6 flex-0"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">№</span></div>
				<div class="px-1 w-1/2"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
				<div class="px-1 w-1/2"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Postaci</span></div>
				<div class="w-5"></div>
			</div>
			<div class="flex absolute top-0 right-0 items-center h-full">
				<button
					type="button"
					onclick={addActor}
					class="flex justify-center items-center size-5 text-green-100 bg-green-500 rounded-full dark:bg-green-600 focus:bg-green-700"
				>
					<span>+</span>
				</button>
			</div>
		</div>
		<div class="w-full flex gap-1.5 flex-wrap">
			{#each $form.actors as actor, index (actor.key)}
				<!-- svelte-ignore a11y_no_static_element_interactions -->
				<div
					class="flex items-center gap-2 w-full"
					class:opacity-0={actor.isDragged}
					class:pt-12={actor.isDraggedOver === 'fromBelow' || actor.hasDeletedElement === 'above'}
					class:pb-12={actor.isDraggedOver === 'fromAbove' || actor.hasDeletedElement === 'below'}
					class:transition-all={!actor.noTransitions}
					class:duration-300={!actor.noTransitions}
					draggable="true"
					ondragstart={(e) => onDragStart(e, index)}
					ondragend={() => onDragEnd(index)}
					ondragover={(e) => onDragOver(e, index)}
					ondragleave={() => onDragLeave(index)}
					ondrop={(e) => onDrop(e, index)}
				>
					<div class="flex justify-center items-center self-stretch w-6 flex-0">
						<span class="text-sm font-bold text-gray-800 select-none">{index + 1}</span>
					</div>
					<div class="w-1/2">
						<ArtistPicker bind:value={actor.artist}/>
					</div>
					<div class="w-1/2">
						<input type="text" bind:value={actor.characters} class="w-full form-input">
					</div>
					<button
						type="button"
						onclick={() => removeActor(index)}
						class="flex flex-none justify-center items-center size-5 text-red-100 bg-red-500 rounded-full dark:bg-red-600 focus:bg-red-700"
					>
						<span>-</span>
					</button>
				</div>
			{/each}
		</div>
	</div>

	<div class="flex flex-col w-full">
		<label for="notes" class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">Notatki</label>
		<textarea bind:value={$form.notes} id="notes" rows="5" class="w-full form-input"></textarea>
	</div>

	<button class="self-center py-2 px-4 text-sm font-medium bg-white rounded-full shadow-md dark:bg-gray-800">
		Zapisz
	</button>
</form>
