<script lang="ts">
	import { type Snippet } from 'svelte';

	let {
		for: forId,
		inline = false,
		small = false,
		children,
	}: {
		for: string;
		inline?: boolean;
		small?: boolean;
		children: Snippet;
	} = $props();
</script>

<label
	for={forId}
	class="{inline ? 'pr-1' : 'pb-1 w-full'} font-medium text-gray-700 dark:text-gray-400"
	class:text-sm={small}
>
	{@render children()}
</label>
