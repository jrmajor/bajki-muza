<script lang="ts">
	import type { Writable } from 'svelte/store';
	import { type InertiaForm } from '@inertiajs/svelte';
	import prettyBytes from 'pretty-bytes';
	import type { CoverResource } from '@/types/tales';
	import Label from '@/Components/Form/Label.svelte';

	let { currentCover, form, action }: {
		currentCover: CoverResource | null;
		form: Writable<InertiaForm<{
			removeCover: boolean;
			cover: File | null;
		}>>;
		action: 'create' | 'edit';
	} = $props();

	let filesInput: HTMLInputElement;
	let file: File | null = $state(null);

	let previewUrl = $derived.by(() => {
		if ($form.removeCover) return null;
		if (file) return URL.createObjectURL(file);
		return currentCover?.url[128] ?? null;
	});

	$effect(() => {
		$form.cover = file;
		if (file) $form.removeCover = false;
	});

	$effect(() => {
		if ($form.removeCover) deselectFile();
	});

	function deselectFile() {
		filesInput.value = '';
		updateSelectedFile();
	}

	function updateSelectedFile() {
		file = filesInput.files![0] ?? null;
	}
</script>

<div class="flex flex-col">
	<Label for="cover">Okładka</Label>
	<div class="flex gap-5">
		<label class="flex overflow-hidden flex-grow items-center h-10 bg-white rounded-md border cursor-pointer dark:border-gray-900 dark:bg-gray-800">
			<div class="flex-none size-10 bg-placeholder-cover">
				{#if previewUrl}
					<img
						src={previewUrl}
						class="object-cover size-10"
						alt={file ? 'Przesłana okładka' : 'Aktualna okładka'}
					>
				{/if}
			</div>
			<span class="py-2 px-3">
				<span>{file ? file.name : 'Wybierz plik'}</span>
				<small class="pl-1 text-xs font-medium">{file ? prettyBytes(file.size) : ''}</small>
			</span>
			<input
				bind:this={filesInput}
				type="file"
				id="cover"
				class="hidden"
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
