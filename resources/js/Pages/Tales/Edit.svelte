<script lang="ts">
	import { route } from 'ziggy-js';
	import type { EditResource } from '@/types/tales';
	import Layout from '@/Layouts/Layout.svelte';
	import Form from './Components/Form/Form.svelte';

	let { tale, user }: { tale: EditResource } & SharedProps = $props();
</script>

<svelte:head>
	<title>{tale.title} - Bajki Polskich Nagrań „Muza”</title>
</svelte:head>

<Layout {user}>
  <div class="text-center pb-4">
    <h2 class="text-2xl font-medium">
      <a href="{route('tales.show', { tale })}">
				{#each tale.title.split(' ') as word}
					<span class="shadow-title">{word}</span>
					<span class="hidden"></span>
				{/each}
      </a>
    </h2>
  </div>

  <Form {tale} action="edit"/>
</Layout>
