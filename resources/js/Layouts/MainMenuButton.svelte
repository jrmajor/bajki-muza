<script lang="ts">
	import { type Snippet } from 'svelte';
	import { inertia } from '@inertiajs/svelte';

	let { href, children }: { href: string; children: Snippet } = $props();
</script>

<a
	use:inertia
	{href}
	class="
		rounded-full bg-white px-5 py-2.5 font-semibold tracking-wide
		text-gray-900 uppercase shadow-lg transition-all duration-200
		hover:ring-3 hover:ring-brand-primary/50
		focus:ring-3 focus:ring-brand-primary/75 focus:outline-none
		active:ring-3 active:ring-brand-primary/100
		dark:bg-gray-700 dark:text-white
	"
>
	{@render children()}
</a>
