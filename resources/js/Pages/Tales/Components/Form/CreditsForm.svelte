<script lang="ts">
	import type { Writable } from 'svelte/store';
	import { type InertiaForm } from '@inertiajs/svelte';
	import type { CreditType } from '@/types/tales';
	import { creditLabels } from '@/helpers/creditLabels';
	import randomKey from '@/helpers/randomKey';
	import ArtistPicker from '@/Components/ComboBox/ArtistPicker.svelte';

	let { form }: {
		form: Writable<InertiaForm<{
			credits: Array<{
				artist: string;
				type: CreditType;
				as: string | null;
				nr: number | null;
				key: string;
			}>;
		}>>;
	} = $props();

	function addCredit() {
		$form.credits = [...$form.credits, { artist: '', type: 'text', as: '', nr: 0, key: randomKey() }];
	}

	function removeCredit(index: number) {
		$form.credits = [...$form.credits.slice(0, index), ...$form.credits.slice(index + 1)];
	}
</script>

<div class="flex flex-col">
	<div class="relative -space-y-1 mb-0.5">
		<span class="w-full font-medium text-gray-700 dark:text-gray-400">Solidna robota</span>
		<div class="flex gap-2 items-center w-full">
			<div class="px-1 w-1/2 shrink-1"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Artysta</span></div>
			<div class="shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Robota</span></div>
			<div class="shrink-0 px-1 w-1/4"><span class="w-full text-xs font-medium text-gray-700 dark:text-gray-400">Jako</span></div>
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
				<div class="w-1/2 shrink-1">
					<ArtistPicker bind:value={credit.artist}/>
				</div>
				<div class="shrink-0 w-1/4">
					<select bind:value={credit.type} class="w-full form-select">
						{#each Object.entries(creditLabels) as [type, label]}
							<option value={type}>{label}</option>
						{/each}
					</select>
				</div>
				<div class="shrink-0 w-1/4">
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
