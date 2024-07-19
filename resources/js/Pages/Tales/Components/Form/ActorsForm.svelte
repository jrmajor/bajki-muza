<script lang="ts">
	import { tick } from 'svelte';
	import { type InertiaForm } from '@inertiajs/svelte';
	import randomKey from '@/helpers/randomKey';
	import ArtistPicker from '@/Components/ComboBox/ArtistPicker.svelte';

	let { form }: {
		form: InertiaForm<{
			actors: Array<{
				artist: string;
				characters: string | null;
				key: string;
				isDragged: boolean;
				isDraggedOver: null | 'fromAbove' | 'fromBelow';
				noTransitions: boolean;
				hasDeletedElement: null | 'above' | 'below';
			}>;
		}>;
	} = $props();

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

	function removeActor(index: number) {
		$form.actors = [...$form.actors.slice(0, index), ...$form.actors.slice(index + 1)];
	}

	const actorDragId = randomKey();

	function onDragStart(event: DragEvent, index: number) {
		event.dataTransfer!.setData('dragId', actorDragId);
		event.dataTransfer!.setData('index', index.toString());
		$form.actors[index].isDragged = true;
	}

	function onDragEnd(index: number) {
		$form.actors[index].isDragged = false;
	}

	function onDragOver(event: DragEvent, destination: number) {
		if (event.dataTransfer!.getData('dragId') !== actorDragId) return;

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
		if (event.dataTransfer!.getData('dragId') !== actorDragId) return;

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
</script>

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
