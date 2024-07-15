<script lang="ts">
	import { type InertiaForm } from '@inertiajs/svelte';
	import prettyBytes from 'pretty-bytes';
	import type { EditResource } from '@/types/tales';

	let {
		tale,
		form,
		action,
	}: {
		tale: EditResource;
		form: InertiaForm<{
			removeCover: boolean;
			cover: File | null;
		}>;
		action: 'create' | 'edit';
	} = $props();

	let filesInput: HTMLInputElement;
	let coverFile: File | null = $state(null);
	let coverPreview = $derived(coverFile ? URL.createObjectURL(coverFile) : null);

	$effect(() => {
		$form.cover = coverFile;
		if (coverFile) $form.removeCover = false;
	});

	$effect(() => {
		if ($form.removeCover) deselectFile();
	});

	function deselectFile() {
		filesInput.value = '';
		updateSelectedFile();
	}

	function updateSelectedFile() {
		coverFile = filesInput.files![0] ?? null;
	}
</script>

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
