<script lang="ts">
	import { route } from 'ziggy-js';
	import Picker from './Picker.svelte';

	let {
		service,
		value = $bindable(),
	}: {
		service: 'discogs' | 'filmPolski' | 'wikipedia';
		value: string | number | null;
	} = $props();

	async function searchUsing(value: string) {
		let response = await fetch(route(`ajax.${service}`, { search: value }));
		let json = await response.json() as Array<{ id: number | string; name: string }>;
		return json.map((a) => ({ label: a.name, value: String(a.id) }));
	}
</script>

<Picker bind:value {searchUsing} id={service}/>
