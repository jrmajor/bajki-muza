<script lang="ts">
	import { route } from 'ziggy-js';
	import type { ShowResource } from '@/types/tales';
	import Title from '@/Components/Title.svelte';

	let { tale, user }: { tale: ShowResource, user: SharedUser } = $props();
</script>

<Title text={tale.title} href={route('tales.edit', { tale })} hrefIf={!!user}/>

{#if tale.year}
	<div class="mt-1">{tale.year}</div>
{/if}
