<script lang="ts">
	import { route } from 'ziggy-js';
	import ComboBox from './ComboBox.svelte';

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

<ComboBox bind:value {searchUsing} id={service}/>
