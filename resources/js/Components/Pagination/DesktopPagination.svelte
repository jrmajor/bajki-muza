<script lang="ts">
	import PaginationNext from '@/Components/Icons/PaginationNext.svelte';
	import PaginationPrevious from '@/Components/Icons/PaginationPrevious.svelte';
	import PaginationLink from './PaginationLink.svelte';

	let { meta }: { meta: PaginationMeta } = $props();
</script>

<span class="inline-flex relative z-0 shadow-sm">
	{#each meta.links as element}
		{#if element.label === 'pagination.previous'}
			{#if meta.current_page === 1}
				<PaginationLink isEnabled={false} aria-label="Poprzednia" class="px-2 rounded-l-lg">
					<PaginationPrevious/>
				</PaginationLink>
			{:else}
				<PaginationLink url={element.url} rel="prev" aria-label="Poprzednia" class="px-2 rounded-l-lg">
					<PaginationPrevious/>
				</PaginationLink>
			{/if}
		{/if}

		{#if element.label === '...'}
			<PaginationLink isEnabled={false} class="px-4">
				{element.label}
			</PaginationLink>
		{/if}

		{#if !isNaN(parseInt(element.label))}
			{#if element.active}
				<PaginationLink isEnabled={false} isCurrent class="px-4">
					{element.label}
				</PaginationLink>
			{:else}
				<PaginationLink url={element.url} aria-label="Idź do strony {element.label}" class="px-4">
					{element.label}
				</PaginationLink>
			{/if}
		{/if}

		{#if element.label === 'pagination.next'}
			{#if meta.current_page === meta.last_page}
				<PaginationLink isEnabled={false} aria-label="Następna" class="px-2 rounded-r-lg">
					<PaginationNext/>
				</PaginationLink>
			{:else}
				<PaginationLink url={element.url} rel="next" aria-label="Następna" class="px-2 rounded-r-lg">
					<PaginationNext/>
				</PaginationLink>
			{/if}
		{/if}
	{/each}
</span>
