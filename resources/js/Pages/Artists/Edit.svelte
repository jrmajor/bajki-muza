<script lang="ts">
	import { route } from 'ziggy-js';
	import type { EditResource } from '@/types/artists';
	import Title from '@/Components/Title.svelte';
	import Form from './Components/Form/Form.svelte';

	let { artist }: { artist: EditResource } = $props();
</script>

<svelte:head>
	<title>{artist.name} - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<div class="text-center pb-4">
	<Title text={artist.name} href={route('artists.show', { artist })}/>
</div>

<Form {artist}/>
