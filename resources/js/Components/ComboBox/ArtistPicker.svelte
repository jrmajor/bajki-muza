<script lang="ts">
	import { useHttp } from '@inertiajs/svelte';
	import { route } from 'ziggy-js';
	import ComboBox from './ComboBox.svelte';

	let { value = $bindable() }: { value: string } = $props();

	let http = useHttp<{ search: string }, string[]>({ search: '' });

	async function getResults(value: string) {
		http.search = value;
		let artists = await http.get(route('ajax.artists'));
		return artists.map((a) => ({ label: a, value: a }));
	}
</script>

<ComboBox bind:value {getResults} allowsAnyString/>
