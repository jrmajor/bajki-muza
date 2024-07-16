<script lang="ts">
	import type { CreditType, EditResource } from '@/types/tales';
	import Layout from '@/Layouts/Layout.svelte';
	import Title from '@/Components/Title.svelte';
	import Form from './Components/Form/Form.svelte';

	let { user }: SharedProps = $props();

	const commonCreditTypes: CreditType[] = [
		'text',
		'author',
		'music',
		'directing',
		'recording_director',
		'sound_operator',
		'artwork',
	];

	const tale: EditResource = {
		slug: '',
		title: '',
		year: null,
		nr: null,
		discogs: null,
		notes: null,
		cover: null,
		credits: commonCreditTypes.map((c) => ({ artist: '', type: c, as: null, nr: 0 })),
		actors: Array(10).fill({ artist: '', characters: null }),
	};
</script>

<svelte:head>
	<title>Nowa bajka - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<Layout {user}>
	<div class="text-center pb-4">
		<Title text="Nowa bajka"/>
  </div>

  <Form {tale} action="create"/>
</Layout>
