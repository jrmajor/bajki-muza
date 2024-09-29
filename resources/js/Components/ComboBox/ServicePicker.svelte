<script module lang="ts">
	type Service = 'discogs' | 'filmPolski' | 'wikipedia';
</script>

<script lang="ts" generics="TService extends Service">
	import { route } from 'ziggy-js';
	import ComboBox from './ComboBox.svelte';

	let {
		service,
		value = $bindable(),
	}: {
		service: TService;
		value: IdTypeMap[TService] | null;
	} = $props();

	type IdTypeMap = {
		discogs: number;
		filmPolski: number;
		wikipedia: string;
	};

	async function getResults(value: string) {
		let endpoint: `ajax.${Service}` = `ajax.${service}`;
		let response = await fetch(route(endpoint, { search: value }));
		let json = await response.json() as Array<{ id: IdTypeMap[TService]; name: string }>;
		return json.map((a) => ({ label: a.name, value: a.id }));
	}
</script>

<ComboBox id={service} bind:value {getResults} minSearchLength={5} allowsAnyString={false}/>
