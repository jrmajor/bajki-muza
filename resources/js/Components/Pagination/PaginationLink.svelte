<script lang="ts">
	import { type Snippet } from 'svelte';
	import { inertia } from '@inertiajs/svelte';

	let {
		url = null,
		isEnabled = true,
		isCurrent = false,
		rel = null,
		'aria-label': ariaLabel = '',
		class: className = '',
		children,
	}: {
		url?: string | null;
		isEnabled?: boolean;
		isCurrent?: boolean;
		rel?: string | null;
		'aria-label'?: string;
		class?: string;
		children: Snippet;
	} = $props();
</script>

{#if isEnabled}
	<a
		use:inertia
		href={url}
		{rel}
		aria-label={ariaLabel}
		aria-current={isCurrent ? 'page' : null}
		class="
			{className}
			relative inline-flex items-center py-2 cursor-pointer
			text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md
			hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
			focus:outline-none focus:ring-3 focus:ring-brand-primary/25 focus:z-10
			transition ease-in-out duration-150
		"
	>
		{@render children()}
	</a>
{:else}
	<span
		aria-disabled="true"
		aria-label={ariaLabel}
		aria-current={isCurrent ? 'page' : null}
	>
		<span
			class="
				{className}
				relative inline-flex items-center py-2 cursor-default
				text-gray-400 bg-white dark:text-gray-500 dark:bg-gray-900 shadow-md
			"
			aria-hidden="true"
		>
			{@render children()}
		</span>
	</span>
{/if}
