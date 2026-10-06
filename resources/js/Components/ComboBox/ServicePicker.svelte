<script module lang="ts">
	type Service = 'discogs' | 'filmPolski' | 'wikipedia';
</script>

<script lang="ts" generics="TService extends Service">
	import { useHttp } from '@inertiajs/svelte';
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

	let http = useHttp<{ search: string }, Array<{ id: IdTypeMap[TService], name: string }>>({ search: '' });

	async function getResults(value: string) {
		let endpoint: `ajax.${Service}` = `ajax.${service}`;
		http.search = value;
		let results = await http.get(route(endpoint));
		return results.map((a) => ({ label: a.name, value: a.id }));
	}
</script>

<ComboBox id={service} bind:value {getResults} minSearchLength={5} allowsAnyString={false}/>
