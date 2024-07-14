<script lang="ts">
	import { type Snippet } from 'svelte';

	let { href, children }: { href: string; children: Snippet } = $props();
</script>

<a {href} class="
	px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
	text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
	focus:outline-none hover:ring focus:ring active:ring ring-brand-primary hover:ring-opacity-50 focus:ring-opacity-75 active:ring-opacity-100
">
	{@render children()}
</a>
