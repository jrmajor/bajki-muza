<script lang="ts">
	import { route } from 'ziggy-js';
	import Picker from './Picker.svelte';

	let { value = $bindable() }: { value: string } = $props();

	async function searchUsing(value: string) {
		let response = await fetch(route('ajax.artists', { search: value }));
		let json = await response.json() as string[];
		return json.map((a) => ({ label: a, value: a }));
	}
</script>

<Picker bind:value {searchUsing}/>
