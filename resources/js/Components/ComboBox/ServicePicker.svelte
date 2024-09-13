<script lang="ts">
	import { route } from 'ziggy-js';
	import ComboBox from './ComboBox.svelte';

	let {
		service,
		value = $bindable(),
	}: {
		service: 'discogs' | 'filmPolski' | 'wikipedia';
		value: any;
	} = $props();

	async function getResults(value: string) {
		let response;
		if (service === 'discogs') {
			response = await fetch(route('ajax.discogs', { search: value }));
		} else if (service === 'filmPolski') {
			response = await fetch(route('ajax.filmPolski', { search: value }));
		} else {
			response = await fetch(route('ajax.wikipedia', { search: value }));
		}

		let json = await response.json() as Array<{ id: any; name: string }>;
		return json.map((a) => ({ label: a.name, value: a.id }));
	}
</script>

<ComboBox id={service} bind:value {getResults} minSearchLength={5}/>
