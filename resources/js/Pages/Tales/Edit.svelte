<script lang="ts">
	import { route } from 'ziggy-js';
	import type { EditResource } from '@/types/tales';
	import Title from '@/Components/Title.svelte';
	import Form from './Components/Form/Form.svelte';

	let { tale }: { tale: EditResource } = $props();
</script>

<svelte:head>
	<title>{tale.title} - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<div class="text-center pb-4">
	<Title text={tale.title} href={route('tales.show', { tale })}/>
</div>

<Form {tale} action="edit"/>
