<script lang="ts">
	import { type Snippet } from 'svelte';
	import { inertia } from '@inertiajs/svelte';

	let { href, children }: { href: string; children: Snippet } = $props();
</script>

<a
	use:inertia
	{href}
	class="
		px-5 py-2.5 bg-white dark:bg-gray-700 shadow-lg rounded-full
		text-gray-900 dark:text-white transition-colors-shadow duration-200 uppercase font-semibold tracking-wide
		focus:outline-none hover:ring-3 focus:ring-3 active:ring-3 hover:ring-brand-primary/50 focus:ring-brand-primary/75 active:ring-brand-primary/100
	"
>
	{@render children()}
</a>
