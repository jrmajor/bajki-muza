<script lang="ts" generics="Service extends ('discogs' | 'filmPolski' | 'wikipedia')">
	import { route } from 'ziggy-js';
	import ComboBox from './ComboBox.svelte';

	let {
		service,
		value = $bindable(),
	}: {
		service: Service;
		value: Value | null;
	} = $props();

	type Value = Service extends 'wikipedia' ? string : number;

	async function getResults(value: string) {
		// do not use `ajax.${service}` to keep typescript happy
		let response;
		if (service === 'discogs') {
			response = await fetch(route('ajax.discogs', { search: value }));
		} else if (service === 'filmPolski') {
			response = await fetch(route('ajax.filmPolski', { search: value }));
		} else {
			response = await fetch(route('ajax.wikipedia', { search: value }));
		}

		let json = await response.json() as Array<{ id: Value; name: string }>;
		return json.map((a) => ({ label: a.name, value: a.id }));
	}
</script>

<ComboBox id={service} bind:value {getResults} minSearchLength={5}/>
