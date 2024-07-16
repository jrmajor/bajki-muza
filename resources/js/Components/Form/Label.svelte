<script lang="ts">
	import { type Snippet } from 'svelte';

	let {
		for: forId,
		children,
	}: {
		for: string;
		children: Snippet;
	} = $props();
</script>

<label for={forId} class="pb-1 w-full font-medium text-gray-700 dark:text-gray-400">
	{@render children()}
</label>
